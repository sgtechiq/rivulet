<?php

namespace Rivulet\Mail\Drivers;

use Exception;

class SendGridDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $cc, $bcc, $from, $subject, $body, $isHtml, $attachments) {
        $payload = [
            'personalizations' => [
                ['to' => array_map(fn($email) => ['email' => $email], $to)]
            ],
            'from' => ['email' => $from['address'], 'name' => $from['name']],
            'subject' => $subject,
            'content' => [[ 'type' => $isHtml ? 'text/html' : 'text/plain', 'value' => $body ]],
        ];
        if ($cc) $payload['personalizations'][0]['cc'] = array_map(fn($email) => ['email' => $email], $cc);
        if ($bcc) $payload['personalizations'][0]['bcc'] = array_map(fn($email) => ['email' => $email], $bcc);
        if ($attachments) {
            $payload['attachments'] = [];
            foreach ($attachments as $attach) {
                $content = base64_encode(file_get_contents($attach['file']));
                $payload['attachments'][] = [
                    'content' => $content,
                    'type' => mime_content_type($attach['file']),
                    'filename' => $attach['name'],
                    'disposition' => 'attachment',
                ];
            }
        }

        $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->config['api_key']}",
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 202) {
            return true;
        }
        throw new Exception("SendGrid error: {$response}");
    }
}