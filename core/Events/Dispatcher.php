<?php
namespace Rivulet\Events;

use Exception;
use Rivulet\Rivulet;

/**
 * Event Dispatcher
 *
 * Handles registration and triggering of event listeners.
 */
class Dispatcher
{
    /**
     * @var Rivulet Application instance
     */
    protected $app;

    /**
     * @var array Registered event listeners [event => [listeners]]
     */
    protected $listeners = [];

    /**
     * Create new event dispatcher
     *
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Register an event listener
     *
     * @param string $event Event class name
     * @param string $listener Listener class name
     */
    public function listen(string $event, string $listener)
    {
        $this->listeners[$event][] = $listener;
    }

    /**
     * Trigger an event
     *
     * @param string $event Event class name
     * @param array $data Event payload data
     * @throws Exception If event or listener class doesn't exist
     */
    public function fire(string $event, array $data = [])
    {
        if (! class_exists($event)) {
            throw new Exception("Event class {$event} not found");
        }

        $eventInstance = new $event($data);
        $listeners     = $this->listeners[$event] ?? [];

        foreach ($listeners as $listener) {
            if (! class_exists($listener)) {
                throw new Exception("Listener class {$listener} not found");
            }

            $listenerInstance = new $listener();
            $listenerInstance->handle($eventInstance);
        }
    }
}
