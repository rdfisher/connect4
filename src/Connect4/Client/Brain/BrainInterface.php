<?php
namespace Connect4\Client\Brain;

use Connect4\Lib\Board;
use Connect4\Lib\Player;
use Connect4\Lib\Column;

interface BrainInterface
{
    /**
     * @return Column
     */
    public function getColumn(Board $board);
    
    /**
     * @return Player
     */
    public function getIdentity();
    
    /**
     * @Param Player $player
     */
    public function setIdentity(Player $player);
    
    /**
     * 
     */
    public function getName();
}

