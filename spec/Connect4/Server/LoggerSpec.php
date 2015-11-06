<?php

namespace spec\Connect4\Server;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LoggerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Server\LoggerInterface');
        $this->shouldHaveType('Connect4\Server\Logger');
    }
}
