<?php

namespace Connect4\Server;

use React\Socket\Connection as SocketConnection;
use Connect4\Lib\Player;

class Connection
{
    /**
     * @var SocketConnection
     */
    private $socketConnection;
    
    /**
     * @var Player
     */
    private $player;
 
    /**
     * @var string
     */
    private $name = '';
    
    /**
     * @param SocketConnection $socketConnection
     * @param Player $player
     */
    public function __construct(SocketConnection $socketConnection, Player $player)
    {
        $this->socketConnection = $socketConnection;
        $this->player = $player;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return SocketConnection
     */
    public function getSocketConnection()
    {
        return $this->socketConnection;
    }

    /**
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    function setName($name)
    {
        $this->name = (string)$name;
    }
}
