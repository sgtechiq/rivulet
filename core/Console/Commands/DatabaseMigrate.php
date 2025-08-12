<?php
namespace Rivulet\Console\Commands;

use Rivulet\Database\Migrations\Runner;
use Rivulet\Rivulet;

/**
 * Command to run database migrations
 *
 * Executes all pending database migrations in the order they were created.
 * This command should be run after creating new migrations or deploying to a new environment.
 */
class DatabaseMigrate
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
     * Executes the migration command
     *
     * @param array $args Command arguments (not used in this command)
     * @return void
     */
    public function execute($args)
    {
        $runner = new Runner($this->app);
        $runner->migrate();
        echo "Migrations run\n";
    }
}
