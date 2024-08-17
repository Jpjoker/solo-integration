<?php
class Customer_Manager {
    private $rabbitmq_sender;
    private $rabbitmq_receiver;

    public function __construct() {
        $this->rabbitmq_sender = new RabbitMQ_Sender();
        $this->rabbitmq_receiver = new RabbitMQ_Receiver();

        add_action('wp_ajax_add_customer', array($this, 'add_customer'));
        add_action('wp_ajax_edit_customer', array($this, 'edit_customer'));
        add_action('wp_ajax_delete_customer', array($this, 'delete_customer'));
        add_action('wp_ajax_get_customers', array($this, 'get_customers'));
    }

    public function add_customer() {
        // Implement customer addition logic
        $customer_data = $_POST['customer_data'];
        // Validate and sanitize data
        $this->rabbitmq_sender->send_customer_data('create', $customer_data);
        wp_send_json_success('Customer added successfully');
    }

    public function edit_customer() {
        // Implement customer editing logic
        $customer_data = $_POST['customer_data'];
        // Validate and sanitize data
        $this->rabbitmq_sender->send_customer_data('update', $customer_data);
        wp_send_json_success('Customer updated successfully');
    }

    public function delete_customer() {
        // Implement customer deletion logic
        $customer_id = $_POST['customer_id'];
        $this->rabbitmq_sender->send_customer_data('delete', array('id' => $customer_id));
        wp_send_json_success('Customer deleted successfully');
    }

    public function get_customers() {
        // Implement logic to fetch customers from Odoo
        $customers = $this->rabbitmq_receiver->get_customers_from_odoo();
        wp_send_json_success($customers);
    }
}