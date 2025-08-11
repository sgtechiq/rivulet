<?php
namespace Rivulet\Providers;

use Exception;
use Rivulet\Events\Dispatcher;

/**
 * Event Service Provider
 *
 * Registers the event dispatcher and binds configured event listeners.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the event dispatcher
     *
     * Binds a singleton instance of the event dispatcher to the container.
     */
    public function register(): void
    {
        $this->app->bind
            ('event', function ($app) {
            return new Dispatcher($app);
        });
    }

    /**
     * Bootstrap event listeners
     *
     * Registers all configured event listeners from the configuration.
     * @throws Exception If event configuration is invalid
     */
    public function boot(): void
    {
        $dispatcher = $this->app->make('event');
        $events     = $this->app->getConfig('events', []);

        if (! is_array($events)) {
            throw new Exception('Invalid events configuration - must be an array');
        }

        foreach ($events as $event => $listeners) {
            $this->registerListeners($dispatcher, $event, (array) $listeners);
        }
    }

    /**
     * Register listeners for a specific event
     *
     * @param Dispatcher $dispatcher Event dispatcher instance
     * @param string $event Event class name
     * @param array $listeners Array of listener class names
     * @throws Exception If listener registration fails
     */
    protected function registerListeners(Dispatcher $dispatcher, string $event, array $listeners): void
    {
        foreach ($listeners as $listener) {
            if (! is_string($listener)) {
                throw new Exception(sprintf(
                    'Invalid listener for event %s - must be a class name',
                    $event
                ));
            }

            $dispatcher->listen($event, $listener);
        }
    }
}
