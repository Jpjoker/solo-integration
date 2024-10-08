<?php
/**
 * Plugin Name: Odoo Integration
 * Description: Integrates WordPress with Odoo using RabbitMQ
 * Version: 1.0
 * Author: jurgen
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-odoo-integration.php';

function run_odoo_integration() {
    $plugin = new Odoo_Integration();
    $plugin->run();
}

run_odoo_integration();