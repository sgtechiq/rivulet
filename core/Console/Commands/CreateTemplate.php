<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateTemplate {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $name = $args[0];
        $dir = $this->app->basePath('resources/views');
        $path = $this->resolvePath($dir, $name, 'html');
        $content = "<!-- {$name} template -->\n<html>\n<body>\n<h1>Hello</h1>\n</body>\n</html>";
        file_put_contents($path, $content);
        echo "Template created: {$path}\n";
    }

   protected function resolvePath($baseDir, $name, $ext) {
    if (strpos($name, '/') !== false) {
        $parts = explode('/', $name);
        $file = array_pop($parts);
        $subDir = implode('/', $parts);
        $dir = $baseDir . '/' . $subDir;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir . '/' . $file . '.' . $ext;
    }
    return $baseDir . '/' . $name . '.' . $ext;
}
}