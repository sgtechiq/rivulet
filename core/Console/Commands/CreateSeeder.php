<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateSeeder {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $name = $args[0];
        $dir = $this->app->basePath('database/Seeders');
        $path = $this->resolvePath($dir, $name, 'php');
        $className = basename($name);
$content = "<?php\n\nnamespace Database\\Seeders;\n\nuse Rivulet\\Database\\Migrations\\SeedOperation;\n\nclass {$className} extends SeedOperation {\n    public function run() {\n        // Seed data\n    }\n}";        file_put_contents($path, $content);
        echo "Seeder created: {$path}\n";
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