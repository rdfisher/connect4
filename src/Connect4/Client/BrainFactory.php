<?php

namespace Connect4\Client;

class BrainFactory
{
    /**
     * @param string $name
     * @return \Connect4\Client\Brain\BrainInterface
     * @throws \InvalidArgumentException
     */
    public function build($name)
    {
        switch ($name) {
            case 'RandomBrain':
                return new Brain\RandomBrain;
                break;
        }
        
        throw new \InvalidArgumentException('Brain not known: ' . $name);
    }
}
