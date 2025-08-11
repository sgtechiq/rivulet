<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class CreateSeeder
{
    protected $app;

    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    public function execute($args)
    {
        $name      = $args[0];
        $dir       = $this->app->basePath('database/Seeders');
        $path      = $this->resolvePath($dir, $name, 'php');
        $className = basename($name);

        $content = <<<EOD
<?php
namespace Database\\Seeders;

use App\\Models\\{$className};
use Rivulet\\Database\\Migrations\\SeedOperation;

class {$className} extends SeedOperation {
    public function run() {
        \$data = [
            // Add your seed data here
            // Example:
            // 'name' => 'Test User',
            // 'email' => 'test@example.com',
            // 'password' => PassEncrypt('password'),
        ];

        {$className}::create(\$data);

        // For multiple records:
        // foreach (\$users as \$userData) {
        //     {$className}::create(\$userData);
        // }
    }
}
EOD;

        file_put_contents($path, $content);
        echo "Seeder created: {$path}\n";
    }

    protected function resolvePath($baseDir, $name, $ext)
    {
        if (strpos($name, '/') !== false) {
            $parts  = explode('/', $name);
            $file   = array_pop($parts);
            $subDir = implode('/', $parts);
            $dir    = $baseDir . '/' . $subDir;
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            return $dir . '/' . $file . '.' . $ext;
        }
        return $baseDir . '/' . $name . '.' . $ext;
    }
}
