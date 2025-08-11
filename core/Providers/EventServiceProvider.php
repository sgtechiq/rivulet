<?php

namespace Rivulet\Providers;

use Rivulet\Events\Dispatcher;

class EventServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('event', function ($app) {
            return new Dispatcher($app);
        });
    }

    public function boot() {
        $dispatcher = $this->app->make('event');
        $events = $this->app->getConfig('events', []);
        foreach ($events as $event => $listeners) {
            foreach ((array) $listeners as $listener) {
                $dispatcher->listen($event, $listener);
            }
        }
    }
}