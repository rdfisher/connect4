<?php

namespace spec\Connect4\Server\GameArchive;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Connect4\Lib\Board;
use DateTime;

class ArchivedGameSpec extends ObjectBehavior
{
    function let(Board $board, DateTime $date)
    {
        $this->beConstructedWith($board, 'Jim', 'Bob', $date);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Server\GameArchive\ArchivedGame');
    }
    
    function it_has_getters(Board $board, DateTime $date)
    {
        $this->getDate()->shouldBe($date);
        $this->getBoard()->shouldBe($board);
        $this->getRedPlayerName()->shouldBe('Jim');
        $this->getYellowPlayerName()->shouldBe('Bob');
    }
}
