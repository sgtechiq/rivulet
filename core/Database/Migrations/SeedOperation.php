<?php

namespace Rivulet\Database\Migrations;

// For seeders, base class
abstract class SeedOperation {
    abstract public function run();
}