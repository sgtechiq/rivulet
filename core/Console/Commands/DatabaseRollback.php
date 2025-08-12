<?php
namespace Rivulet\Console\Commands;

use Rivulet\Database\Migrations\Runner;
use Rivulet\Rivulet;

/**
 * Command to rollback database migrations
 *
 * Reverts the most recent database migration batch.
 * This command should be used carefully in production environments.
 */
class DatabaseRollback
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
     * Executes the rollback command
     *
     * @param array $args Command arguments (not used in this command)
     * @return void
     */
    public function execute($args)
    {
        $runner = new Runner($this->app);
        $runner->rollback();
        echo "Rollback complete\n";
    }
}
