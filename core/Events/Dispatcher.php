<?php

namespace Rivulet\Events;

use Rivulet\Rivulet;
use Exception;

class Dispatcher {
    protected $app;
    protected $listeners = [];

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function listen($event, $listener) {
        $this->listeners[$event][] = $listener;
    }

    public function fire($event, $data = []) {
        if (!class_exists($event)) {
            throw new Exception("Event class {$event} not found");
        }
        $eventInstance = new $event($data);
        $listeners = $this->listeners[$event] ?? [];
        foreach ($listeners as $listener) {
            if (!class_exists($listener)) {
                throw new Exception("Listener class {$listener} not found");
            }
            $listenerInstance = new $listener();
            $listenerInstance->handle($eventInstance);
        }
    }
}