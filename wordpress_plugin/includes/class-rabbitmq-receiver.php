<?php
require_once(__DIR__ . '/../vendor/autoload.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ_Receiver {
    public function start_consuming() {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('pos_to_wordpress', false, true, false, false);
        $channel->queue_declare('product_to_wordpress', false, true, false, false);

        $callback = function($msg) {
            $data = json_decode($msg->body, true);
            if ($msg->delivery_info['routing_key'] === 'pos_to_wordpress') {
                $this->process_pos_order($data);
            } elseif ($msg->delivery_info['routing_key'] === 'product_to_wordpress') {
                $this->process_product($data);
            }
        };

        $channel->basic_consume('pos_to_wordpress', '', false, true, false, false, $callback);
        $channel->basic_consume('product_to_wordpress', '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    private function process_pos_order($data) {
        $order_manager = new Order_Manager();
        $order_manager->create_or_update_order($data);
    }

    private function process_product($data) {
        $product_manager = new Product_Manager();
        if ($data['action'] === 'create' || $data['action'] === 'update') {
            $product_manager->create_or_update_product($data);
        } elseif ($data['action'] === 'delete') {
            $product_manager->delete_product($data['id']);
        }
    }
}