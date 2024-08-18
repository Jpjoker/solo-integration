<?php
class Webhook_Handler {
    public function handle_odoo_webhook() {
        $data = json_decode(file_get_contents('php://input'), true);
        $rabbitmq_sender = new RabbitMQ_Sender();
        $rabbitmq_sender->send('odoo_to_wordpress', $data);
        wp_send_json_success();
    }
}

add_action('rest_api_init', function () {
    register_rest_route('odoo-integration/v1', '/webhook', array(
        'methods' => 'POST',
        'callback' => array(new Webhook_Handler(), 'handle_odoo_webhook'),
        'permission_callback' => '__return_true'
    ));
});