<?php
namespace Connect4\Client;

use Connect4\Lib\Player;
use Connect4\Lib\Board;

class Response
{
    /**
     * @var Player
     */
    private $player;
    
    /**
     * @var Board
     */
    private $board;
    
    public function __construct(Board $board, Player $player)
    {
        $this->board = $board;
        $this->player = $player;
    }
    
    /**
     * @return Player
     */
    function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return Board
     */
    function getBoard()
    {
        return $this->board;
    }
}
