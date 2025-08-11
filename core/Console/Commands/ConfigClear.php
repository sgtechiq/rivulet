<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class ConfigClear {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $cacheFile = $this->app->basePath('storage/cache/config.cache');
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
            echo "Config cache cleared\n";
        } else {
            echo "No config cache to clear\n";
        }
    }
}