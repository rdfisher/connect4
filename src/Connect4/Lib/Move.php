<?php
namespace Connect4\Lib;

use Connect4\Lib\Player;
use Connect4\Lib\Column;

class Move
{
    /**
     * @var Player
     */
    private $player;
    
    /**
     * @var Column
     */
    private $column;
    
    /**
     * @param Player $player
     * @param Column $column
     */
    public function __construct(Player $player, Column $column)
    {
        $this->player = $player;
        $this->column = $column;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->column;
    }
}
