<?php

namespace spec\Connect4\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BrainFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Client\BrainFactory');
    }
    
    function it_can_create_a_randomBrain()
    {
        $this->build('RandomBrain')->shouldHaveType('Connect4\Client\Brain\RandomBrain');
    }
    
    function it_throws_exception_if_asked_for_an_unknown_brain()
    {
        $this->shouldThrow('InvalidArgumentException')->duringBuild('Foo');
    }
}
