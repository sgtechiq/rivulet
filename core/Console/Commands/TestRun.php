<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class TestRun
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new test runner command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute PHPUnit tests
     * Runs either all tests or a specific test file
     *
     * @param array $args Command arguments [test_file]
     * @return void
     */
    public function execute($args)
    {
        $file = $args[0] ?? '';
        exec("vendor/bin/phpunit {$file}", $output);
        echo implode("\n", $output) . "\n";
    }
}
