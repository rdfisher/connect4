<?php
namespace Connect4\Lib;

use Connect4\Lib\Value\Enum;

class Player extends Enum
{
    const RED  = 'red';
    const YELLOW = 'yellow';
    
    public function getCounterpart()
    {
        if ($this->getValue() == self::RED) {
            return new self(self::YELLOW);
        }
        
        return new self(self::RED);
    }
}
