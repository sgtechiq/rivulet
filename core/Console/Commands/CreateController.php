<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new controller classes
 *
 * Creates controller files with standard CRUD methods in the specified location.
 * Supports namespaced controllers by creating appropriate directory structures.
 */
class CreateController
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
     * Executes the controller creation command
     *
     * @param array $args Command arguments [controller_name]
     * @return void
     * @throws Exception If controller name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Controller name required");
        }

        $name    = $args[0];
        $dir     = $this->app->basePath('app/Controllers');
        $path    = $this->resolvePath($dir, $name . 'Controller', 'php');
        $content = $this->generateControllerContent($name);

        file_put_contents($path, $content);
        echo "Controller created: {$path}\n";
    }

    /**
     * Resolves the full file path including directory structure
     *
     * @param string $baseDir Base directory path
     * @param string $name Controller name (may include subdirectories)
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

    /**
     * Generates the controller class content
     *
     * @param string $name Controller name
     * @return string Generated PHP code
     */
    protected function generateControllerContent(string $name): string
    {
        $className = basename($name) . 'Controller';
        $namespace = 'App\\Controllers';

        // Handle namespaced controllers
        if (strpos($name, '/') !== false) {
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
        }

        return <<<PHP
<?php

namespace {$namespace};

use Rivulet\\Controller;

class {$className} extends Controller
{
    /**
     * List all resources
     */
    public function list()
    {
        // Implement listing logic
    }

    /**
     * Show single resource
     *
     * @param mixed \$id Resource identifier
     */
    public function show(\$id)
    {
        // Implement show logic
    }

    /**
     * Store new resource
     */
    public function store()
    {
        // Implement store logic
    }

    /**
     * Update existing resource
     *
     * @param mixed \$id Resource identifier
     */
    public function modify(\$id)
    {
        // Implement update logic
    }

    /**
     * Soft delete resource
     *
     * @param mixed \$id Resource identifier
     */
    public function delete(\$id)
    {
        // Implement soft delete
    }

    /**
     * Permanently destroy resource
     *
     * @param mixed \$id Resource identifier
     */
    public function destroy(\$id)
    {
        // Implement hard delete
    }
}
PHP;
    }
}
