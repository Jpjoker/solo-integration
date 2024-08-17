<?php
class RabbitMQ_Sender {
    private $connection;
    private $channel;

    public function __construct() {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('odoo_wp_queue', false, true, false, false);
    }

    public function send_customer_data($action, $data) {
        $message = json_encode(array(
            'action' => $action,
            'data' => $data
        ));
        $this->channel->basic_publish(new AMQPMessage($message), '', 'odoo_wp_queue');
    }

    public function __destruct() {
        $this->channel->close();
        $this->connection->close();
    }
}