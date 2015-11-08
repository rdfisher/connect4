<?php
include_once __DIR__ . '/../../vendor/autoload.php';

use Connect4\Server\Server;

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$server = new Server();

$socket->on('connection', function ($connection)  use ($server) {
    $name = '';
    $connection->on('data', function($data) use (&$name, $server, $connection) {
        if ($name) return;
        $name = trim($data);
        $server->addSocketConnection($connection, $name);
    });
    
});

$socket->listen(1337, '0.0.0.0');

//http server
$detailsSocket = new React\Socket\Server($loop);
$http = new React\Http\Server($detailsSocket);

$gameToArray = function(\Connect4\Server\GameArchive\ArchivedGame $game) {
    $board = $game->getBoard();
    $data = [
        'id' => $game->getId(),
        'board' => [],
        'redPlayer' => $game->getRedPlayerName(),
        'yellowPlayer' => $game->getYellowPlayerName(),
        'date' => $game->getDate()->format('Y-m-d H:i:s'),
        'transcript' => [],
        'state' => $board->getState()->getValue()
    ];
    
    foreach (range(\Connect4\Lib\Row::MIN, \Connect4\Lib\Row::MAX) as $rowNumber) {
        $rowContents = $board->getRowContents(new \Connect4\Lib\Row($rowNumber));
        $cells = [];
        foreach ($rowContents as $cell) {
            $cells[] = (string)$cell;
        }
        $data['board'][] = $cells;
    }
    
    foreach ($board->getTranscript() as $move) {
        $data['transcript'][] = [$move->getPlayer()->getValue(), $move->getColumn()->getValue()];
    }
    
    return $data;
};

$http->on('request', function ($request, $response) use ($server, $gameToArray) {
    $serve = function($content, $type, $code = 200) use ($response) {
        $headers = array('Content-Type' => $type);
        $response->writeHead($code, $headers);
        $response->end($content);
    };
    switch($request->getPath()) {
        case '/archive': 
            $query = $request->getQuery();
            $archive = $server->getGameArchive();
            if (isset($query['since'])) {
                $archivedGames = $archive->getArchiveSince($query['since']);
            } else {
                $archivedGames = $archive->getArchive();
            }
            $games = [];
            foreach ($archivedGames as $i => $game) {
                $games[] = $gameToArray($game, $i + 1);
            }
            $serve(json_encode($games), 'application/json');
            break;
        case '/':
            $serve(file_get_contents(__DIR__. '/../../web/index.html'), 'text/html');
            break;
        case '/connect4.js':
            $serve(file_get_contents(__DIR__. '/../../web/connect4.js'), 'application/javascript');
            break;
        case '/connect4.css':
            $serve(file_get_contents(__DIR__. '/../../web/connect4.css'), 'text/css');
            break;
        default:
            $serve('Not Found', 'text/html', 404);
            break;
    }
        
});
$detailsSocket->listen(8080);

$loop->run();