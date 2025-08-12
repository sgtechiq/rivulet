<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new service classes
 *
 * Creates service classes with standard register() and boot() methods.
 * Services are typically used for business logic and application services.
 */
class CreateService
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
     * Executes the service creation command
     *
     * @param array $args Command arguments [service_name]
     * @return void
     * @throws Exception If service name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Service name is required");
        }

        $name    = $args[0];
        $dir     = $this->app->basePath('app/Services');
        $path    = $this->resolvePath($dir, $name, 'php');
        $content = $this->generateServiceContent($name);

        file_put_contents($path, $content);
        echo "Service created: {$path}\n";
    }

    /**
     * Generates the service class content
     *
     * @param string $name Service name (may include namespace)
     * @return string Generated PHP code
     */
    protected function generateServiceContent(string $name): string
    {
        $className = basename($name);
        $namespace = 'App\\Services';

        // Handle namespaced services
        if (strpos($name, '/') !== false) {
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
        }

        return <<<PHP
<?php

namespace {$namespace};

use Rivulet\\Rivulet;

class {$className}Service
{
    /**
     * @var Rivulet The application instance
     */
    protected \$app;

    /**
     * Constructor
     *
     * @param Rivulet \$app The application instance
     */
    public function __construct(Rivulet \$app)
    {
        \$this->app = \$app;
    }

    /**
     * Register service bindings and configurations
     *
     * @return void
     */
    public function register()
    {
        // Register service bindings
        // \$this->app->bind(Interface::class, Implementation::class);
    }

    /**
     * Bootstrap any application services
     *
     * @return void
     */
    public function boot()
    {
        // Perform service initialization
    }

    /**
     * Add your custom service methods below
     */

    // Example service method
    // public function processData(array \$data): array
    // {
    //     // Business logic implementation
    //     return \$processedData;
    // }
}
PHP;
    }

    /**
     * Resolves the full file path including directory structure
     *
     * @param string \$baseDir Base directory path
     * @param string \$name Service name (may include subdirectories)
     * @param string \$ext File extension
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
