<?php
namespace Connect4\Lib;

use Connect4\Lib\Player;
use Connect4\Lib\GameState;
use Connect4\Lib\Column;
use Connect4\Lib\Row;
use Connect4\Lib\Exception\WrongPlayerException;
use Connect4\Lib\Exception\ColumnFullException;

class Board

{
    /**
     * @var Player
     */
    private $firstPlayer;
    
    /**
     * @var GameState
     */
    private $state;

    /**
     * @var array
     */
    private $cells;
    
    /**
     * @var Move[]
     */
    private $transcript = [];
    
    /**
     * @param Player $firstPlayer
     */
    public function __construct(Player $firstPlayer)
    {
        $this->firstPlayer = $firstPlayer;
        if ($firstPlayer == Player::RED) {
            $this->state = new GameState(GameState::RED_PLAYS_NEXT);
        } else {
            $this->state = new GameState(GameState::YELLOW_PLAYS_NEXT);
        }
        
        $this->cells = [
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null]
        ];
    }
    
    /**
     * @return Player
     */
    public function getFirstPlayer()
    {
        return $this->firstPlayer;
    }
    
    /**
     * @return GameState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * 
     * @return Player|null
     */
    public function getNextPlayer()
    {
        if ($this->state == GameState::RED_PLAYS_NEXT) {
            return new Player(Player::RED);
        }
        
        if ($this->state == GameState::YELLOW_PLAYS_NEXT) {
            return new Player(Player::YELLOW);
        }
        
        return null;
    }
    
    /**
     * @param Column $column
     * @param Row $row
     * @return Player|null
     */
    public function getContentsOfCell(Column $column, Row $row)
    {
        $rowIndex = $row->getValue() - 1;
        $columnIndex = $column->getValue() - 1;
        return $this->cells[$rowIndex][$columnIndex];
    }

    /**
     * @param Move $move
     * @throws WrongPlayerException
     */
    public function applyMove(Move $move)
    {
        $column = $move->getColumn();
        $player = $move->getPlayer();
        
        if ($player != $this->getNextPlayer()) {
            throw new WrongPlayerException();
        }
        
        if ($this->isColumnFull($column)) {
            throw new ColumnFullException();
        }
        
        $contents = $this->getColumnContents($column);
        $filledCells = array_filter($contents);
        
        $board = clone($this);
        $board->transcript[] = $move;
        $board->cells[count($filledCells)][$column->getValue() - 1] = $player;
        
        if ($board->isBoardFull()) {
            $board->state = new GameState(GameState::DRAW);
            return $board;
        }
        
        $winningPlayer = $board->computeWinner();
        
        if ($winningPlayer == Player::RED) {
            $board->state = new GameState(GameState::RED_WON);
            return $board;
        } 
        
        if ($winningPlayer == Player::YELLOW) {
            $board->state = new GameState(GameState::YELLOW_WON);
            return $board;
        }
        
        if ($player == Player::RED) {
            $board->state = new GameState(GameState::YELLOW_PLAYS_NEXT);
        } else {
            $board->state = new GameState(GameState::RED_PLAYS_NEXT);
        }
        
        return $board;
    }

    /**
     * @param Column $column
     * @return array
     */
    public function getColumnContents(Column $column)
    {
        $contents = [];
        foreach (range(Row::MIN, Row::MAX) as $rowNumber) {
            $row = new Row($rowNumber);
            $contents[] = $this->getContentsOfCell($column, $row);
        }
        return $contents;
    }
    
    /**
     * @param Column $column
     * @return array
     */
    public function getRowContents(Row $row)
    {
        $contents = [];
        foreach (range(Column::MIN, Column::MAX) as $columnNumber) {
            $column = new Column($columnNumber);
            $contents[] = $this->getContentsOfCell($column, $row);
        }
        return $contents;
    }
    
    /**
     * @param Column $column
     * @return boolean
     */
    public function isColumnFull(Column $column)
    {
        $contents = array_filter($this->getColumnContents($column));
        return count($contents) == Row::MAX;
    }
    
    /**
     * @param array $cells
     * @return Player|null
     */
    public function getFourInARow(array $cells)
    {
        $count = 0;
        $result = null;
        
        foreach ($cells as $cell) {
            if ($cell == $result) {
                $count ++;
                if (($count >= 4) && $result) {
                    return $result;
                }
            } else {
                $count = 1;
                $result = $cell;
            }
        }
        
        return null;
    }
    
    /**
     * @return Move[]
     */
    public function getTranscript()
    {
        return $this->transcript;
    }
    
    /**
     * @return boolean
     */
    public function isBoardFull()
    {
        $count = 0;
        foreach (range(Row::MIN, Row::MAX) as $rowNumber) {
            $contents = array_filter($this->getRowContents(new Row($rowNumber)));
            $count += count($contents);
        }
        return ($count == (Row::MAX * Column::MAX));
    }
    
    /**
     * Return the diagonal starting from the specified cell going up and right
     * 
     * @param Column $column
     * @param Row $row
     * @return array
     */
    public function getForwardDiagonalFromCell(Column $column, Row $row)
    {
        $x = $column->getValue();
        $y = $row->getValue();
        
        $set = [];
        while ($x <= Column::MAX && $y <= Row::MAX) {
            $set[] = $this->getContentsOfCell(new Column($x), new Row($y));
            $y ++;
            $x ++;
        }
        return $set;
    }
    
    /**
     * Return the diagonal starting from the specified cell going up and left
     * 
     * @param Column $column
     * @param Row $row
     * @return array
     */
    public function getBackwardDiagonalFromCell(Column $column, Row $row)
    {
        $x = $column->getValue();
        $y = $row->getValue();
        
        $set = [];
        while ($x >= Column::MIN && $y <= Row::MAX) {
            $set[] = $this->getContentsOfCell(new Column($x), new Row($y));
            $y ++;
            $x --;
        }
        
        return $set;
    }
    
    /**
     * @return Player|null
     */
    private function computeWinner()
    {
        if ($winner = $this->checkRows()) {
            return $winner;
        }
        
        if ($winner = $this->checkColumns()) {
            return $winner;
        }
        
        if ($winner = $this->checkDiagonals()) {
            return $winner;
        }
    }
    
    /**
     * @return Player|null
     */
    private function checkRows()
    {
        foreach (range(Row::MIN, Row::MAX) as $rowNumber) {
            $row = new Row($rowNumber);
            $set = $this->getRowContents($row);
            if ($winner = $this->getFourInARow($set)) {
                return $winner;
            }
        }
        
        return null;
    }
    
    /**
     * @return Player|null
     */
    private function checkColumns()
    {
        foreach (range(Column::MIN, Column::MAX) as $columnNumber) {
            $column = new Column($columnNumber);
            $set = $this->getColumnContents($column);

            if ($winner = $this->getFourInARow($set)) {
                return $winner;
            }
        }
        
        return null;
    }
    
    /**
     * @return Player|null
     */
    private function checkDiagonals()
    {
        $sets = [];
        foreach (range(Row::MIN, Row::MAX) as $rowNumber) {
            $row = new Row($rowNumber);
            foreach (range(Column::MIN, Column::MAX) as $columnNumber) {
                $column = new Column($columnNumber);
                if (($columnNumber == Column::MIN) || ($rowNumber == Row::MIN)) {
                    $sets[] = $this->getForwardDiagonalFromCell($column, $row);
                }
                if (($columnNumber == Column::MAX) || ($rowNumber == Row::MIN)) {
                    $sets[] = $this->getBackwardDiagonalFromCell($column, $row);
                }
            }
        }
        
        foreach ($sets as $set) {
            if ($winner = $this->getFourInARow($set)) {
                return $winner;
            }
        }
        
        return null;
    }
}
