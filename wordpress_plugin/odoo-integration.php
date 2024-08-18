<?php
/**
 * Plugin Name: Odoo PoS Integration
 * Description: Integrates WordPress with Odoo Point of Sale
 * Version: 1.0
 * Author: Your Name
 */

require_once plugin_dir_path(__FILE__) . 'includes/class-customer-manager.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-product-manager.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-order-manager.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-rabbitmq-receiver.php';

class Odoo_Integration {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_get_customers', array($this, 'get_customers'));
        add_action('wp_ajax_add_customer', array($this, 'add_customer'));
        add_action('wp_ajax_edit_customer', array($this, 'edit_customer'));
        add_action('wp_ajax_delete_customer', array($this, 'delete_customer'));
        add_action('wp_ajax_get_products', array($this, 'get_products'));
        add_action('wp_ajax_add_product', array($this, 'add_product'));
        add_action('wp_ajax_edit_product', array($this, 'edit_product'));
        add_action('wp_ajax_delete_product', array($this, 'delete_product'));
        add_action('wp_ajax_create_order', array($this, 'create_order'));

        $rabbitmq_receiver = new RabbitMQ_Receiver();
        $rabbitmq_receiver->start_consuming();
    }

    public function add_admin_menu() {
        add_menu_page('Odoo Integration', 'Odoo Integration', 'manage_options', 'odoo-integration', array($this, 'display_admin_page'), 'dashicons-store', 6);
    }

    public function display_admin_page() {
        require_once plugin_dir_path(__FILE__) . 'templates/admin-page.php';
    }

    // Customer management
    public function get_customers() {
        $customer_manager = new Customer_Manager();
        wp_send_json($customer_manager->get_customers());
    }

    public function add_customer() {
        $customer_manager = new Customer_Manager();
        $result = $customer_manager->add_customer($_POST['customer']);
        wp_send_json($result);
    }

    public function edit_customer() {
        $customer_manager = new Customer_Manager();
        $result = $customer_manager->edit_customer($_POST['customer']);
        wp_send_json($result);
    }

    public function delete_customer() {
        $customer_manager = new Customer_Manager();
        $result = $customer_manager->delete_customer($_POST['customer_id']);
        wp_send_json($result);
    }

    // Product management
    public function get_products() {
        $product_manager = new Product_Manager();
        wp_send_json($product_manager->get_products());
    }

    public function add_product() {
        $product_manager = new Product_Manager();
        $result = $product_manager->add_product($_POST['product']);
        wp_send_json($result);
    }

    public function edit_product() {
        $product_manager = new Product_Manager();
        $result = $product_manager->edit_product($_POST['product']);
        wp_send_json($result);
    }

    public function delete_product() {
        $product_manager = new Product_Manager();
        $result = $product_manager->delete_product($_POST['product_id']);
        wp_send_json($result);
    }

    // Order management
    public function create_order() {
        $order_manager = new Order_Manager();
        $result = $order_manager->create_order($_POST['order']);
        wp_send_json($result);
    }
}

new Odoo_Integration();