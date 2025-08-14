<?php

namespace Rivulet\Console;

use Rivulet\Rivulet;
use Exception;
use Rivulet\Console\Commands\{
    RunServer,
    CreateModel,
    CreateController,
    CreateService,
    CreateTemplate,
    CreateEvent,
    CreateRule,
    CreateResource,
    CreateSeeder,
    Create,
    DatabaseMigrate,
    DatabaseSeed,
    DatabaseRollback,
    CacheClear,
    LogsClear,
    ConfigClear,
    ConfigCache,
    RoutesList,
    RoutesClear,
    RoutesCache,
    QueueWork,
    ScheduleRun,
    StorageLink,
    TestRun,
    KeyGenerate,
    Poke,
    Optimize,
    CreateJob,
    CreateListener,
    CreateMiddleware
};

/**
 * Console application handler for Rivulet framework
 * 
 * Manages command registration, execution, and provides help system
 */
class Console 
{
    /**
     * @var Rivulet The application instance
     */
    protected $app;

    /**
     * @var array Registered commands with their handler classes
     */
    protected $commands = [
        'run' => RunServer::class,
        'create:model' => CreateModel::class,
        'create:controller' => CreateController::class,
        'create:service' => CreateService::class,
        'create:template' => CreateTemplate::class,
        'create:event' => CreateEvent::class,
        'create:rule' => CreateRule::class,
        'create:resource' => CreateResource::class,
        'create:seeder' => CreateSeeder::class,
        'create' => Create::class,
        'database:migrate' => DatabaseMigrate::class,
        'database:seed' => DatabaseSeed::class,
        'database:rollback' => DatabaseRollback::class,
        'cache:clear' => CacheClear::class,
        'logs:clear' => LogsClear::class,
        'config:clear' => ConfigClear::class,
        'config:cache' => ConfigCache::class,
        'routes:list' => RoutesList::class,
        'routes:clear' => RoutesClear::class,
        'routes:cache' => RoutesCache::class,
        'queue:work' => QueueWork::class,
        'schedule:run' => ScheduleRun::class,
        'storage:link' => StorageLink::class,
        'test:run' => TestRun::class,
        'key:generate' => KeyGenerate::class,
        'poke' => Poke::class,
        'optimize' => Optimize::class,
        'create:listener' => CreateListener::class,
        'create:job' => CreateJob::class,
        'create:middleware' =>CreateMiddleware::class,
    ];

    /**
     * Console constructor
     * 
     * @param Rivulet $app The application instance
     */
    public function __construct(Rivulet $app) 
    {
        $this->app = $app;
    }

    /**
     * Main entry point for console execution
     * 
     * @param array $argv Command line arguments
     * @return void
     */
    public function run(array $argv) 
    {
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

    /**
     * Displays available commands and usage help
     * 
     * @return void
     */
    protected function showHelp() 
    {
        echo "Available commands:\n";
        foreach (array_keys($this->commands) as $cmd) {
            echo "- {$cmd}\n";
        }
    }
}