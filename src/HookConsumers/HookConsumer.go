package main

import (
	"bufio"
	"database/sql"
	"encoding/json"
	"fmt"
	"github.com/streadway/amqp"
	"log"
	"net/http"
	"net/url"
	"os"
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
	var rabbitmq_host = "localhost"
	var rabbitmq_port = "5672"
	var rabbitmq_vhost = "/"
	var rabbitmq_user = "guest"
	var rabbitmq_pass = "guest"

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
				moveFromFailToQueue(ch, msg.Body)
				msg.Ack(false)
			}
		}
	}()

	done := make(chan bool)

	db, err := sql.Open("mysql", "root:root@/evt_core")
	if err != nil {
		panic(err.Error()) // Just for example purpose. You should use proper error handling instead of panic
	}
	defer db.Close()

	go func() {
		for msg := range msgs {
			fmt.Printf("\n----------------------------\n%s\n", msg.Body)

			var hookEvent HookEvent
			err := json.Unmarshal(msg.Body, &hookEvent)
			if err != nil {
				moveToFailQueue(ch, msg.Body)
				fmt.Printf("can't process the message: %s\n", err)
				msg.Ack(false)
				continue
			}

			fmt.Printf("Event name: %s\n", hookEvent.Name)

			rows, err := db.Query("SELECT url FROM hook WHERE event = ?", hookEvent.Name)
			if err != nil {
				log.Fatal(err)
			}

			var hasFailed = false

			for rows.Next() {
				var hookurl string
				if err := rows.Scan(&hookurl); err != nil {
					log.Fatal(err)
				}
				fmt.Printf("Url is: %s for event: %s\n", hookurl, hookEvent.Name)
				postParams := url.Values{}
				postParams.Set("event", hookEvent.Name)

				resp, err := http.PostForm(hookurl, postParams)
				if err != nil {
					hasFailed = true
					log.Printf("response err: %s", err)
				} else if resp.StatusCode != 201 {
					hasFailed = true
					log.Printf("response status code: " + string(resp.StatusCode))
					defer resp.Body.Close()
				}
			}

			if hasFailed {
				moveToFailQueue(ch, msg.Body)
			}

			msg.Ack(false)

			if err := rows.Err(); err != nil {
				log.Fatal(err)
			}

		}
	}()

	log.Printf(" [*] Waiting for messages. To exit press CTRL+C")
	<-done
	os.Exit(0)
}

func moveToFailQueue(ch *amqp.Channel, msgBody []byte) bool {
	ch.Publish("", "events-hook-comm-fail-queue", false, false, amqp.Publishing{Body: msgBody})
	return true
}

func moveFromFailToQueue(ch *amqp.Channel, msgBody []byte) bool {
	ch.Publish("", "events-hook-queue", false, false, amqp.Publishing{Body: msgBody})
	return true
}

type HookEvent struct {
	Name string
}
