<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateResource {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $name = $args[0];
        $dir = $this->app->basePath('database/Migrations');
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_create_{strtolower($name)}_table";
        $path = $dir . '/' . $fileName . '.php';
        $className = 'Create' . ucfirst($name) . 'Table';
        $content = "<?php\n\nuse Rivulet\\Database\\Migrations\\Migration;\n\nclass {$className} extends Migration {\n    public function up() {\n        \$this->executeSchema(function (\$builder) {\n            \$builder->createTable('{$name}', function (\$add) {\n                \$add('id', 'INT', ['auto_increment' => true, 'primary_key' => true]);\n                // Add columns\n            });\n        });\n    }\n\n    public function down() {\n        \$this->executeSchema(function (\$builder) {\n            \$builder->dropTable('{$name}');\n        });\n    }\n}";
        file_put_contents($path, $content);
        echo "Resource migration created: {$path}\n";
    }
}