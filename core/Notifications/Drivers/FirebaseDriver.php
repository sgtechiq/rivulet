<?php

namespace Rivulet\Notifications\Drivers;

use Exception;

class FirebaseDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $title, $body, $data) {
        $payload = [
            'to' => $to,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];

        $ch = curl_init('https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . $this->config['api_key'],
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            throw new Exception("Firebase error: {$response}");
        }
        return true;
    }
}