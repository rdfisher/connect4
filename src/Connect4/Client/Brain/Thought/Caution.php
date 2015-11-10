<?php
namespace Connect4\Client\Brain\Thought;

use Connect4\Lib\Board;
use Connect4\Lib\Column;
class Caution implements ThoughtInterface
{
    /**
     * If a move gives the oponent a winning move, don't take it
     * 
     * @param Board $board
     * @param array $columns
     * @return array
     */
    public function filter(Board $board, array $columns)
    {
        $currentPlayer = $board->getNextPlayer();
        if (count($columns) < 2) {
            return $columns;
        }
        $possibleMoves = [];
        foreach ($columns as $column) {
            
            $newBoard = $board->applyMove(new \Connect4\Lib\Move($currentPlayer, $column));
            $opportunism = new Opportunism();
            $validColumns = [];
            
            foreach (range(Column::MIN, Column::MAX) as $columnNumber) {
                $_column = new Column($columnNumber);
                if (! $newBoard->isColumnFull($_column)) {
                    $validColumns[] = $_column;
                }
            }
            
            $filtered = $opportunism->filter($newBoard, $validColumns);
            if (count($filtered) > 1) {
                $possibleMoves[] = $column;
            }
        }
        return $possibleMoves;
    }
}

