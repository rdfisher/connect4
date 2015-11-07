<?php
namespace Connect4\Server\GameArchive;

use Connect4\Lib\Board;
use Connect4\Server\GameArchive\ArchivedGame;
use DateTime;

class InMemoryArchive implements GameArchiveInterface
{
    /**
     * @var ArchivedGame[]
     */
    private $archive = [];
    
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
    ) {
        $this->archive[] = new ArchivedGame($board, $redPlayerName, $yellowPlayerName, $date);
        return count($this->archive);
    }
    
    /**
     * @return ArchivedGame[]
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * @param integer $id
     * @return ArchivedGame|null
     */
    public function getById($id)
    {
        $index = $id - 1;
        if (isset($this->archive[$index])) {
            return $this->archive[$index];
        }
    }
    
    /**
     * @return ArchivedGame[]
     */
    public function getArchiveSince($id)
    {
        return array_filter($this->archive, function($v, $k) use ($id) {
            return ($k + 1) > $id; 
        }, ARRAY_FILTER_USE_BOTH);
    }
}
