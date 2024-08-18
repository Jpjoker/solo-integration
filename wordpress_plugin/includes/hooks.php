<?php
// File: includes/hooks.php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('wp_insert_post', 'send_product_to_odoo', 10, 3);
function send_product_to_odoo($post_id, $post, $update) {
    if ($post->post_type == 'product') {
        $product_manager = new Product_Manager();
        $product_manager->send_to_odoo($post_id);
    }
}

// Voeg hier eventueel meer hooks toe