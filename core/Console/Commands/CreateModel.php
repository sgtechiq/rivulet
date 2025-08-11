<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Exception;

class CreateModel {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        if (empty($args[0])) {
            throw new Exception("Model name required");
        }
        $name = $args[0];
        $dir = $this->app->basePath('app/Models');
        $path = $this->resolvePath($dir, $name, 'php');
        $content = $this->getTemplate($name);
        file_put_contents($path, $content);
        echo "Model created: {$path}\n";
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
        $className = basename($name);
        return "<?php\n\nnamespace App\Models;\n\nuse Rivulet\Model;\n\nclass {$className} extends Model {\n    // Define table, fillable, etc.\n}";
    }
}

// Similar for other create commands, adjust template/dir