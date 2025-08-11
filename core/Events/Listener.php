<?php

namespace Rivulet\Events;

abstract class Listener {
    abstract public function handle(Event $event);
}