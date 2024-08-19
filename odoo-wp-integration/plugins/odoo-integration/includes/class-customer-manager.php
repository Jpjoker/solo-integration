<?php
class Customer_Manager {
    private $api_handler;

    public function __construct() {
        $this->api_handler = new API_Handler();
    }

    public function get_customers() {
        return $this->api_handler->get_from_odoo('res.partner');
    }

    public function add_customer($customer_data) {
        return $this->api_handler->send_to_odoo('res.partner', $customer_data);
    }

    public function update_customer($customer_id, $customer_data) {
        // Implementeer update logica
    }

    public function delete_customer($customer_id) {
        // Implementeer delete logica
    }
}