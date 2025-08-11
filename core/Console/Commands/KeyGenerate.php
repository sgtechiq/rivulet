<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class KeyGenerate {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $key = bin2hex(random_bytes(16)); // 32 char
        $envFile = $this->app->basePath('.env');
        $content = file_get_contents($envFile);
        $content = preg_replace('/^APP_KEY=.*$/m', "APP_KEY={$key}", $content);
        file_put_contents($envFile, $content);
        echo "APP_KEY generated: {$key}\n";
    }
}