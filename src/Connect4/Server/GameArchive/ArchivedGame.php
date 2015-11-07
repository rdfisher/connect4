<?php
namespace Connect4\Server\GameArchive;

use Connect4\Lib\Board;
use DateTime;

class ArchivedGame
{
    /**
     * @var Board
     */
    private $board;
    
    /**
     * @var string
     */
    private $redPlayerName;
    
    /**
     * @var string
     */
    private $yellowPlayerName;

    /**
     * @var DateTime
     */
    private $date;
    
    /**
     * @param Board $board
     * @param string $redPlayerName
     * @param string $yellowPlayerName
     * @param DateTime $date
     */
    public function __construct(
        Board $board, 
        $redPlayerName = '', 
        $yellowPlayerName = '', 
        DateTime $date = null
    ) {
        $this->board = $board;
        $this->redPlayerName = (string)$redPlayerName;
        $this->yellowPlayerName = (string)$yellowPlayerName;
        $this->date = $date ?: new DateTime();
    }

    /**
     * @return Board
     */
    function getBoard()
    {
        return $this->board;
    }

    /**
     * @return string
     */
    function getRedPlayerName()
    {
        return $this->redPlayerName;
    }

    /**
     * @return string
     */
    function getYellowPlayerName()
    {
        return $this->yellowPlayerName;
    }

    /**
     * @return DateTime
     */
    function getDate()
    {
        return $this->date;
    }


}
