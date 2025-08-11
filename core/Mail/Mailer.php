<?php

namespace Rivulet\Mail;

use Rivulet\Rivulet;
use Exception;

class Mailer {
    protected $app;
    protected $mailerName;
    protected $config;
    protected $to = [];
    protected $cc = [];
    protected $bcc = [];
    protected $from;
    protected $subject;
    protected $body;
    protected $isHtml = false;
    protected $attachments = [];

    public function __construct(Rivulet $app, $mailerName = null) {
        $this->app = $app;
        $this->mailerName = $mailerName ?? $app->getConfig('mail.default');
        $this->config = $app->getConfig("mail.mailers.{$this->mailerName}");
        $this->from = $this->config['from'];
    }

    public function to($email) {
        $this->to[] = $email;
        return $this;
    }

    public function cc($email) {
        $this->cc[] = $email;
        return $this;
    }

    public function bcc($email) {
        $this->bcc[] = $email;
        return $this;
    }

    public function subject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function text($body) {
        $this->body = $body;
        $this->isHtml = false;
        return $this;
    }

    public function html($body) {
        $this->body = $body;
        $this->isHtml = true;
        return $this;
    }

    public function view($template, $data = []) {
        $view = $this->app->make('view');
        $this->body = $view->render($template, $data);
        $this->isHtml = true;
        return $this;
    }

    public function attach($file, $name = null) {
        $this->attachments[] = ['file' => $file, 'name' => $name ?? basename($file)];
        return $this;
    }

    public function send() {
        $driverClass = $this->getDriverClass();
        $driver = new $driverClass($this->config);
        $result = $driver->send($this->to, $this->cc, $this->bcc, $this->from, $this->subject, $this->body, $this->isHtml, $this->attachments);
        $this->reset();
        return $result;
    }

    protected function getDriverClass() {
        $transport = $this->config['transport'];
        switch ($transport) {
            case 'smtp':
                return Drivers\SmtpDriver::class;
            case 'mailgun':
                return Drivers\MailgunDriver::class;
            case 'sendgrid':
                return Drivers\SendGridDriver::class;
            case 'sendmail':
                return Drivers\SendmailDriver::class;
            case 'mail':
                return Drivers\PhpMailDriver::class;
            default:
                throw new Exception("Unsupported mail transport: {$transport}");
        }
    }

    protected function reset() {
        $this->to = $this->cc = $this->bcc = $this->attachments = [];
        $this->subject = $this->body = null;
        $this->isHtml = false;
    }
}