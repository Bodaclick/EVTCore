package main

import (
	"bufio"
	"crypto/md5"
	"database/sql"
	"encoding/json"
	"fmt"
	"github.com/streadway/amqp"
	"log"
	"net/http"
	"os"
	"strconv"
	"strings"
	"time"
)
import _ "github.com/go-sql-driver/mysql"

func failOnError(err error, msg string) {
	if err != nil {
		log.Fatalf("%s: %s", msg, err)
		panic(fmt.Sprintf("%s: %s", msg, err))
	}
}

func main() {
	// Default values
	rabbitmq_host := "localhost"
	rabbitmq_port := "5672"
	rabbitmq_vhost := "/"
	rabbitmq_user := "guest"
	rabbitmq_pass := "guest"
	database_host := "127.0.0.1"
	database_port := "3306"
	database_name := "evt_core"
	database_user := "root"
	database_password := "root"

	pwd, err := os.Getwd()
	if err != nil {
		fmt.Println(err)
		os.Exit(1)
	}

	file, err := os.Open(pwd + "/../app/config/parameters.yml")
	if err != nil {
		fmt.Printf("error opening file: %s\n", err)
		os.Exit(1)
	}
	fileReader := bufio.NewReader(file)
	line, longLine, err := fileReader.ReadLine()
	for err == nil {

		var keyValue = strings.Split(string(line), ":")

		if len(keyValue) > 1 {
			var key = strings.Trim(keyValue[0], " ")
			var value = strings.Trim(keyValue[1], " ")

			switch key {
			case "rabbitmq_host":
				rabbitmq_host = value
			case "rabbitmq_port":
				rabbitmq_port = value
			case "rabbitmq_vhost":
				rabbitmq_vhost = value
			case "rabbitmq_user":
				rabbitmq_user = value
			case "rabbitmq_pass":
				rabbitmq_pass = value
			case "database_host":
				database_host = value
			case "database_name":
				database_name = value
			case "database_user":
				database_user = value
			case "database_password":
				database_password = value
			case "database_port":
				if value != "null" {
					database_port = value
				}
			}
		}

		line, longLine, err = fileReader.ReadLine()
		if longLine {
			fmt.Printf("line too long")
		}
	}

	conn, err := amqp.Dial("amqp://" + rabbitmq_user + ":" + rabbitmq_pass + "@" + rabbitmq_host + ":" + rabbitmq_port + rabbitmq_vhost)
	failOnError(err, "Failed to connect to RabbitMQ")
	defer conn.Close()

	ch, err := conn.Channel()
	failOnError(err, "Failed to open a channel")

	defer ch.Close()

	ch.QueueDeclare("events-hook-comm-fail-queue", true, false, false, false, nil)

	msgs, err := ch.Consume("events-hook-queue", "", false, false, false, false, nil)
	failOnError(err, "Failed to register a consumer")

	go func() {
		masgFailed, err := ch.Consume("events-hook-comm-fail-queue", "", false, false, false, false, nil)
		if err != nil {
			fmt.Printf("Failed to register a failed consumer: %s\n", err)
		} else {
			for msg := range masgFailed {
				time.Sleep(time.Duration(10) * time.Second)
				moveFromFailToQueue(ch, msg)
				msg.Ack(false)
			}
		}
	}()

	done := make(chan bool)

	db, err := sql.Open("mysql", database_user+":"+database_password+"@tcp("+database_host+":"+database_port+")/"+database_name)
	if err != nil {
		panic(err.Error())
	}
	defer db.Close()

	go func() {
		for msg := range msgs {
			fmt.Printf("\n----------------------------\n%s\n", msg.Body)

			var dat map[string]interface{}
			if err := json.Unmarshal(msg.Body, &dat); err != nil {
				moveToFailQueue(ch, msg.Body, nil)
				fmt.Printf("can't process the message: %s\n", err)
				msg.Ack(false)
				continue
			}

			var hookName = dat["name"].(string)

			rows, err := db.Query("SELECT url FROM hook WHERE event = ?", hookName)
			if err != nil {
				log.Fatal(err)
			}

			hookMessage := MessageFactory(hookName)

			// Get the headers
			sended := msg.Headers["sended"]

			var sendedUrlHashArray map[string]string
			if sended != nil {
				// Get the sended urlshashs
				if err := json.Unmarshal([]byte(sended.(string)), &sendedUrlHashArray); err != nil {
					log.Fatal(err)
				}
			} else {
				sendedUrlHashArray = make(map[string]string)
			}

			var hasFailed = false

			for rows.Next() {
				var hookurl string
				if err := rows.Scan(&hookurl); err != nil {
					log.Fatal(err)
				}

				hash := md5.New()
				hash.Write([]byte(hookurl))
				hookUrlMD5 := fmt.Sprintf("%x", hash.Sum(nil))

				// Check if already send
				if sendedUrlHashArray[hookUrlMD5] == "202" {
					log.Printf("Url: %s for event: %s Already Called\n", hookurl, hookName)
				} else {
					log.Printf("Url: %s for event: %s (hash: %s)\n", hookurl, hookName, hookUrlMD5)
					var leadJson []byte
					if hookMessage.getDataName() == "" {
						leadJson, _ = json.Marshal(dat)
					} else {
						leadJson, _ = json.Marshal(dat[hookMessage.getDataName()])
					}

					postParams := strings.NewReader(string(leadJson))
					req, err := http.NewRequest("POST", hookurl, postParams)
					// Don't forget to set the content type, this will contain the boundary.
					req.Header.Set("Content-Type", "application/json")

					// Submit the request
					client := &http.Client{}
					resp, err := client.Do(req)

					if err != nil {
						hasFailed = true
						log.Printf("response err: %s", err)
						sendedUrlHashArray[hookUrlMD5] = "error"
					} else if resp.StatusCode != 202 {
						hasFailed = true
						log.Printf("response status code: " + strconv.Itoa(resp.StatusCode))
						sendedUrlHashArray[hookUrlMD5] = strconv.Itoa(resp.StatusCode)
						defer resp.Body.Close()
					} else {
						sendedUrlHashArray[hookUrlMD5] = strconv.Itoa(resp.StatusCode)
						defer resp.Body.Close()
					}
				}
			}

			if hasFailed {
				moveToFailQueue(ch, msg.Body, sendedUrlHashArray)
			}

			msg.Ack(false)

			if err := rows.Err(); err != nil {
				log.Fatal(err)
			}

		}
	}()

	<-done
	os.Exit(0)
}

func moveToFailQueue(ch *amqp.Channel, msgBody []byte, headers map[string]string) bool {
	var tableHeader amqp.Table
	tableHeader = make(amqp.Table)
	mapB, _ := json.Marshal(headers)
	tableHeader["sended"] = string(mapB)
	ch.Publish("", "events-hook-comm-fail-queue", false, false, amqp.Publishing{Headers: tableHeader, Body: msgBody})
	return true
}

func moveFromFailToQueue(ch *amqp.Channel, msg amqp.Delivery) bool {
	ch.Publish("", "events-hook-queue", false, false, amqp.Publishing{Headers: msg.Headers, Body: msg.Body})
	return true
}

type hookMessageInterface interface {
	getDataName() string
}

type LeadCreatedEvent struct {
}

func (this *LeadCreatedEvent) getDataName() string {
	return "lead"
}

type UserCreatedEvent struct {
}

func (this *UserCreatedEvent) getDataName() string {
	return ""
}

type ShowroomCreatedEvent struct {
}

func (this *ShowroomCreatedEvent) getDataName() string {
	return "showroom"
}

func MessageFactory(hookName string) hookMessageInterface {
	if hookName == "evt.event.lead_create" {
		return new(LeadCreatedEvent)
	} else if hookName == "evt.event.user_create" {
		return new(UserCreatedEvent)
	} else if hookName == "evt.event.showroom_create" {
		return new(ShowroomCreatedEvent)
	}
	return new(LeadCreatedEvent)
}
