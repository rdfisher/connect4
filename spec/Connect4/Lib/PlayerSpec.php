<?php

namespace spec\Connect4\Lib;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PlayerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('red');
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Lib\Player');
    }
    
    function it_can_return_its_value()
    {
        $this->getValue()->shouldBe('red');
    }
    
    function it_can_return_the_counterpart()
    {
        $yellow = $this->getCounterpart();
        $yellow->shouldHaveType('Connect4\Lib\Player');
        $yellow->getValue()->shouldBe('yellow');
        
        $yellow->getCounterpart()->getValue()->shouldBe('red');
    }
}
