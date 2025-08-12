<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Console\Commands\{
    CreateModel,
    CreateController,
    CreateService,
    CreateTemplate,
    CreateEvent,
    CreateResource,
    CreateSeeder
};

/**
 * Command to generate multiple application components at once
 *
 * Handles bulk creation of various application components (models, controllers, etc.)
 * using a single command with flags for each component type.
 * 
 * Usage: php luna create -flags name
 * Example: php luna create -mcs User  // Creates model, controller and service
 */
class Create
{
    /**
     * @var Rivulet The application instance
     */
    protected $app;

    /**
     * Flag to component type mapping
     * @var array
     */
    protected const FLAG_MAP = [
        'm' => 'model',
        'c' => 'controller',
        's' => 'service',
        't' => 'template',
        'e' => 'event',
        'r' => 'resource',
        'd' => 'seeder',
    ];

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
     * Executes the create command
     *
     * Processes flags and delegates to individual component creators
     * 
     * @param array $args Command arguments [flags, name]
     * @return void
     * @throws \InvalidArgumentException If arguments are invalid
     */
    public function execute(array $args = [])
    {
        if (count($args) < 2 || empty($args[0]) || empty($args[1])) {
            echo "Usage: php luna create -flags name\n";
            echo "Available flags:\n";
            foreach (self::FLAG_MAP as $flag => $type) {
                echo "  -{$flag}  Create {$type}\n";
            }
            return;
        }

        $flags = ltrim($args[0], '-');
        $name = $args[1];

        foreach (str_split($flags) as $flag) {
            if (!isset(self::FLAG_MAP[$flag])) {
                echo "Warning: Unknown flag '{$flag}' ignored\n";
                continue;
            }

            $this->createComponent($flag, $name);
        }
    }

    /**
     * Creates a specific component based on flag
     *
     * @param string $flag Single character flag
     * @param string $name Component name
     * @return void
     */
    protected function createComponent(string $flag, string $name): void
    {
        $type = self::FLAG_MAP[$flag];
        $commandClass = "Create" . ucfirst($type);
        
        if (!class_exists("Rivulet\\Console\\Commands\\{$commandClass}")) {
            echo "Error: Creator for '{$type}' not found\n";
            return;
        }

        $instance = new $commandClass($this->app);
        $instance->execute([$name]);
    }
}