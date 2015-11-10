<?php

namespace Connect4\Client\Brain;

use Connect4\Lib\Board;
use Connect4\Lib\Player;
use Connect4\Lib\Column;
use Connect4\Client\Brain\Thought\ThoughtInterface;

class ThoughtfulBrain extends AbstractBrain
{   
    private $thoughts = array();
    
    public function addThought(ThoughtInterface $thought)
    {
        $this->thoughts[] = $thought;
    }
    
    public function getColumn(Board $board)
    {
        $columns = $this->getPossibleColumns($board);
        
        foreach ($this->thoughts as $thought) {
            $columns = $thought->filter($board, $columns);
        }
        
        if (empty($columns)) {
            $columns = $this->getPossibleColumns($board);
        }
        
        shuffle($columns);
        return array_pop($columns);
    }

    public function getName()
    {
        return "RF";
    }
    
    private function getPossibleColumns(Board $board)
    {
        $validColumns = [];
        foreach (range(Column::MIN, Column::MAX) as $columnNumber) {
            $column = new Column($columnNumber);
            if (! $board->isColumnFull($column)) {
                $validColumns[] = $column;
            }
        }
        return $validColumns;
    }

}