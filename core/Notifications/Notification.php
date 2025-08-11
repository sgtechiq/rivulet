<?php

namespace Rivulet\Notifications;

use Rivulet\Rivulet;
use Exception;

class Notification {
    protected $app;
    protected $channel;
    protected $to;
    protected $title;
    protected $body;
    protected $data = [];

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function channel($channel) {
        $this->channel = $channel;
        if (!$this->isEnabled($channel)) {
            throw new Exception("Notification channel {$channel} not enabled (check env)");
        }
        return $this;
    }

    public function to($to) {
        $this->to = $to;
        return $this;
    }

    public function title($title) {
        $this->title = $title;
        return $this;
    }

    public function body($body) {
        $this->body = $body;
        return $this;
    }

    public function data(array $data) {
        $this->data = $data;
        return $this;
    }

    public function send() {
        if (!$this->channel) {
            throw new Exception('No notification channel specified');
        }
        $driverClass = $this->getDriverClass($this->channel);
        $driver = new $driverClass($this->app->getConfig('services.' . $this->channel));
        $result = $driver->send($this->to, $this->title, $this->body, $this->data);
        $this->reset();
        return $result;
    }

    protected function getDriverClass($channel) {
        $map = [
            'firebase' => Drivers\FirebaseDriver::class,
            'pusher' => Drivers\PusherDriver::class,
            'slack' => Drivers\SlackDriver::class,
            'whatsapp' => Drivers\WhatsappDriver::class,
            'sms' => Drivers\SmsDriver::class,
            'mail' => Drivers\MailDriver::class,
        ];
        if (!isset($map[$channel])) {
            throw new Exception("Unsupported notification channel: {$channel}");
        }
        return $map[$channel];
    }

    protected function isEnabled($channel) {
        $keyMap = [
            'firebase' => 'NOTIFICATION_FIREBASE_API_KEY',
            'pusher' => 'NOTIFICATION_PUSHER_APP_KEY',
            'slack' => 'NOTIFICATION_SLACK_WEBHOOK',
            'whatsapp' => 'NOTIFICATION_WHATSAPP_API_KEY',
            'sms' => 'NOTIFICATION_SMS_ACCOUNT_SID',
            'mail' => 'MAIL_HOST',
        ];
        return !empty(env($keyMap[$channel] ?? ''));
    }

    protected function reset() {
        $this->channel = $this->to = $this->title = $this->body = null;
        $this->data = [];
    }
}