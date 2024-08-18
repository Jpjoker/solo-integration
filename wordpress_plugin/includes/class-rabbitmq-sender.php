<?php
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(WP_PLUGIN_DIR . '/odoo-integration/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ_Sender {
    public function send($queue, $data) {
        $connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage(json_encode($data));
        $channel->basic_publish($msg, '', $queue);

        $channel->close();
        $connection->close();
    }
}