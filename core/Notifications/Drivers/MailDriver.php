<?php

namespace Rivulet\Notifications\Drivers;

use Rivulet\Mail\Mailer;

class MailDriver {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function send($to, $title, $body, $data) {
        $mailer = app()->make('mail');
        $mailer->to($to)->subject($title)->html($body)->send();
        return true;
    }
}