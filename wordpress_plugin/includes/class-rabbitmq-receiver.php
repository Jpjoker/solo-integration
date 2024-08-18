<?php
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(WP_PLUGIN_DIR . '/odoo-integration/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ_Receiver {
    public function receive($queue) {
        $connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $this->process_message($data);
            $msg->ack();
        };

        $channel->basic_consume($queue, '', false, false, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    private function process_message($data) {
        // Process the message based on its type and action
    }
}