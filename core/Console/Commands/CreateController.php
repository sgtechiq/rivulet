<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Exception;

class CreateController {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        if (empty($args[0])) {
            throw new Exception("Controller name required");
        }
        $name = $args[0];
        $dir = $this->app->basePath('app/Controllers');
        $path = $this->resolvePath($dir, $name . 'Controller', 'php');
        $content = $this->getTemplate($name);
        file_put_contents($path, $content);
        echo "Controller created: {$path}\n";
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

    protected function getTemplate($name) {
        $className = basename($name) . 'Controller';
        return "<?php\n\nnamespace App\\Controllers;\n\nuse Rivulet\\Controller;\n\nclass {$className} extends Controller {\n    public function list() {\n        // List all items\n    }\n\n    public function show(\$id) {\n        // Show item by id\n    }\n\n    public function store() {\n        // Add new item\n    }\n\n    public function modify(\$id) {\n        // Update item\n    }\n\n    public function delete(\$id) {\n        // Soft delete item\n    }\n\n    public function destroy(\$id) {\n        // Hard delete item\n    }\n}";
    }
}