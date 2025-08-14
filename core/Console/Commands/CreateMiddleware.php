<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateMiddleware {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        if (empty($args[0])) {
            echo "Middleware name required\n";
            return;
        }
        $name = $args[0];
        $dir = $this->app->basePath('app/Middleware');
        $path = $this->resolvePath($dir, $name, 'php');
        $className = basename($name) . 'Middleware';
        $content = "<?php\n\nnamespace App\\Middleware;\n\nuse Rivulet\\Middleware\\Middleware;\nuse Rivulet\\Http\\Request;\nuse Closure;\n\nclass {$className} implements Middleware {\n    public function handle(Request \$request, Closure \$next) {\n        // Custom logic here\n        return \$next(\$request);\n    }\n}";
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $content);
        echo "Middleware created: {$path}\n";
    }

    protected function resolvePath($baseDir, $name, $ext) {
        if (strpos($name, '/') !== false) {
            $parts = explode('/', $name);
            $file = array_pop($parts) . 'Middleware';
            $subDir = implode('/', $parts);
            $dir = $baseDir . '/' . $subDir;
            return $dir . '/' . $file . '.' . $ext;
        }
        return $baseDir . '/' . $name . 'Middleware.' . $ext;
    }
}