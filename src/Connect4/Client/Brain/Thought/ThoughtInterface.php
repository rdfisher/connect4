<?php
namespace Connect4\Client\Brain\Thought;

use Connect4\Lib\Board;

interface ThoughtInterface
{
    /**
     * @param array $columns
     * @return array
     */
    public function filter(Board $board, array $columns);
}
