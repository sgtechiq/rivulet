<?php

namespace Rivulet\Queue;

abstract class Job {
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    abstract public function handle();
}