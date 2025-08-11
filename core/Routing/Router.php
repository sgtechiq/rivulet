<?php
namespace Rivulet\Routing;

use Closure;
use Exception;
use Rivulet\Http\Request;
use Rivulet\Http\Response;
use Rivulet\Rivulet;

/**
 * Application Router
 *
 * Handles route registration, dispatching, and middleware processing
 */
class Router
{
    protected $app;
    protected $routes                 = [];
    protected $currentGroupPrefix     = '';
    protected $currentGroupMiddleware = [];

    /**
     * Initialize router with application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Load route definitions from route files
     */
    public function loadRoutes()
    {
        $cacheFile = $this->app->basePath('storage/cache/routes.cache');
        if (file_exists($cacheFile)) {
            $this->routes = unserialize(file_get_contents($cacheFile));
            return;
        }

        $handlers = $this->app->getConfig('routes.handlers', []);
        $basePath = $this->app->basePath('routes');

        foreach ($handlers as $handler => $file) {
            $prefix = $handler ? '/' . $handler : '';
            $this->prefix($prefix, function () use ($basePath, $file) {
                require $basePath . '/' . $file;
            });
        }
        file_put_contents($cacheFile, serialize($this->routes));
    }

    /**
     * Dispatch request to matching route
     * @param Request $request HTTP request
     * @throws Exception If route action fails
     */
    public function dispatch(Request $request)
    {
        $uri    = $request->uri;
        $method = $request->method;

        foreach ($this->routes as $route) {
            if ($route->method !== $method) {
                continue;
            }

            if (preg_match($route->pattern, $uri, $matches)) {
                array_shift($matches);
                $params = $matches;

                $this->applyMiddleware($route->middleware, $request);

                return $this->callAction($route, $request, $params);
            }
        }

        return $this->handleNotFound();
    }

    /**
     * Execute route action
     * @param Route $route Matched route
     * @param Request $request HTTP request
     * @param array $params Route parameters
     */
    protected function callAction(Route $route, Request $request, array $params = [])
    {
        $action = $route->action;

        if ($action instanceof Closure) {
            $result = call_user_func_array($action, array_merge([$request], $params));
        } else {
            [$controllerClass, $method] = $action;
            $response                   = new Response();
            $controller                 = new $controllerClass($request, $response);
            $result                     = call_user_func_array([$controller, $method], $params);
        }

        if ($result instanceof Response) {
            return $result;
        }

        return Response::json($result);
    }

    /**
     * Handle 404 Not Found response
     */
    protected function handleNotFound()
    {
        return Response::view('404', [], 404);
    }

    /**
     * Apply middleware stack to request
     * @param array $middlewares Middleware classes
     * @param Request $request HTTP request
     */
    protected function applyMiddleware(array $middlewares, Request $request)
    {
        $next = function ($request) {
            return $request;
        };

        $middlewares = array_reverse($middlewares);

        foreach ($middlewares as $middleware) {
            $class = "\\Rivulet\\Middleware\\" . ucfirst($middleware) . "Middleware";
            if (! class_exists($class)) {
                throw new Exception("Middleware {$middleware} not found");
            }
            $instance     = new $class();
            $previousNext = $next;
            $next         = function ($request) use ($instance, $previousNext) {
                return $instance->handle($request, $previousNext);
            };
        }

        return $next($request);
    }

    /**
     * Register new route
     * @param string $method HTTP method
     * @param string $uri Route URI
     * @param mixed $controllerOrAction Controller class or closure
     * @param string|null $methodName Controller method name
     */
    public function addRoute($method, $uri, $controllerOrAction, $methodName = null)
    {
        $fullUri = rtrim($this->currentGroupPrefix . '/' . ltrim($uri, '/'), '/') ?: '/';
        $pattern = '#^' . preg_replace('/\{(\w+)\}/', '([^/]+)', $fullUri) . '$#';

        if ($methodName !== null) {
            $action = [$controllerOrAction, $methodName];
        } else {
            $action = $controllerOrAction;
        }

        $route          = new Route($method, $fullUri, $action, $this->currentGroupMiddleware);
        $route->pattern = $pattern;

        $this->routes[] = $route;
    }

    /**
     * Register file serving route
     * @param string $uri Route URI
     * @param string $path File path
     */
    public function addFile($uri, $path)
    {
        $this->addRoute('GET', $uri, function (Request $request, ...$params) use ($path) {
            $filePath = str_replace('{file}', $params[0] ?? '', $path);
            $fullPath = realpath($filePath);
            if ($fullPath && file_exists($fullPath) && strpos($fullPath, realpath(dirname($path))) === 0) {
                $content = file_get_contents($fullPath);
                $mime    = mime_content_type($fullPath) ?: 'application/octet-stream';
                return new Response($content, 200, ['Content-Type' => $mime]);
            }
            return Response::json(['error' => 'File not found'], 404);
        });
    }

    /**
     * Create route group with prefix
     * @param string $prefix URI prefix
     * @param Closure $callback Route definitions
     */
    public function prefix($prefix, Closure $callback)
    {
        $previousPrefix           = $this->currentGroupPrefix;
        $this->currentGroupPrefix = rtrim($previousPrefix . '/' . trim($prefix, '/'), '/');
        call_user_func($callback);
        $this->currentGroupPrefix = $previousPrefix;
    }

    /**
     * Create route group with attributes
     * @param mixed ...$args Group attributes and callback
     */
    public function group(...$args)
    {
        $callback   = array_pop($args);
        $attributes = [];
        foreach ($args as $arg) {
            [$key, $value]    = explode('=', $arg, 2);
            $attributes[$key] = $value;
        }
        $previousPrefix     = $this->currentGroupPrefix;
        $previousMiddleware = $this->currentGroupMiddleware;
        if (isset($attributes['prefix'])) {
            $this->currentGroupPrefix = rtrim($previousPrefix . '/' . trim($attributes['prefix'], '/'), '/');
        }
        if (isset($attributes['middleware'])) {
            $newMiddleware                = explode(',', $attributes['middleware']);
            $this->currentGroupMiddleware = array_merge($previousMiddleware, $newMiddleware);
        }
        call_user_func($callback);
        $this->currentGroupPrefix     = $previousPrefix;
        $this->currentGroupMiddleware = $previousMiddleware;
    }

    /**
     * Apply middleware to route group
     * @param mixed $middleware Middleware class(es)
     * @param Closure $callback Route definitions
     */
    public function middleware($middleware, Closure $callback)
    {
        $previousMiddleware           = $this->currentGroupMiddleware;
        $newMiddleware                = is_array($middleware) ? $middleware : [$middleware];
        $this->currentGroupMiddleware = array_merge($previousMiddleware, $newMiddleware);
        call_user_func($callback);
        $this->currentGroupMiddleware = $previousMiddleware;
    }
}
