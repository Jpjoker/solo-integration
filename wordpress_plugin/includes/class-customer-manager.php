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
            $this->send_to_odoo('create', $customer_data);
            return $wpdb->insert_id;
        }
        return false;
    }

    public function edit_customer($customer_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_customers';
        $result = $wpdb->update($table_name, $customer_data, array('id' => $customer_data['id']));
        if ($result) {
            $this->send_to_odoo('update', $customer_data);
            return true;
        }
        return false;
    }

    public function delete_customer($customer_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_customers';
        $result = $wpdb->delete($table_name, array('id' => $customer_id));
        if ($result) {
            $this->send_to_odoo('delete', array('id' => $customer_id));
            return true;
        }
        return false;
    }

    private function send_to_odoo($action, $data) {
        $rabbitmq_sender = new RabbitMQ_Sender();
        $rabbitmq_sender->send('customer_to_odoo', array(
            'action' => $action,
            'data' => $data
        ));
    }
}