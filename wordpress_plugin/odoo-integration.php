<?php
/**
 * Plugin Name: Odoo Integration
 * Description: Integrates WordPress with Odoo Point of Sale
 * Version: 1.0
 * Author: Jurgen
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load configuration
require_once plugin_dir_path(__FILE__) . 'config/config.php';

// Load main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-odoo-integration.php';

// Load hooks
require_once plugin_dir_path(__FILE__) . 'includes/hooks.php';

function run_odoo_integration() {
    $plugin = new Odoo_Integration();
    $plugin->run();
}

run_odoo_integration();