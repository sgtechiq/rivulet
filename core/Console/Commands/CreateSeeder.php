<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new database seeder classes
 *
 * Creates seeder files with boilerplate code for database seeding.
 * Supports namespaced seeders by creating appropriate directory structures.
 */
class CreateSeeder
{
    /**
     * @var Rivulet The application instance
     */
    protected $app;

    /**
     * Constructor
     *
     * @param Rivulet $app The application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Executes the seeder creation command
     *
     * @param array $args Command arguments [seeder_name]
     * @return void
     * @throws Exception If seeder name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Seeder name is required");
        }

        $name    = $args[0];
        $dir     = $this->app->basePath('database/Seeders');
        $path    = $this->resolvePath($dir, $name, 'php');
        $content = $this->generateSeederContent($name);

        file_put_contents($path, $content);
        echo "Seeder created: {$path}\n";
    }

    /**
     * Generates the seeder class content
     *
     * @param string $name Seeder name (may include namespace)
     * @return string Generated PHP code
     */
    protected function generateSeederContent(string $name): string
    {
        $className = basename($name);
        $namespace = 'Database\\Seeders';

        // Handle namespaced seeders
        if (strpos($name, '/') !== false) {
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
        }

        return <<<PHP
<?php

namespace {$namespace};

use App\\Models\\{$className};
use Rivulet\\Database\\Seeders\\Seeder;

class {$className}Seeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        // Single record example
        {$className}::create([
            // 'column1' => 'value1',
            // 'column2' => 'value2',
            // Add your seed data here
        ]);

        // Multiple records example
        // {$className}::insert([
        //     [
        //         'column1' => 'value1',
        //         'column2' => 'value2',
        //     ],
        //     [
        //         'column1' => 'value3',
        //         'column2' => 'value4',
        //     ],
        // ]);

        // Factory example (if available)
        // {$className}::factory()->count(10)->create();
    }
}
PHP;
    }

    /**
     * Resolves the full file path including directory structure
     *
     * @param string $baseDir Base directory path
     * @param string $name Seeder name (may include subdirectories)
     * @param string $ext File extension
     * @return string Full file path
     */
    protected function resolvePath(string $baseDir, string $name, string $ext): string
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
