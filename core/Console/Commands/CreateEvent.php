<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new event classes
 *
 * Creates event class files in the specified location with proper namespace.
 * Supports namespaced events by creating appropriate directory structures.
 */
class CreateEvent
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
     * Executes the event creation command
     *
     * @param array $args Command arguments [event_name]
     * @return void
     * @throws Exception If event name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Event name is required");
        }

        $name    = $args[0];
        $dir     = $this->app->basePath('app/Events');
        $path    = $this->resolvePath($dir, $name, 'php');
        $content = $this->generateEventContent($name);

        file_put_contents($path, $content);
        echo "Event created: {$path}\n";
    }

    /**
     * Generates the event class content
     *
     * @param string $name Event name (may include namespace)
     * @return string Generated PHP code
     */
    protected function generateEventContent(string $name): string
    {
        $className = basename($name);
        $namespace = 'App\\Events';

        // Handle namespaced events
        if (strpos($name, '/') !== false) {
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
        }

        return <<<PHP
<?php

namespace {$namespace};

use Rivulet\\Events\\Event;

class {$className} extends Event
{
    /**
     * Create a new event instance
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the event properties
     */
    public function getProperties(): array
    {
        return [];
    }
}
PHP;
    }

    /**
     * Resolves the full file path including directory structure
     *
     * @param string $baseDir Base directory path
     * @param string $name Event name (may include subdirectories)
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
