<?php

namespace Connect4\Client\Brain;

use Connect4\Lib\Board;
use Connect4\Lib\Player;
use Connect4\Lib\Column;

class RandomBrain extends AbstractBrain
{    
    /**
     * @return Column
     */
    public function getColumn(Board $board)
    {
        $validColumns = [];
        foreach (range(Column::MIN, Column::MAX) as $columnNumber) {
            $column = new Column($columnNumber);
            if (! $board->isColumnFull($column)) {
                $validColumns[] = $column;
            }
        }
        shuffle($validColumns);
        return array_pop($validColumns);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Random Brain';
    }

}
