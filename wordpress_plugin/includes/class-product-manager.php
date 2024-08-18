<?php
class Product_Manager {
    public function get_products() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_products';
        return $wpdb->get_results("SELECT * FROM $table_name");
    }

    public function add_product($product_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_products';
        $result = $wpdb->insert($table_name, $product_data);
        if ($result) {
            $this->send_to_odoo('create', $product_data);
            return $wpdb->insert_id;
        }
        return false;
    }

    public function edit_product($product_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_products';
        $result = $wpdb->update($table_name, $product_data, array('id' => $product_data['id']));
        if ($result) {
            $this->send_to_odoo('update', $product_data);
            return true;
        }
        return false;
    }

    public function delete_product($product_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_products';
        $result = $wpdb->delete($table_name, array('id' => $product_id));
        if ($result) {
            $this->send_to_odoo('delete', array('id' => $product_id));
            return true;
        }
        return false;
    }

    private function send_to_odoo($action, $data) {
        $rabbitmq_sender = new RabbitMQ_Sender();
        $rabbitmq_sender->send('product_to_odoo', array(
            'action' => $action,
            'data' => $data
        ));
    }
}