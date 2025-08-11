<?php

namespace Rivulet\Mail\Drivers;

use Exception;

class SendmailDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $cc, $bcc, $from, $subject, $body, $isHtml, $attachments) {
        $sendmail = ini_get('sendmail_path');
        if (!$sendmail) {
            throw new Exception('Sendmail not configured');
        }

        $headers = [
            "From: {$from['name']} <{$from['address']}>",
            "Subject: {$subject}",
            "To: " . implode(', ', $to),
            "MIME-Version: 1.0",
        ];

        if ($cc) $headers[] = "Cc: " . implode(', ', $cc);
        if ($bcc) $headers[] = "Bcc: " . implode(', ', $bcc);

        $message = $this->buildMessage($headers, $body, $isHtml, $attachments);

        $pipe = popen($sendmail . ' -t', 'w');
        if ($pipe === false) {
            throw new Exception('Failed to open sendmail pipe');
        }
        fwrite($pipe, $message);
        $status = pclose($pipe);
        return $status === 0;
    }

    protected function buildMessage($headers, $body, $isHtml, $attachments) {
        $boundary = md5(time());
        $headers[] = "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";
        $message = implode("\r\n", $headers) . "\r\n\r\n";
        $message .= "--{$boundary}\r\n";
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
        return $message;
    }
}