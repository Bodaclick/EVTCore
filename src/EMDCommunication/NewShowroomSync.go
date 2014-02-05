package main

import (
	"bufio"
	"fmt"
	"github.com/streadway/amqp"
	"io/ioutil"
	"log"
	"net/http"
	"net/url"
	"os"
	"strings"
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

	// TODO Get the parameter value
	f, err := os.Open(pwd + "/../app/config/parameters.yml")
	if err != nil {
		fmt.Printf("error opening file: %s\n", err)
		os.Exit(1)
	}
	r := bufio.NewReader(f)
	s, n, e := r.ReadLine()
	for e == nil {

		var keyValue = strings.Split(string(s), ":")

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
		s, n, e = r.ReadLine()
		if n {
			fmt.Printf("line too long	")
		}
	}

	conn, err := amqp.Dial("amqp://" + rabbitmq_user + ":" + rabbitmq_pass + "@" + rabbitmq_host + ":" + rabbitmq_port + rabbitmq_vhost)
	failOnError(err, "Failed to connect to RabbitMQ")
	defer conn.Close()

	ch, err := conn.Channel()
	failOnError(err, "Failed to open a channel")

	defer ch.Close()

	msgs, err := ch.Consume("log-queue", "", false, false, false, false, nil)
	failOnError(err, "Failed to register a consumer")

	done := make(chan bool)

	go func() {
		for d := range msgs {
			log.Printf("Received a message: %s", d.Body)
			resp, err := http.PostForm(
				"http://api.evento.local/api/managers?apikey=1234",
				url.Values{"key": {"Value"}, "id": {"123"}})

			if err != nil {
				// handle error
				log.Printf("response err: %s", err)
			}
			defer resp.Body.Close()
			body, err := ioutil.ReadAll(resp.Body)

			log.Printf("response status: %n", resp.StatusCode)
			log.Printf("response head: %s", resp.Header)
			log.Printf("response cont: %s", body)
			if err != nil {
				// handle error
				log.Printf("response err: %s", err)
			}
			d.Ack(false)
		}
	}()

	log.Printf(" [*] Waiting for messages. To exit press CTRL+C")
	<-done
	os.Exit(0)
}
