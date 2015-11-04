<?php

namespace spec\Connect4\Lib;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Connect4\Lib\Player;
use Connect4\Lib\GameState;
use Connect4\Lib\Column;
use Connect4\Lib\Row;
use Connect4\Lib\Move;

class BoardSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Player(Player::RED));
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Lib\Board');
    }
    
    function it_returns_an_initial_state()
    {
        $this->getState()->shouldBeLike(new GameState(GameState::RED_PLAYS_NEXT));
    }
    
    function it_knows_the_next_player()
    {
        $this->getNextPlayer()->shouldBeLike(new Player(Player::RED));
    }
    
    function it_is_initially_empty()
    {
        foreach (range(1, 7) as $columnNumber) {
            $column = new Column($columnNumber);
            
            foreach (range(1, 6) as $rowNumber) {
                $row = new Row($rowNumber);
                
                $this->getContentsOfCell($column, $row)->shouldBe(null);
            }
        }
    }
    
    function it_can_get_the_contents_of_a_column()
    {
        $this->getColumnContents(new Column(4))->shouldBe([null, null, null, null, null, null]);
    }
    
    function it_rejects_a_move_for_the_wrong_player()
    {
        $column = new Column(4);
        $player = new Player(Player::YELLOW);
        $move = new Move($player, $column);
        
        $this->shouldThrow('Connect4\Lib\Exception\WrongPlayerException')->during('applyMove', [$move]);
    }
    
    function it_applies_two_moves_in_the_same_column()
    {
        $firstMove = $this->getMove(Player::RED, 4);
        $boardAfterMoveOne = $this->applyMove($firstMove);
        $boardAfterMoveOne->getContentsOfCell(new Column(4), new Row(1))->shouldBe($firstMove->getPlayer());
        
        $secondMove = $this->getMove(Player::YELLOW, 4);
        $boardAfterMoveTwo = $boardAfterMoveOne->applyMove($secondMove);
        $boardAfterMoveTwo->getContentsOfCell(new Column(4), new Row(1))->shouldBe($firstMove->getPlayer());
        $boardAfterMoveTwo->getContentsOfCell(new Column(4), new Row(2))->shouldBe($secondMove->getPlayer());
        $boardAfterMoveTwo->getContentsOfCell(new Column(3), new Row(1))->shouldBe(null);
        $boardAfterMoveTwo->getColumnContents(new Column(4))->shouldBe(array(
            $firstMove->getPlayer(),
            $secondMove->getPlayer(), 
            null, 
            null, 
            null,
            null
        ));
        $this->getTranscript()->shouldBe([]);
        $boardAfterMoveOne->getTranscript()->shouldBe([$firstMove]);
        $boardAfterMoveTwo->getTranscript()->shouldBe([$firstMove, $secondMove]);
    }
    
    function it_rejects_a_move_in_a_full_column()
    {
        $redMove = $this->getMove(Player::RED, 3);
        $yellowMove = $this->getMove(Player::YELLOW, 3);
        
        $board = $this->applyMove($redMove);
        $board = $board->applyMove($yellowMove);
        $board = $board->applyMove($redMove);
        $board = $board->applyMove($yellowMove);
        $board = $board->applyMove($redMove);
        $board = $board->applyMove($yellowMove);
        
        $board->shouldThrow('Connect4\Lib\Exception\ColumnFullException')->during('applyMove', [$redMove]);
    }
    
    function it_applies_two_moves_in_different_columns()
    {
        $firstMove = $this->getMove(Player::RED, 4);
        $boardAfterMoveOne = $this->applyMove($firstMove);
        $boardAfterMoveOne->getContentsOfCell(new Column(4), new Row(1))->shouldBe($firstMove->getPlayer());
        
        $secondMove = $this->getMove(Player::YELLOW, 3);
        $boardAfterMoveTwo = $boardAfterMoveOne->applyMove($secondMove);
        $boardAfterMoveTwo->getContentsOfCell(new Column(4), new Row(1))->shouldBe($firstMove->getPlayer());
        $boardAfterMoveTwo->getContentsOfCell(new Column(4), new Row(2))->shouldBe(null);
        $boardAfterMoveTwo->getContentsOfCell(new Column(3), new Row(1))->shouldBe($secondMove->getPlayer());
        $boardAfterMoveTwo->getRowContents(new Row(1))->shouldBe(array(
            null, 
            null, 
            $secondMove->getPlayer(), 
            $firstMove->getPlayer(),
            null,
            null,
            null
        ));
    }
    
    function it_can_detect_a_vertical_win()
    {
        $redMove = $this->getMove(Player::RED, 1);
        $yellowMove = $this->getMove(Player::YELLOW, 2);
        
        $board = $this->applyMove($redMove);
        
        for ($i=  0; $i<3; $i++) {
            $board = $board->applyMove($yellowMove);
            $board = $board->applyMove($redMove);
        }
        $board->getState()->shouldBeLike(new GameState(GameState::RED_WON));
    }
    
    function it_can_detect_a_horizontal_win()
    {
        $yellowMove = $this->getMove(Player::YELLOW, 1);
        
        $board = $this->applyMove($this->getMove(Player::RED, 1));
        
        for ($i=  0; $i<3; $i++) {
            $board = $board->applyMove($yellowMove);
            $board = $board->applyMove($this->getMove(Player::RED, $i+2));
        }
        $board->getState()->shouldBeLike(new GameState(GameState::RED_WON));
    }
    
    function it_can_detect_a_forward_diagonal_win()
    {
        /**
         * .......
         * .......
         * ...R...
         * ..RR...
         * .RYY...
         * RYYRY..
         */
        $moves = [
            1, 2, 
            2, 3,
            4, 3,
            3, 4,
            4, 5,
            4
        ];
        
        $board = $this;
        
        foreach ($moves as $i => $columnNumber) {
            $player = $i % 2 ? new Player(Player::YELLOW) : new Player(Player::RED);
            $move = new Move($player, new Column($columnNumber));
            $board = $board->applyMove($move);
        }
        
        $board->getState()->shouldBeLike(new GameState(GameState::RED_WON));
    }
    
    function it_can_detect_a_backward_diagonal_win()
    {
        /**
         * .......
         * .......
         * ..R....
         * ..RR...
         * ..YYR..
         * .YROOR.
         */
        $moves = [
            6, 5, 
            5, 4,
            3, 4,
            4, 3,
            3, 2,
            3
        ];
        
        $board = $this;
        
        foreach ($moves as $i => $columnNumber) {
            $player = $i % 2 ? new Player(Player::YELLOW) : new Player(Player::RED);
            $move = new Move($player, new Column($columnNumber));
            $board = $board->applyMove($move);
        }
        
        $board->getState()->shouldBeLike(new GameState(GameState::RED_WON));
    }
    
    /**
     * @param string $playerColour
     * @param integer $columnNumber
     * @return Move
     */
    private function getMove($playerColour, $columnNumber)
    {
        return new Move(new Player($playerColour), new Column($columnNumber));
    }
}
