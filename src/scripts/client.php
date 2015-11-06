<?php
include_once __DIR__ . '/../../vendor/autoload.php';

//Edit these lines as appropriate
const SERVER_ADDRESS = '127.0.0.1';
$brain = new \Connect4\Client\Brain\RandomBrain();

//Leave the rest alone

$responseInterpreter = new \Connect4\Client\ResponseInterpreter();
$client = new \Connect4\Client\Client($brain);

$loop = React\EventLoop\Factory::create();

$dnsResolverFactory = new React\Dns\Resolver\Factory();
$dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

$connector = new React\SocketClient\Connector($loop, $dns);

$connector->create(SERVER_ADDRESS, 1337)->then(function (React\Stream\Stream $stream) use ($brain, $responseInterpreter, $client) {
    $stream->write($brain->getName());
    $stream->on('data', function($data) use ($stream, $client, $responseInterpreter){
       if (! $data) {
           die ('CONNECTION TERMINATED' . $data);
       }
       $response = $responseInterpreter->buildResponse($data);
       $move = $client->handle($response);
       if ($move) {
           $stream->write($move->getColumn()->getValue());
       }
    });
});

$loop->run();