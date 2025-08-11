<?php
namespace Rivulet\Providers;

use Exception;
use Rivulet\Database\Migrations\Runner;
use Rivulet\Queue\Queue;

/**
 * Queue Service Provider
 *
 * Registers the queue service and optionally sets up required database tables.
 */
class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register the queue service
     *
     * Binds the Queue implementation to the container.
     */
    public function register(): void
    {
        $this->app->bind('queue', function ($app) {
            return new Queue($app);
        });
    }

    /**
     * Bootstrap queue service
     *
     * Optionally sets up database tables if using database queue driver.
     * Note: Actual migration should be handled via CLI command for production.
     */
    public function boot(): void
    {
        if ($this->shouldAutoMigrate()) {
            $this->migrateJobsTable();
        }
    }

    /**
     * Determine if auto-migration should run
     *
     * @return bool True only in local/testing environments
     */
    protected function shouldAutoMigrate(): bool
    {
        return $this->app->getConfig('app.env') === 'local' &&
        $this->app->getConfig('queue.default') === 'database';
    }

    /**
     * Run jobs table migration if needed
     *
     * @throws Exception If migration fails
     */
    protected function migrateJobsTable(): void
    {
        try {
            $runner = new Runner($this->app);
            // Implementation would check if table exists first
            // then run specific migration file if needed
        } catch (Exception $e) {
            throw new Exception('Queue table migration failed: ' . $e->getMessage());
        }
    }
}
