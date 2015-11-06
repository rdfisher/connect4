<?php
namespace Connect4\Client;

use Connect4\Client\Brain\BrainInterface as Brain;
use Connect4\Client\Response;
use Connect4\Lib\Move;

class Client
{
    /**
     * @var Brain
     */
    private $brain;
    
    /**
     * @param Brain $brain
     */
    public function __construct(Brain $brain)
    {
        $this->brain = $brain;
    }

    /**
     * @param Response $response
     * @return Move|null
     */
    public function handle(Response $response)
    {
        $board = $response->getBoard();
        $identity = $response->getPlayer();
        $this->brain->setIdentity($identity);
        if ($board->getNextPlayer() == $identity) {
            $column  = $this->brain->getColumn($board);
            return new Move($identity, $column);
        }
    }
}
