<?php
namespace Rivulet\Console\Commands;

use Psy\Shell;
use Rivulet\Rivulet;

class Poke
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new tinker command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute interactive PsySH shell (REPL)
     * Provides an interactive development console
     * @param array $args Command arguments (unused)
     * @return void
     */
    public function execute($args)
    {
        $shell = new Shell();
        $shell->run();
    }
}
