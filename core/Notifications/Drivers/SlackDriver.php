<?php

namespace Rivulet\Notifications\Drivers;

use Exception;

class SlackDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $title, $body, $data) {
        $message = "*{$title}*\n{$body}";
        if ($data) $message .= "\n" . json_encode($data);

        $payload = ['text' => $message];

        $ch = curl_init($this->config['webhook']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response !== 'ok') {
            throw new Exception("Slack error: {$response}");
        }
        return true;
    }
}