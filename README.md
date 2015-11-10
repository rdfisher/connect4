Connect 4
=========

Running Locally
---------------

Start a server:
- php scripts/connect4.php connect4:server

Start 2 clients:
- php scripts/connect4.php connect4:client NameOfBrain 127.0.0.1
- php scripts/connect4.php connect4:client NameOfBrain 127.0.0.1

See the result:
- http://127.0.0.1:8080

Docker
------

Build the project with `make` or `make build`

Run the server with `make start`

Now connect two clients with `make client server=<docker_host_ip>`

Watch the logs on connect4 server: `docker logs -f connect4`
