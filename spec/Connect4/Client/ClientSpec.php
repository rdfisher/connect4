<?php

namespace spec\Connect4\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Connect4\Client\Brain\BrainInterface as Brain;
use Connect4\Client\Response;
use Connect4\Lib\Player;
use Connect4\Lib\Board;
use Connect4\Lib\Column;
use Connect4\Lib\Move;

class ClientSpec extends ObjectBehavior
{
    function let(Brain $brain)
    {
        $this->beConstructedWith($brain);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Client\Client');
    }
    
    function it_can_handle_a_response_where_it_is_not_this_clients_turn(
        Response $response,
        Board $board,
        Brain $brain,
        Player $red,
        Player $yellow
    ) {
        $brain->setIdentity($red)->willReturn(null);
        $response->getPlayer()->willReturn($red);
        $response->getBoard()->willReturn($board);
        $board->getNextPlayer()->willReturn($yellow);
        $this->handle($response)->shouldBeNull();
    }
    
    function it_can_handle_a_response_where_it_is_this_clients_turn(
        Response $response,
        Board $board,
        Brain $brain,
        Player $red,
        Column $column
    ) {
        $response->getPlayer()->willReturn($red);
        $response->getBoard()->willReturn($board);
        $board->getNextPlayer()->willReturn($red);
        $brain->getColumn($board)->willReturn($column);
        $brain->setIdentity($red)->willReturn(null);
        $move = $this->handle($response);
        $move->shouldHaveType('Connect4\Lib\Move');
        $move->getPlayer()->shouldBe($red);
        $move->getColumn()->shouldBe($column);
    }
    
}
