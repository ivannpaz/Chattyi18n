<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use App\Chat;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$app = new App\Application([
    'debug' => true,
]);


$server = IoServer::factory(
    new WsServer(
        new Chat($app)
    ),
    9090
);

echo "Starting server. Awaiting connections...\n";
$server->run();
