<?php
class Order_Manager {
    private $rabbitmq_sender;

    public function __construct() {
        $this->rabbitmq_sender = new RabbitMQ_Sender();

        add_action('wp_ajax_place_order', array($this, 'place_order'));
        add_action('wp_ajax_get_orders', array($this, 'get_orders'));
    }

    public function place_order() {
        $order_data = $_POST['order_data'];
        // Validate and sanitize data
        $this->rabbitmq_sender->send_order_data('create', $order_data);
        wp_send_json_success('Order placed successfully');
    }

    public function get_orders() {
        // Implement logic to fetch orders from Odoo
        $orders = $this->rabbitmq_sender->get_orders_from_odoo();
        wp_send_json_success($orders);
    }
}