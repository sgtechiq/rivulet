<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class RoutesList
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new routes list command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute route listing command
     * Displays all registered routes with their methods and actions
     * @param array $args Command arguments (unused)
     */
    public function execute($args)
    {
        $router = $this->app->make('router');
        $router->loadRoutes(); // Ensure routes are loaded

        foreach ($router->routes as $route) {
            $action = is_array($route->action)
            ? $route->action[0] . '@' . $route->action[1]
            : 'Closure';

            echo "{$route->method} {$route->uri} -> {$action}\n";
        }
    }
}
