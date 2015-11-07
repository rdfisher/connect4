<?php

namespace spec\Connect4\Server;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use React\Socket\Connection as SocketConnection;
use Connect4\Server\LoggerInterface as Logger;

class ServerSpec extends ObjectBehavior
{
    function let(Logger $logger)
    {
        $this->beConstructedWith($logger);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Server\Server');
    }
    
    function it_has_no_connections_initially()
    {
        $this->getConnections()->shouldBe([]);
    }
    
    function it_can_add_a_socket_connection(SocketConnection $socketConnection)
    {
        $result = $this->addSocketConnection($socketConnection, 'foo');
        $result->shouldHaveType('Connect4\Server\Connection');
        $result->getSocketConnection()->shouldBe($socketConnection);
        $result->getName()->shouldBe('foo');
        $this->getConnections()->shouldBe([$result]);
    }
    
    function it_can_add_two_socket_connections(
        SocketConnection $socketConnection1,
        SocketConnection $socketConnection2
    ) {
        $result1 = $this->addSocketConnection($socketConnection1, 'foo');
        $this->getConnections()->shouldBe([$result1]);
        $result2 = $this->addSocketConnection($socketConnection2, 'bar');
        $this->getConnections()->shouldBe([$result1, $result2]);
    }
    
    function it_only_accepts_two_connections(
        SocketConnection $socketConnection1,
        SocketConnection $socketConnection2,
        SocketConnection $socketConnection3
    ) {
        $this->addSocketConnection($socketConnection1, 'foo')->shouldHaveType('Connect4\Server\Connection');
        $this->addSocketConnection($socketConnection2, 'bar')->shouldHaveType('Connect4\Server\Connection');
        $this->addSocketConnection($socketConnection3, 'qux')->shouldBe(null);
        $socketConnection3->close()->shouldHaveBeenCalled();
    }
    
    function it_is_ready_once_there_are_two_connections(
        SocketConnection $socketConnection1,
        SocketConnection $socketConnection2
    ) {
        $this->isReady()->shouldBe(false);
        $this->addSocketConnection($socketConnection1, 'foo')->shouldHaveType('Connect4\Server\Connection');
        $this->isReady()->shouldBe(false);
        $this->addSocketConnection($socketConnection2, 'bar')->shouldHaveType('Connect4\Server\Connection');
        $this->isReady()->shouldBe(true);
    }
}