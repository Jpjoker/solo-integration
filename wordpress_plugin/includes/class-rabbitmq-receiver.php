<?php
class RabbitMQ_Receiver {
    private $connection;
    private $channel;

    public function __construct() {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('wp_odoo_queue', false, true, false, false);
    }

    public function get_customers_from_odoo() {
        $customers = array();
        $callback = function($msg) use (&$customers) {
            $data = json_decode($msg->body, true);
            if ($data['action'] === 'customers_list') {
                $customers = $data['data'];
            }
            $msg->ack();
        };

        $this->channel->basic_consume('wp_odoo_queue', '', false, false, false, false, $callback);

        while (count($customers) === 0) {
            $this->channel->wait();
        }

        return $customers;
    }

    public function __destruct() {
        $this->channel->close();
        $this->connection->close();
    }
}