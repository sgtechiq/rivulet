<?php

namespace Rivulet\Notifications\Drivers;

use Exception;

class SmsDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $title, $body, $data) {
        $message = $title ? "{$title}\n{$body}" : $body;

        $postData = http_build_query([
            'From' => $this->config['from'],
            'To' => $to,
            'Body' => $message,
        ]);

        $ch = curl_init("https://api.twilio.com/2010-04-01/Accounts/{$this->config['account_sid']}/Messages.json");
        curl_setopt($ch, CURLOPT_USERPWD, $this->config['account_sid'] . ':' . $this->config['auth_token']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($response, true);
        if ($httpCode != 201 || isset($json['status']) && $json['status'] != 'queued') {
            throw new Exception("SMS error: " . ($json['message'] ?? $response));
        }
        return true;
    }
}