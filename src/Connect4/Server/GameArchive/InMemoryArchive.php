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
        $archivedGame = new ArchivedGame($board, $redPlayerName, $yellowPlayerName, $date);
        $this->archive[] = $archivedGame;
        $id = count($this->archive);
        $archivedGame->setId($id);
        return $id;
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
        $filteredArchive = [];
        foreach ($this->archive as $key => $game) {
            if (($key + 1) > $id) {
                $filteredArchive[$key] = $game;
            }
        }
        return $filteredArchive;
    }
}
