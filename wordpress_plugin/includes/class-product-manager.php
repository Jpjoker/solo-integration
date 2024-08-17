<?php
class Product_Manager {
    private $rabbitmq_sender;

    public function __construct() {
        $this->rabbitmq_sender = new RabbitMQ_Sender();

        add_action('wp_ajax_add_product', array($this, 'add_product'));
        add_action('wp_ajax_edit_product', array($this, 'edit_product'));
        add_action('wp_ajax_delete_product', array($this, 'delete_product'));
        add_action('wp_ajax_get_products', array($this, 'get_products'));
    }

    public function add_product() {
        $product_data = $_POST['product_data'];
        // Validate and sanitize data
        $this->rabbitmq_sender->send_product_data('create', $product_data);
        wp_send_json_success('Product added successfully');
    }

    public function edit_product() {
        $product_data = $_POST['product_data'];
        // Validate and sanitize data
        $this->rabbitmq_sender->send_product_data('update', $product_data);
        wp_send_json_success('Product updated successfully');
    }

    public function delete_product() {
        $product_id = $_POST['product_id'];
        $this->rabbitmq_sender->send_product_data('delete', array('id' => $product_id));
        wp_send_json_success('Product deleted successfully');
    }

    public function get_products() {
        // Implement logic to fetch products from Odoo
        $products = $this->rabbitmq_sender->get_products_from_odoo();
        wp_send_json_success($products);
    }
}