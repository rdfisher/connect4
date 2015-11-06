<?php
namespace Connect4\Client\Brain;

use Connect4\Lib\Player;
use Connect4\Lib\Board;
use Connect4\Lib\Column;

abstract class AbstractBrain implements BrainInterface
{
    /**
     * @var Player
     */
    private $identity;
    
    /**
     * @return Column
     */
    abstract public function getColumn(Board $board);
    
    /**
     * @return Player
     */
    public function getIdentity()
    {
        return $this->identity;
    }
    
    /**
     * @Param Player $player
     */
    public function setIdentity(Player $player)
    {
        $this->identity = $player;
    }
}

