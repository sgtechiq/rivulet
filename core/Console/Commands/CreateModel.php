<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new model classes
 *
 * Creates model files with base Model class extension in the specified location.
 * Supports namespaced models by creating appropriate directory structures.
 */
class CreateModel
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
     * Executes the model creation command
     *
     * @param array $args Command arguments [model_name]
     * @return void
     * @throws Exception If model name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Model name is required");
        }

        $name    = $args[0];
        $dir     = $this->app->basePath('app/Models');
        $path    = $this->resolvePath($dir, $name, 'php');
        $content = $this->generateModelContent($name);

        file_put_contents($path, $content);
        echo "Model created: {$path}\n";
    }

    /**
     * Generates the model class content
     *
     * @param string $name Model name (may include namespace)
     * @return string Generated PHP code
     */
    protected function generateModelContent(string $name): string
    {
        $className = basename($name);
        $namespace = 'App\\Models';

        // Handle namespaced models
        if (strpos($name, '/') !== false) {
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
        }

        return <<<PHP
<?php

namespace {$namespace};

use Rivulet\\Model;

class {$className} extends Model
{
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected \$table = '';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected \$fillable = [];

    /**
     * The attributes that should be hidden for serialization
     *
     * @var array
     */
    protected \$hidden = [];

    /**
     * The attributes that should be cast
     *
     * @var array
     */
    protected \$casts = [];

    /**
     * The primary key for the model
     *
     * @var string
     */
    protected \$primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public \$timestamps = true;
}
PHP;
    }

    /**
     * Resolves the full file path including directory structure
     *
     * @param string $baseDir Base directory path
     * @param string $name Model name (may include subdirectories)
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
