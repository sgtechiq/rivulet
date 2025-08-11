<?php

namespace Rivulet\Console;

use Rivulet\Rivulet;
use Exception;

class Console {
    protected $app;
    protected $commands = [
        'run' => Commands\RunServer::class,
        'create:model' => Commands\CreateModel::class,
        'create:controller' => Commands\CreateController::class,
        'create:service' => Commands\CreateService::class,
        'create:template' => Commands\CreateTemplate::class,
        'create:event' => Commands\CreateEvent::class,
        'create:rule' => Commands\CreateRule::class,
        'create:resource' => Commands\CreateResource::class,
        'create:seeder' => Commands\CreateSeeder::class,
        'create' => Commands\Create::class,
        'database:migrate' => Commands\DatabaseMigrate::class,
        'database:seed' => Commands\DatabaseSeed::class,
        'database:rollback' => Commands\DatabaseRollback::class,
        'cache:clear' => Commands\CacheClear::class,
        'logs:clear' => Commands\LogsClear::class,
        'config:clear' => Commands\ConfigClear::class,
        'config:cache' => Commands\ConfigCache::class,
        'routes:list' => Commands\RoutesList::class,
        'routes:clear' => Commands\RoutesClear::class,
        'routes:cache' => Commands\RoutesCache::class,
        'queue:work' => Commands\QueueWork::class,
        'schedule:run' => Commands\ScheduleRun::class,
        'storage:link' => Commands\StorageLink::class,
        'test:run' => Commands\TestRun::class,
    'key:generate' => Commands\KeyGenerate::class,
    'tinker' => Commands\Tinker::class,
    'optimize' => Commands\Optimize::class,
    ];

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function run($argv) {
        if (count($argv) < 2) {
            $this->showHelp();
            return;
        }

        $command = $argv[1];
        $args = array_slice($argv, 2);

        if (isset($this->commands[$command])) {
            $class = $this->commands[$command];
            $instance = new $class($this->app);
            $instance->execute($args);
        } else {
            echo "Command not found: {$command}\n";
            $this->showHelp();
        }
    }

    protected function showHelp() {
        echo "Available commands:\n";
        foreach (array_keys($this->commands) as $cmd) {
            echo "- {$cmd}\n";
        }
    }
}