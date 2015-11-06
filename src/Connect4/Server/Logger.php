<?php

namespace Connect4\Server;

class Logger implements LoggerInterface
{
    public function log($message)
    {
        echo $message . "\n";
    }
}
