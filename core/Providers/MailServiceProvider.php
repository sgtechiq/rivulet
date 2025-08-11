<?php

namespace Rivulet\Providers;

use Rivulet\Mail\Mailer;

class MailServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('mail', function ($app) {
            return new Mailer($app);
        });
    }
}