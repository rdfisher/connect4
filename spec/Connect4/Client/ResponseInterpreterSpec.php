<?php

namespace spec\Connect4\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Connect4\Client\Response;
use Connect4\Lib\Player;
use Connect4\Lib\Board;
use Connect4\Lib\Move;
use Connect4\Lib\Column;

class ResponseInterpreterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Client\ResponseInterpreter');
    }
    
    function it_can_get_a_response_from_an_initial_state()
    {
        $response = $this->buildResponse('{"startPlayer":"red","state":"red_plays_next","you":"yellow","transcript":[]}');
        $response->shouldHaveType('Connect4\Client\Response');
        $response->getPlayer()->shouldBeLike(new Player(Player::YELLOW));
        $board = $response->getBoard();
        $board->getNextPlayer()->shouldBeLike(new Player(Player::RED));
        $board->getTranscript()->shouldBe([]);
    }
    
    function it_can_get_a_response_for_a_game_after_two_moves()
    {
        $red = new Player(Player::RED);
        $yellow = new Player(Player::YELLOW);
        $response = $this->buildResponse('{"startPlayer":"red","state":"red_plays_next","you":"yellow","transcript":[["red",1],["yellow",4]]}');
        $response->shouldHaveType('Connect4\Client\Response');
        $response->getPlayer()->shouldBeLike($yellow);
        $board = $response->getBoard();
        $board->getNextPlayer()->shouldBeLike($red);
        $move1 = new Move($red, new Column(1));
        $move2 = new Move($yellow, new Column(4));
        $board->getTranscript()->shouldBeLike([$move1, $move2]);
    }
}
