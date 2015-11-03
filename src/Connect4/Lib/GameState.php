<?php
namespace Connect4\Lib;

use Connect4\Lib\Value\Enum;

class GameState extends Enum
{
    const RED_PLAYS_NEXT  = 'red_plays_next';
    const YELLOW_PLAYS_NEXT  = 'yellow_plays_next';
    const RED_WON = 'red_won';
    const YELLOW_WON = 'yellow_won';
    const DRAW = 'draw';
}


