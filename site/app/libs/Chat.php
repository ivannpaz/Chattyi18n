<?php

namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Application;

class Chat implements MessageComponentInterface
{

    protected $clients;

    protected $app;

    public function __construct(Application $app)
    {
        $this->clients = new \SplObjectSTorage();
        $this->app = $app;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $packet)
    {
        $numRecv = count($this->clients) - 1;

        $msg = json_decode($packet);

        // LOG
        echo sprintf(
            'Connection %d sending message "%s" in "%s" to %d other connection%s in "%s"' . "\n",
            $from->resourceId,
            $msg->text,
            $msg->src,
            $numRecv,
            $numRecv == 1 ? '' : 's',
            $msg->dst
        );

        // Translate if src & dst differs
        if ($msg->src != $msg->dst) {
            echo "Translating: '{$msg->text}' -> ";
            $text = $this->app['microsoft_translator']->translate(
                $msg->text,
                $msg->dst,
                $msg->src
            );
            echo " '{$text}'\n";
        } else {
            echo "No translation needed...\n";
            $text = $msg->text;
        }

        $response = [
            'text' => $this->app->escape($text),
            'original' => $this->app->escape($msg->text),
        ];

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $response['type'] = 'rcv';
            } else {
                $response['type'] = 'ack';
            }

            $client->send(json_encode($response));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
