<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Cache\Cache;

class CacheClear {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $cache = new Cache($this->app->getConfig('cache'));
        $cache->clear();
        echo "Cache cleared\n";
    }
}

// Similar for logs:clear (unlink files), config:clear (if cached, delete cache file), etc.