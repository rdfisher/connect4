<?php

namespace Connect4\Client;

use Connect4\Lib\Player;
use Connect4\Lib\Board;
use Connect4\Lib\Move;
use Connect4\Lib\Column;

class ResponseInterpreter
{
    public function buildResponse($data)
    {
        $data = json_decode($data, true);
        $startPlayer = new Player($data['startPlayer']);
        $board = new Board($startPlayer);
        $thisPlayer = new Player($data['you']);
        
        foreach ($data['transcript'] as $moveData) {
            $move = new Move(new Player($moveData[0]), new Column($moveData[1]));
            $board = $board->applyMove($move);
        }
        
        $response = new Response($board, $thisPlayer);
        return $response;
    }
}
