<?php
namespace Rivulet\Events;

/**
 * Base Event Class
 *
 * Provides foundation for event-driven architecture in the application.
 * Concrete event classes should extend this class.
 */
abstract class Event
{
    /**
     * @var array Event payload data
     */
    protected $data;

    /**
     * Create new event instance
     *
     * @param array $data Event payload data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get event data payload
     *
     * @return array Event data
     */
    public function getData(): array
    {
        return $this->data;
    }
}
