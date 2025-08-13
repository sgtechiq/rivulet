<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class RunServer
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new server command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute server command
     * Starts PHP development server
     * @param array $args Command arguments [host:port]
     */
    public function execute($args)
    {
        $host = $args[0] ?? 'localhost:8080';
        echo "Starting server on http://{$host}\n";
        exec("php -S {$host} -t public");
    }
}
