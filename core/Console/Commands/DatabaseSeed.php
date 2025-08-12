<?php
namespace Rivulet\Console\Commands;

use Rivulet\Database\Migrations\Runner;
use Rivulet\Rivulet;

/**
 * Command to seed the database
 *
 * Executes all database seeders to populate the database with initial data.
 * This command should be run after migrations in development environments.
 */
class DatabaseSeed
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
     * Executes the seeding command
     *
     * @param array $args Command arguments (not used in this command)
     * @return void
     */
    public function execute($args)
    {
        $runner = new Runner($this->app);
        $runner->seed();
        echo "Database seeded\n";
    }
}
