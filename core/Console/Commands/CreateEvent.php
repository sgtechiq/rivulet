<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateEvent {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $name = $args[0];
        $dir = $this->app->basePath('app/Events');
        $path = $this->resolvePath($dir, $name, 'php');
        $className = basename($name);
        $content = "<?php\n\nnamespace App\\Events;\n\nuse Rivulet\\Events\\Event;\n\nclass {$className} extends Event {\n    // Event logic\n}";
        file_put_contents($path, $content);
        echo "Event created: {$path}\n";
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