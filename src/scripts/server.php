<?php
include_once __DIR__ . '/../../vendor/autoload.php';

use Connect4\Server\Server;

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$server = new Server();

$socket->on('connection', function ($connection)  use ($server) {
    $name = '';
    $connection->on('data', function($data) use (&$name, $server, $connection) {
        if ($name) return;
        $name = trim($data);
        $server->addSocketConnection($connection, $name);
    });
    
});

$socket->listen(1337, '0.0.0.0');
$loop->run();