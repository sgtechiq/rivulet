<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class Create {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        if (empty($args[0]) || empty($args[1])) {
            echo "Usage: php luna create -flags name\n";
            return;
        }
        $flags = ltrim($args[0], '-');
        $name = $args[1];

        $map = [
            'm' => 'model',
            'c' => 'controller',
            's' => 'service',
            't' => 'template',
            'e' => 'event',
            'r' => 'resource',
            'd' => 'seeder',
        ];

        for ($i = 0; $i < strlen($flags); $i++) {
            $flag = $flags[$i];
            if (isset($map[$flag])) {
                $commandClass = "Create" . ucfirst($map[$flag]);
                $fullClass = "\\Rivulet\\Console\\Commands\\{$commandClass}";
                $instance = new $fullClass($this->app);
                $instance->execute([$name]);
            }
        }
    }
}