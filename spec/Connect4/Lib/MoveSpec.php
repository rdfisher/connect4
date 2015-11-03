<?php

namespace spec\Connect4\Lib;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Connect4\Lib\Player;
use Connect4\Lib\Column;

class MoveSpec extends ObjectBehavior
{
    function let(Player $player, Column $column)
    {
        $this->beConstructedWith($player, $column);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Lib\Move');
    }
    
    function it_returns_the_player(Player $player)
    {
        $this->getPlayer()->shouldBe($player);
    }
    
    function it_returns_the_column(Column $column)
    {
        $this->getColumn()->shouldBe($column);
    }
}
