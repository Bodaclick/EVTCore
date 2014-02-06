package main

import (
	"./types"
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

	pwd, err := os.Getwd()
	if err != nil {
		fmt.Println(err)
		os.Exit(1)
	}
	fmt.Println(pwd)

	f, err := os.Open(pwd + "/../app/config/parameters.yml")
	if err != nil {
		fmt.Printf("error opening file: %s\n", err)
		os.Exit(1)
	}
	fileReader := bufio.NewReader(f)
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
			fmt.Printf("line too long	")
		}
	}

	conn, err := amqp.Dial("amqp://" + rabbitmq_user + ":" + rabbitmq_pass + "@" + rabbitmq_host + ":" + rabbitmq_port + rabbitmq_vhost)
	failOnError(err, "Failed to connect to RabbitMQ")
	defer conn.Close()

	ch, err := conn.Channel()
	failOnError(err, "Failed to open a channel")

	defer ch.Close()

	msgs, err := ch.Consume("showroom-create-queue", "", false, false, false, false, nil)
	failOnError(err, "Failed to register a consumer")

	go func() {
		masgFailed, err := ch.Consume("showroom-create-comm-fail-queue", "", false, false, false, false, nil)
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
			var showroomCreationEvent types.ShowroomCreationEvent
			err := json.Unmarshal(msg.Body, &showroomCreationEvent)
			if err != nil {
				moveToFailQueue(ch, msg.Body)
				fmt.Printf("can't process the message: %s\n", err)
				msg.Ack(false)
				continue
			}

			postParams := url.Values{}
			postParams.Set("showroom[client]", "1")
			postParams.Set("showroom[evt_id]", strconv.Itoa(showroomCreationEvent.Showroom.Id))
			postParams.Set("showroom[name]", "1")
			postParams.Set("showroom[slug]", "1")
			postParams.Set("showroom[e-vertical]", showroomCreationEvent.Showroom.Vertical.Domain)
			postParams.Set("showroom[score]", "1")
			postParams.Set("showroom[location][lat]", "1")
			postParams.Set("showroom[location][long]", "1")
			postParams.Set("showroom[location][country]", "1")
			postParams.Set("showroom[extra_data]", "1")

			resp, err := http.PostForm(
				"http://api.evento.local/api/managers?apikey=1234",
				postParams)

			if err != nil {
				moveToFailQueue(ch, msg.Body)
				log.Printf("response err: %s", err)
			} else if resp.StatusCode != 201 {
				// Not created
				moveToFailQueue(ch, msg.Body)
				log.Printf("response status code: " + strconv.Itoa(resp.StatusCode))
				defer resp.Body.Close()
			}
			msg.Ack(false)
		}
	}()

	log.Printf(" [*] Waiting for messages. To exit press CTRL+C")
	<-done
	os.Exit(0)
}

func moveToFailQueue(ch *amqp.Channel, msgBody []byte) bool {
	ch.QueueDeclare("showroom-create-comm-fail-queue", true, false, false, false, nil)
	ch.Publish("", "showroom-create-comm-fail-queue", false, false, amqp.Publishing{Body: msgBody})
	return true
}

func moveFromFailToQueue(ch *amqp.Channel, msgBody []byte) bool {
	ch.Publish("", "showroom-create-queue", false, false, amqp.Publishing{Body: msgBody})
	return true
}
