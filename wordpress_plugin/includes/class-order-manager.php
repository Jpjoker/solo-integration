<?php
class Order_Manager {
    public function create_order($order_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_orders';
        $result = $wpdb->insert($table_name, $order_data);
        if ($result) {
            $this->send_to_odoo('create', $order_data);
            return $wpdb->insert_id;
        }
        return false;
    }

    public function get_orders() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'odoo_orders';
        return $wpdb->get_results("SELECT * FROM $table_name");
    }

    private function send_to_odoo($action, $data) {
        $rabbitmq_sender = new RabbitMQ_Sender();
        $rabbitmq_sender->send('order_to_odoo', array(
            'action' => $action,
            'data' => $data
        ));
    }
}