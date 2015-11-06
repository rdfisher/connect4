<?php

namespace spec\Connect4\Server;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use React\Socket\Connection as SocketConnection;
use Connect4\Lib\Player;

class ConnectionSpec extends ObjectBehavior
{
    function let(SocketConnection $socketConnection, Player $player)
    {
        $this->beConstructedWith($socketConnection, $player);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Server\Connection');
    }
    
    function it_can_return_the_player(Player $player)
    {
        $this->getPlayer()->shouldBe($player);
    }
    
    function it_can_return_the_connection(SocketConnection $socketConnection)
    {
        $this->getSocketConnection()->shouldBe($socketConnection);
    }
}
