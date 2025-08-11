<?php
namespace Rivulet\Providers;

use Rivulet\Mail\Mailer;

/**
 * Mail Service Provider
 *
 * Registers the mailer service with the application container.
 * Provides email sending capabilities throughout the application.
 */
class MailServiceProvider extends ServiceProvider
{
    /**
     * Register the mailer service
     *
     * Binds a new Mailer instance to the container each time it's resolved.
     * Available via the 'mail' alias and through dependency injection.
     */
    public function register(): void
    {
        $this->app->bind('mail', function ($app) {
            return new Mailer($app);
        });
    }
}
