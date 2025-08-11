<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class StorageLink {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $target = $this->app->basePath('storage/uploads');
        $link = $this->app->basePath('public/storage');
        if (is_link($link)) {
            unlink($link);
        }
        symlink($target, $link);
        echo "Storage linked\n";
    }
}