<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class LogsClear
{
    /**
     * The Rivulet application instance
     *
     * @var Rivulet
     */
    protected $app;

    /**
     * Create a new command instance
     *
     * @param Rivulet $app The application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute the command to clear application logs
     *
     * Removes all .log files from the storage/logs directory.
     * Outputs confirmation message when complete.
     *
     * @param array $args Command arguments (unused in this command)
     * @return void
     * @throws \RuntimeException If unable to delete log files
     */
    public function execute($args)
    {
        $logDir = $this->app->basePath('storage/logs');
        $files  = glob($logDir . '/*.log');

        if ($files === false) {
            throw new \RuntimeException("Failed to read log directory");
        }

        foreach ($files as $file) {
            if (! unlink($file)) {
                throw new \RuntimeException("Failed to delete log file: {$file}");
            }
        }

        echo "Logs cleared\n";
    }
}
