<?php
namespace Connect4\Server;

use Connect4\Lib\Player;
use Connect4\Lib\Board;
use Connect4\Lib\Column;
use Connect4\Lib\Move;
use Connect4\Server\GameArchive\GameArchiveInterface as GameArchive;
use Connect4\Server\GameArchive\InMemoryArchive;
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
    
    /**
     * @var GameArchive
     */
    private $gameArchive;
    
    /**
     * @param LoggerInterface $logger
     * @param GameArchive $archive
     */
    public function __construct(LoggerInterface $logger = null, GameArchive $archive = null)
    {
       $this->logger = $logger ?: new Logger();
       $this->gameArchive = $archive ?: new InMemoryArchive();
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

    /**
     * @return GameArchive
     */
    public function getGameArchive()
    {
        return $this->gameArchive;
    }
    
    public function run()
    {
        $startingPlayerConnection = $this->randomlyChooseAConnection();
        $this->setBoard(new Board($startingPlayerConnection->getPlayer()));
        $connections = $this->getConnections();
        $server = $this;
        
        $notify = function() use ($connections, $server) {
            $transcript = array();
            $board = $server->getBoard();
            
            foreach ($board->getTranscript() as $move) {
                $transcript[] = array((string)$move->getPlayer(), $move->getColumn()->getValue());
            }

            foreach ($connections as $connection) {
                $socket = $connection->getSocketConnection();
                
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
        
        $stopGame = function() use ($server) {
            $board = $server->getBoard();
            $redPlayerName = '';
            $yellowPlayerName = '';
            
            foreach ($server->getConnections() as $connection) {
                if ($connection->getPlayer() == Player::RED) {
                    $redPlayerName = $connection->getName();
                }
                if ($connection->getPlayer() == Player::YELLOW) {
                    $yellowPlayerName = $connection->getName();
                }
            }
            
            $server->getGameArchive()->archive($board, $redPlayerName, $yellowPlayerName);
            $server->reset();
        };
        
        foreach ($connections as $connection) {
            $socket = $connection->getSocketConnection();
            $player = $connection->getPlayer();
            
            $socket->on('data', function($data) use ($player, $server, $notify, $stopGame) {
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
        shuffle($this->availablePlayers);
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
}
