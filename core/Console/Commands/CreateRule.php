<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateRule {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $name = $args[0];
        $dir = $this->app->basePath('app/Rules');
        $path = $this->resolvePath($dir, $name, 'php');
        $className = basename($name);
        $content = "<?php\n\nnamespace App\\Rules;\n\nclass {$className} {\n    public function passes(\$field, \$value) {\n        return true;\n    }\n\n    public function message(\$field) {\n        return \"{\$field} failed {$className} rule\";\n    }\n}";
        file_put_contents($path, $content);
        echo "Rule created: {$path}\n";
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