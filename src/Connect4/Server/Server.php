<?php
namespace Connect4\Server;

use Connect4\Lib\Player;
use Connect4\Lib\Board;
use Connect4\Lib\Column;
use Connect4\Lib\Move;
use React\Socket\Connection as SocketConnection;

class Server
{
    /**
     * @var Connection[]
     */
    private $connections = [];
    
    /**
     * @var Player[]
     */
    private $availablePlayers;
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * @var Board
     */
    private $board;
    
    public function __construct(LoggerInterface $logger = null)
    {
       $this->logger = $logger ?: new Logger();
       $this->reset();
    }
    
    /**
     * @return Connection[]
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * @param SocketConnection $socketConnection
     * param string $name
     * @return Connection|null
     */
    public function addSocketConnection(SocketConnection $socketConnection, $name)
    {
        $player = array_pop($this->availablePlayers);
        
        if (! $player) {
            $socketConnection->close();
            return;
        }
        
        $connection = new Connection($socketConnection, $player);
        $connection->setName($name);
        $this->connections[] = $connection;
        if ($this->isReady()) {
            $this->run();
        }
        $this->logger->log("CONNECTION FROM " . $socketConnection->getRemoteAddress() . " AS " . $player . " (" . $name . ")");
        return $connection;
    }

    /**
     * @return boolean
     */
    public function isReady()
    {
        return count($this->connections) == 2;
    }
    
    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }
    
    /**
     * @param Board $board
     */
    public function setBoard(Board $board)
    {
        $this->board = $board;
    }

    public function run()
    {
        $startingPlayerConnection = $this->randomlyChooseAConnection();
        $this->setBoard(new Board($startingPlayerConnection->getPlayer()));
        $connections = $this->getConnections();
        $server = $this;
        
        $notify = function() use ($connections, $server) {
            $transcript = array();
        
            foreach ($server->getBoard()->getTranscript() as $move) {
                $transcript[] = array((string)$move->getPlayer(), $move->getColumn()->getValue());
            }

            foreach ($server->getConnections() as $connection) {
                $socket = $connection->getSocketConnection();
                $board = $server->getBoard();
                $player = $connection->getPlayer();
                $data = [
                    'startPlayer' => (string)$board->getFirstPlayer(),
                    'state' => (string)$board->getState(),
                    'you' => (string)$player,
                    'transcript' => $transcript
                ];
                $socket->write(json_encode($data));
            }
        };
        
        $stopGame = function($errorMessage = null) use ($connections, $server) {
            $server->reset();
        };
        
        foreach ($connections as $connection) {
            $socket = $connection->getSocketConnection();
            $player = $connection->getPlayer();
            
            $socket->on('data', function($data) use ($socket, $player, $server, $notify, $stopGame) {
                $board= $server->getBoard();
                try {
                    $column = new Column((int)trim($data));
                    $move = new Move($player, $column);
                    $this->logger->log(strtoupper($player) . ": " . $column->getValue());
                    $board = $board->applyMove($move);
                    $server->setBoard($board);
                    $notify();
                    if (! $board->getNextPlayer()) {
                        $this->logger->log("GAME ENDED WITH STATE: " . $this->board->getState());
                        $stopGame();
                    }
                } catch (\Exception $ex) {
                    $this->logger->log("EXCEPTION WHILST PROCESSING MOVE FROM $player: " . get_class($ex) . ' ' . $ex->getMessage());
                    $stopGame();
                }
            });
        }
        
        $notify();
    }
    
    public function reset()
    {
        $this->logger->log("NEW GAME");
        foreach ($this->connections as $connection) {
            $connection->getSocketConnection()->close();
        }
        $this->connections = [];
        $this->availablePlayers = [
           new Player(Player::RED),
           new Player(Player::YELLOW)
       ];
        $this->board = null;
    }
    
    /**
     * @return Connection
     */
    private function randomlyChooseAConnection()
    {
        $connections = $this->connections;
        shuffle($connections);
        return current($connections);
    }
    
    /**
     * @param Player $player
     * @return array
     */
    private function getBoardInfo(Player $player)
    {
        $transcript = array();
        
        foreach ($this->board->getTranscript() as $move) {
            $transcript[] = array((string)$move->getPlayer(), $move->getColumn()->getValue());
        }
        
        return [
            'startPlayer' => (string)$this->board->getFirstPlayer(),
            'state' => (string)$this->board->getState(),
            'you' => (string)$player,
            'transcript' => $transcript
        ];
    }
}
