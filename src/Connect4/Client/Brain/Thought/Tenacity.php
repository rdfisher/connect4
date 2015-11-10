<?php
namespace Connect4\Client\Brain\Thought;

use Connect4\Lib\Board;
use Connect4\Lib\Column;

class Tenacity implements ThoughtInterface
{
    private $column;
    public function __construct()
    {
        $this->column = new Column((int)rand(Column::MIN, Column::MAX));
    }
    
    /**
     * If the move we took last time is one of the remaining possible moves, do that
     * 
     * @param Board $board
     * @param array $columns
     * @return array
     */
    public function filter(Board $board, array $columns)
    {
        foreach ($columns as $column) {
            if ($column == $this->column) {
                return [$column];
            }
        }
        
        shuffle($columns);
        
        $this->column = current($columns);
        
        if ($this->column) {
            return [$this->column];
        }
        
        return $columns;
    }
}