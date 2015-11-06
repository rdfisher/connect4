server ?= localhost
all: build

build:
	docker build -t connect4 -f docker/Dockerfile .

run:
	docker run --rm -it connect4 bash

start: stop
	docker run -d --name connect4 -p 1337:1337 connect4

stop:
	@docker rm -vf connect4 ||:

client:
	docker run --rm -it connect4 php src/scripts/client.php $(server)

.PHONY: all build run start stop client
