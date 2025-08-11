<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateService {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $name = $args[0];
        $dir = $this->app->basePath('app/Services');
        $path = $this->resolvePath($dir, $name, 'php');
        $className = basename($name);
        $content = "<?php\n\nnamespace App\\Services;\n\nuse Rivulet\\Rivulet;\n\nclass {$className} {\n    protected \$app;\n\n    public function __construct(Rivulet \$app) {\n        \$this->app = \$app;\n    }\n\n    public function register() {\n        // Register logic\n    }\n\n    public function boot() {\n        // Boot logic\n    }\n}";
        file_put_contents($path, $content);
        echo "Service created: {$path}\n";
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