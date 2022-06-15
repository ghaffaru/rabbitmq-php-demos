<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once './vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost',5672, 'guest', 'guest');

$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($message) {
    echo '[x] Received ' ,  $message->body, '\n';
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}