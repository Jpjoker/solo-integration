<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ_Receiver {
    public function start_listening() {
        $connection = new AMQPStreamConnection('192.168.56.103', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('odoo_to_wp', false, true, false, false);

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            // Verwerk ontvangen data
        };

        $channel->basic_consume('odoo_to_wp', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}