<?php
namespace Rivulet\Events;

/**
 * Abstract Event Listener
 *
 * Base class for all event listeners in the application.
 * Concrete listeners must implement the handle() method.
 */
abstract class Listener
{
    /**
     * Process the event
     *
     * @param Event $event The event instance to handle
     * @return void
     */
    abstract public function handle(Event $event);
}
