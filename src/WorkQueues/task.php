<?php

use PhpAmqpLib\Connection\AMQPStreamConnection as AMQPStreamConnectionAlias;
use PhpAmqpLib\Message\AMQPMessage;

require_once './vendor/autoload.php';

$connection = new AMQPStreamConnectionAlias('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

$data = implode(' ', array_slice($argv, 1));

if (empty($data)) {
    $data = "Hello World!";
}

$msg = new AMQPMessage(
    $data,
    [
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_NON_PERSISTENT
    ]
);

$channel->basic_publish($msg, '', 'task_queue');

echo ' [x] Sent ', $data, '\n';

$channel->close();
$connection->close();