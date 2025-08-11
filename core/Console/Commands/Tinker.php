<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Psy\Shell;

class Tinker {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $shell = new Shell();
        $shell->run();
    }
}