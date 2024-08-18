<?php
class Odoo_Integration {
    public function run() {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-customer-manager.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-product-manager.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-order-manager.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-rabbitmq-sender.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-rabbitmq-receiver.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-webhook-handler.php';
    }

    private function define_admin_hooks() {
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    private function define_public_hooks() {
        add_action('wp_ajax_get_customers', array($this, 'get_customers'));
        add_action('wp_ajax_add_customer', array($this, 'add_customer'));
        add_action('wp_ajax_edit_customer', array($this, 'edit_customer'));
        add_action('wp_ajax_delete_customer', array($this, 'delete_customer'));
        // Add similar hooks for products and orders
    }

    public function add_plugin_admin_menu() {
        add_menu_page('Odoo Integration', 'Odoo Integration', 'manage_options', 'odoo-integration', array($this, 'display_plugin_admin_page'), 'dashicons-store', 6);
    }

    public function display_plugin_admin_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'templates/admin-page.php';
    }

    public function enqueue_styles() {
        wp_enqueue_style('odoo-integration', plugin_dir_url(dirname(__FILE__)) . 'assets/css/style.css', array(), '1.0.0', 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('odoo-integration', plugin_dir_url(dirname(__FILE__)) . 'assets/js/script.js', array('jquery'), '1.0.0', false);
        wp_localize_script('odoo-integration', 'odoo_integration_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    // Implement AJAX callback functions (get_customers, add_customer, etc.)
}