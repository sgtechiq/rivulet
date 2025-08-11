<?php

namespace Rivulet\Mail\Drivers;

use Exception;

class SmtpDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $cc, $bcc, $from, $subject, $body, $isHtml, $attachments) {
        $socket = fsockopen($this->config['host'], $this->config['port'], $errno, $errstr, 30);
        if (!$socket) {
            throw new Exception("SMTP connection failed: {$errstr}");
        }

        $this->smtpCommand($socket, "HELO " . gethostname());
        if ($this->config['encryption'] === 'tls') {
            $this->smtpCommand($socket, "STARTTLS");
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        }

        if ($this->config['username']) {
            $this->smtpCommand($socket, "AUTH LOGIN");
            $this->smtpCommand($socket, base64_encode($this->config['username']));
            $this->smtpCommand($socket, base64_encode($this->config['password']));
        }

        $headers = [
            "From: {$from['name']} <{$from['address']}>",
            "Subject: {$subject}",
            "To: " . implode(', ', $to),
            "Date: " . date("r"),
            "MIME-Version: 1.0",
        ];

        if ($cc) $headers[] = "Cc: " . implode(', ', $cc);
        if ($bcc) $headers[] = "Bcc: " . implode(', ', $bcc);

        if ($attachments || $isHtml) {
            $boundary = md5(time());
            $headers[] = "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";
            $message = "--{$boundary}\r\n";
            $message .= "Content-Type: " . ($isHtml ? 'text/html' : 'text/plain') . "; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
            $message .= $body . "\r\n";
            foreach ($attachments as $attach) {
                $fileContent = file_get_contents($attach['file']);
                $encoded = chunk_split(base64_encode($fileContent));
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

        $this->smtpCommand($socket, "MAIL FROM:<{$from['address']}>");
        foreach (array_merge($to, $cc, $bcc) as $recipient) {
            $this->smtpCommand($socket, "RCPT TO:<{$recipient}>");
        }
        $this->smtpCommand($socket, "DATA");
        $this->smtpCommand($socket, implode("\r\n", $headers) . "\r\n\r\n" . $message . "\r\n.");
        $this->smtpCommand($socket, "QUIT");

        fclose($socket);
        return true;
    }

    protected function smtpCommand($socket, $command) {
        fputs($socket, $command . "\r\n");
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') break;
        }
        if (substr($response, 0, 3) >= 400) {
            throw new Exception("SMTP error: {$response}");
        }
        return $response;
    }
}