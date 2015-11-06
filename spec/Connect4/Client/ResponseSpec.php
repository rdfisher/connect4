<?php

namespace spec\Connect4\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Connect4\Lib\Player;
use Connect4\Lib\Board;

class ResponseSpec extends ObjectBehavior
{
    function let(Player $player, Board $board)
    {
        $this->beConstructedWith($board, $player);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Client\Response');
    }
    
    function it_can_return_the_player(Player $player)
    {
        $this->getPlayer()->shouldBe($player);
    }
    
    function it_can_return_the_board(Board $board)
    {
        $this->getBoard()->shouldBe($board);
    }
}
