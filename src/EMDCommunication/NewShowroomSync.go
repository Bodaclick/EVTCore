package main

import (
	"EMDCommunication/types"
	"bufio"
	"encoding/json"
	"fmt"
	"github.com/streadway/amqp"
	"log"
	"net/http"
	"net/url"
	"os"
	"strconv"
	"strings"
	"time"
	"io/ioutil"
)

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
	var emd_api_root = "emd.api.root/"
	var emd_api_showroom = "api/showroom"

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
			case "emd_api_root":
				emd_api_root = value
			case "emd_api_showroom":
				emd_api_showroom = value
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
	
	ch.QueueDeclare("emd-showroom-created-comm-fail-queue", true, false, false, false, nil)
	
	msgs, err := ch.Consume("emd-showroom-created-queue", "", false, false, false, false, nil)
	failOnError(err, "Failed to register a consumer")

	go func() {
		masgFailed, err := ch.Consume("emd-showroom-created-comm-fail-queue", "", false, false, false, false, nil)
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

	go func() {
		for msg := range msgs {
			var showroom types.Showroom
			err := json.Unmarshal(msg.Body, &showroom)
			if err != nil {
				moveToFailQueue(ch, msg.Body)
				fmt.Printf("can't process the message: %s\n", err)
				msg.Ack(false)
				continue
			}

			postParams := url.Values{}
			postParams.Set("showroom[client]", "1")
			postParams.Set("showroom[partner]", "bdkEV2014")
			postParams.Set("showroom[evt_id]", strconv.Itoa(showroom.Id))
			postParams.Set("showroom[name]", showroom.Provider.Name)
			postParams.Set("showroom[slug]", showroom.Slug)
			postParams.Set("showroom[e-vertical]", showroom.Vertical.Domain)
			postParams.Set("showroom[score]", strconv.Itoa(showroom.Score))
			postParams.Set("showroom[location][lat]", strconv.Itoa(showroom.Provider.Location.Lat))
			postParams.Set("showroom[location][long]", strconv.Itoa(showroom.Provider.Location.Long))
			postParams.Set("showroom[location][country]", showroom.Provider.Location.Country)
			postParams.Set("showroom[extra_data]", showroom.Extra_data)

log.Printf("------------------------------")
log.Printf("URL: %s", emd_api_root+emd_api_showroom)
log.Printf("Params: %s", postParams)
log.Printf(" ")
			resp, err := http.PostForm(
				"http://"+emd_api_root+emd_api_showroom,
				postParams)
				

			if err != nil {
				moveToFailQueue(ch, msg.Body)
				log.Printf("response err: %s", err)
			} else if resp.StatusCode == 200 {
				var dat map[string]interface{}
				body, err := ioutil.ReadAll(resp.Body)
				if err != nil {
					moveToFailQueue(ch, msg.Body)
					log.Printf("ReadAll err: %s", err)
				}
				if err := json.Unmarshal(body, &dat); err != nil {
					moveToFailQueue(ch, msg.Body)
					log.Printf("response not valid json: " + string(body))
					defer resp.Body.Close()
				} else {
					var status = dat["ok"].(string)
					if status != "true" {
						moveToFailQueue(ch, msg.Body)
						log.Printf("response error: " + dat["result"].(string))
						defer resp.Body.Close()
					} else {
						log.Printf("status true, result: " + dat["result"].(string))
					}
				}
			} else {
				log.Printf("response status code: " + strconv.Itoa(resp.StatusCode))
			}
			msg.Ack(false)
		}
	}()

	<-done
	os.Exit(0)
}

func moveToFailQueue(ch *amqp.Channel, msgBody []byte) bool {
	ch.Publish("", "emd-showroom-created-comm-fail-queue", false, false, amqp.Publishing{Body: msgBody})
	return true
}

func moveFromFailToQueue(ch *amqp.Channel, msgBody []byte) bool {
	ch.Publish("", "emd-showroom-created-queue", false, false, amqp.Publishing{Body: msgBody})
	return true
}
