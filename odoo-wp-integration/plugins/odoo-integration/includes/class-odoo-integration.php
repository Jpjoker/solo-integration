<?php

class Odoo_Integration {
    private $customer_manager;
    private $product_manager;
    private $order_manager;
    private $rabbitmq_sender;
    private $rabbitmq_receiver;
    private $api_handler;

    public function __construct() {
        $this->load_dependencies();
        $this->setup_actions();
        add_action('wp_ajax_get_customers', array($this, 'ajax_get_customers'));
        add_action('wp_ajax_save_customer', array($this, 'ajax_save_customer'));
    }

    public function ajax_get_customers() {
        $customers = $this->customer_manager->get_customers();
        wp_send_json_success($customers);
    }

    public function ajax_save_customer() {
        $customer_data = $_POST['customer_data'];
        parse_str($customer_data, $parsed_data);
        $result = $this->customer_manager->add_customer($parsed_data);
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
    private function load_dependencies() {
        require_once plugin_dir_path(__FILE__) . 'class-customer-manager.php';
        require_once plugin_dir_path(__FILE__) . 'class-product-manager.php';
        require_once plugin_dir_path(__FILE__) . 'class-order-manager.php';
        require_once plugin_dir_path(__FILE__) . 'class-rabbitmq-sender.php';
        require_once plugin_dir_path(__FILE__) . 'class-rabbitmq-receiver.php';
        require_once plugin_dir_path(__FILE__) . 'class-api-handler.php';

        $this->customer_manager = new Customer_Manager();
        $this->product_manager = new Product_Manager();
        $this->order_manager = new Order_Manager();
        $this->rabbitmq_sender = new RabbitMQ_Sender();
        $this->rabbitmq_receiver = new RabbitMQ_Receiver();
        $this->api_handler = new API_Handler();
    }

    private function setup_actions() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function run() {
        $this->rabbitmq_receiver->start_listening();
    }

    public function add_admin_menu() {
        add_menu_page('Odoo Integration', 'Odoo Integration', 'manage_options', 'odoo-integration', array($this, 'display_admin_page'), 'dashicons-networking');
    }

    public function display_admin_page() {
        include plugin_dir_path(__FILE__) . '../templates/customer-list.php';
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_style('odoo-integration-admin', plugin_dir_url(__FILE__) . '../assets/css/odoo-integration-admin.css');
        wp_enqueue_script('odoo-integration-admin', plugin_dir_url(__FILE__) . '../assets/js/odoo-integration-admin.js', array('jquery'), '1.0', true);
    }


}