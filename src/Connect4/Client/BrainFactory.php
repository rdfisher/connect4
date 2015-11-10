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
            case 'ThoughtfulBrain':
                $thoughtfulBrain = new Brain\ThoughtfulBrain();
                $opportunism = new Brain\Thought\Opportunism();
                $thoughtfulBrain->addThought($opportunism);
                $thoughtfulBrain->addThought(new Brain\Thought\Caution());
                $thoughtfulBrain->addThought(new Brain\Thought\Tenacity());
                return $thoughtfulBrain;
                break;
        }
        
        throw new \InvalidArgumentException('Brain not known: ' . $name);
    }
}
