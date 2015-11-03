<?php
namespace Connect4\Lib\Value;

use InvalidArgumentException;

abstract class Enum
{
    /** @var mixed */
    protected $value;

    /** @var array */
    protected static $constants = array();

    /**
     *
     * @param  mixed                 $value
     * @throws InvalidArgumentException
     */
    public function __construct($value = null)
    {
        $constants = static::getConstants();
        if (is_null($value) && isset($constants['__DEFAULT'])) {
            $value = $constants['__DEFAULT'];
        }
        if (! in_array($value, $constants)) {
            throw new InvalidArgumentException('Invalid enum value: ' . $value);
        }
        $this->value = $value;
    }

    /**
     * @return array
     */
    protected static function getConstants()
    {
        $calledClass = get_called_class();

        if (! isset(static::$constants[$calledClass])) {
            $class                           = new \ReflectionClass($calledClass);
            static::$constants[$calledClass] = $class->getConstants();
        }

        return static::$constants[$calledClass];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @return array
     */
    public static function getValues()
    {
        $constants = static::getConstants();
        unset($constants['__DEFAULT']);

        return $constants;
    }
}
