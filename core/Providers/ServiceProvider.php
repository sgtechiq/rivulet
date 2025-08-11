<?php

namespace Rivulet\Providers;

use Rivulet\Rivulet;

abstract class ServiceProvider {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    abstract public function register();

    public function boot() {
        // Optional, can be overridden
    }
}