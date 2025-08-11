<?php

namespace Rivulet\Notifications\Drivers;

use Exception;

class PusherDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $title, $body, $data) {
        $event = 'notification';
        $channel = $to; // Assume $to is channel name
        $message = array_merge(['title' => $title, 'body' => $body], $data);

        $query = http_build_query([
            'auth_key' => $this->config['app_key'],
            'auth_timestamp' => time(),
            'auth_version' => '1.0',
            'body_md5' => md5(json_encode($message)),
            'name' => $event,
        ]);

        $signatureString = "POST\n/apps/{$this->config['app_id']}/events\n{$query}";
        $signature = hash_hmac('sha256', $signatureString, $this->config['app_secret']);

        $query .= '&auth_signature=' . $signature;

        $ch = curl_init("https://api-{$this->config['cluster']}.pusher.com/apps/{$this->config['app_id']}/events?{$query}");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['channels' => [$channel], 'name' => $event, 'data' => $message]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 202) {
            throw new Exception("Pusher error: {$response}");
        }
        return true;
    }
}