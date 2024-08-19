<?php
class API_Handler {
    private $odoo_url;
    private $database;
    private $username;
    private $password;

    public function __construct() {
        $this->odoo_url = '192.168.56.103:8069';
        $this->database = 'odoo';
        $this->username = 'admin';
        $this->password = 'admin';
    }

    public function send_to_odoo($endpoint, $data) {
        $url = $this->odoo_url . '/jsonrpc';
        $headers = array('Content-Type: application/json');

        $payload = json_encode(array(
            'jsonrpc' => '2.0',
            'method' => 'call',
            'params' => array(
                'service' => 'object',
                'method' => 'execute_kw',
                'args' => array(
                    $this->database,
                    2,
                    $this->password,
                    $endpoint,
                    'create',
                    array($data)
                )
            )
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function get_from_odoo($endpoint, $domain = array()) {
        $url = $this->odoo_url . '/jsonrpc';
        $headers = array('Content-Type: application/json');

        $payload = json_encode(array(
            'jsonrpc' => '2.0',
            'method' => 'call',
            'params' => array(
                'service' => 'object',
                'method' => 'execute_kw',
                'args' => array(
                    $this->database,
                    2,
                    $this->password,
                    $endpoint,
                    'search_read',
                    array($domain)
                )
            )
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}