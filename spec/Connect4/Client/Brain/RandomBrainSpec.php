<?php

namespace spec\Connect4\Client\Brain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Connect4\Lib\Player;
use Connect4\Lib\Board;

class RandomBrainSpec extends ObjectBehavior
{   
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Client\Brain\BrainInterface');
        $this->shouldHaveType('Connect4\Client\Brain\AbstractBrain');
        $this->shouldHaveType('Connect4\Client\Brain\RandomBrain');
    }
    
    function it_should_return_a_column(Board $board)
    {
        $this->getColumn($board)->shouldHaveType('Connect4\Lib\Column');
    }
}
