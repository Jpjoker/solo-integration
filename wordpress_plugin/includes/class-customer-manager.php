<?php
class Customer_Manager {
    public function get_customers() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_customers';
        return $wpdb->get_results("SELECT * FROM $table_name");
    }

    public function add_customer($customer_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_customers';
        $result = $wpdb->insert($table_name, $customer_data);
        if ($result) {
            $rabbitmq_sender = new RabbitMQ_Sender();
            $rabbitmq_sender->send('customer_to_odoo', array('action' => 'create', 'data' => $customer_data));
            return $wpdb->insert_id;
        }
        return false;
    }

    // Implement edit_customer and delete_customer methods
}