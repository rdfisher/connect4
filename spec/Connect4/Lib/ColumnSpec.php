<?php

namespace spec\Connect4\Lib;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Lib\Column');
    }
        
    function let()
    {
        $this->beConstructedWith(4);
    }
    
    function it_rejects_null()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [null]);
    }
    
    function it_rejects_strings()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', ['abc']);
    }
    
    function it_rejects_floats()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [1.00]);
    }
    
    function it_rejects_numbers_less_than_one()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [0]);
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [-4]);
    }
    
    function it_rejects_numbers_bigger_than_seven()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [8]);
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [9]);
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [912324345]);
    }
}
