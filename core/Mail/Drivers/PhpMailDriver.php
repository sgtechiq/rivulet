<?php

namespace Rivulet\Mail\Drivers;

use Exception;

class PhpMailDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $cc, $bcc, $from, $subject, $body, $isHtml, $attachments) {
        $headers = [
            "From: {$from['name']} <{$from['address']}>",
            "MIME-Version: 1.0",
        ];

        if ($cc) $headers[] = "Cc: " . implode(', ', $cc);
        if ($bcc) $headers[] = "Bcc: " . implode(', ', $bcc);

        if ($attachments || $isHtml) {
            $boundary = md5(time());
            $headers[] = "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";
            $message = "--{$boundary}\r\n";
            $message .= "Content-Type: " . ($isHtml ? 'text/html' : 'text/plain') . "; charset=UTF-8\r\n\r\n";
            $message .= $body . "\r\n";
            foreach ($attachments as $attach) {
                $encoded = chunk_split(base64_encode(file_get_contents($attach['file'])));
                $message .= "--{$boundary}\r\n";
                $message .= "Content-Type: application/octet-stream; name=\"{$attach['name']}\"\r\n";
                $message .= "Content-Transfer-Encoding: base64\r\n";
                $message .= "Content-Disposition: attachment; filename=\"{$attach['name']}\"\r\n\r\n";
                $message .= $encoded . "\r\n";
            }
            $message .= "--{$boundary}--\r\n";
        } else {
            $headers[] = "Content-Type: text/plain; charset=UTF-8";
            $message = $body;
        }

        $headersStr = implode("\r\n", $headers);
        return mail(implode(', ', $to), $subject, $message, $headersStr);
    }
}