<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ_Sender {
    public function send_message($queue, $data) {
        $connection = new AMQPStreamConnection('192.168.56.103', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage(json_encode($data));
        $channel->basic_publish($msg, '', $queue);

        $channel->close();
        $connection->close();
    }
}