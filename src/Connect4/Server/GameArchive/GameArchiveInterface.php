<?php
namespace Connect4\Server\GameArchive;

use Connect4\Lib\Board;
use DateTime;

interface GameArchiveInterface
{
    /**
     * @param Board $board
     * @param string $redPlayerName
     * @param string $yellowPlayerName
     * @param DateTime $date
     * @return integer
     */
    public function archive(
        Board $board, 
        $redPlayerName = '', 
        $yellowPlayerName = '', 
        DateTime $date = null
    );
    
    /**
     * @return ArchivedGame[]
     */
    public function getArchive();
    
    /**
     * @param integer $id
     * @return ArchivedGame|null
     */
    public function getById($id);
    
    /**
     * @return ArchivedGame[]
     */
    public function getArchiveSince($id);
}
