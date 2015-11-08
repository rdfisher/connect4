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
     * @var integer
     */
    private $id;
    
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
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @return string
     */
    public function getRedPlayerName()
    {
        return $this->redPlayerName;
    }

    /**
     * @return string
     */
    public function getYellowPlayerName()
    {
        return $this->yellowPlayerName;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}
