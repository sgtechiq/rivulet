<?php

namespace Rivulet\Mail\Drivers;

use Exception;

class MailgunDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $cc, $bcc, $from, $subject, $body, $isHtml, $attachments) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/{$this->config['domain']}/messages");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "api:{$this->config['api_key']}");
        curl_setopt($ch, CURLOPT_POST, 1);

        $postData = [
            'from' => "{$from['name']} <{$from['address']}>",
            'to' => implode(',', $to),
            'subject' => $subject,
        ];
        if ($cc) $postData['cc'] = implode(',', $cc);
        if ($bcc) $postData['bcc'] = implode(',', $bcc);
        if ($isHtml) {
            $postData['html'] = $body;
        } else {
            $postData['text'] = $body;
        }
        foreach ($attachments as $attach) {
            $postData['attachment'][] = new \CURLFile($attach['file'], mime_content_type($attach['file']), $attach['name']);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        if (isset($json['id'])) {
            return true;
        }
        throw new Exception("Mailgun error: " . ($json['message'] ?? 'Unknown'));
    }
}