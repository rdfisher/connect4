<?php
namespace Connect4\Client\Brain\Thought;

use Connect4\Lib\Board;

class Opportunism implements ThoughtInterface
{
    /**
     * If there is a winning move, take it
     * 
     * @param Board $board
     * @param array $columns
     * @return array
     */
    public function filter(Board $board, array $columns)
    {
        $currentPlayer = $board->getNextPlayer();
        if (! $currentPlayer) {
            return $columns;
        }
        if ($currentPlayer->getValue() == \Connect4\Lib\Player::RED) {
            $winState = new \Connect4\Lib\GameState(\Connect4\Lib\GameState::RED_WON);
        } else {
            $winState = new \Connect4\Lib\GameState(\Connect4\Lib\GameState::YELLOW_WON);
        }
        foreach ($columns as $column) {
            $newBoard = $board->applyMove(new \Connect4\Lib\Move($currentPlayer, $column));
            if ($newBoard->getState() == $winState) {
                return [$column];
            }
        }
        
        return $columns;
    }

}

