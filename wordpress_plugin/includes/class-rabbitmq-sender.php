<?php
require_once(__DIR__ . '/../vendor/autoload.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ_Sender {
    private $connection;
    private $channel;

    public function __construct() {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('wordpress_to_odoo', false, true, false, false);
    }

    public function send($routing_key, $data) {
        $msg = new AMQPMessage(json_encode($data), array('delivery_mode' => 2));
        $this->channel->basic_publish($msg, '', $routing_key);
    }

    public function __destruct() {
        $this->channel->close();
        $this->connection->close();
    }
}