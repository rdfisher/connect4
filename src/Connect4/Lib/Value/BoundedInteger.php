<?php
namespace Connect4\Lib\Value;

use InvalidArgumentException;

abstract class BoundedInteger implements ScalarValueInterface
{
    /**
     * @var integer
     */
    private $value;
    
    /**
     * @param integer $value
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        if (!is_integer($value)) {
            throw new InvalidArgumentException("Not an integer");
        }
        
        if ($value < static::MIN) {
            throw new InvalidArgumentException("Too small");
        }
        
        if ($value > static::MAX) {
            throw new InvalidArgumentException("Too large");
        }
        
        $this->value = $value;
    }
    
    /**
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }
}
