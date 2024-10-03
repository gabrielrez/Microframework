<?php

namespace Core;

use Core\Response;

class Router
{
    private static array $routes = [];
    private array $middlewares = [];

    public static function get(string $path, callable|string $handler): self
    {
        return self::addRoute('GET', $path, $handler);
    }

    public static function post(string $path, callable|string $handler): self
    {
        return self::addRoute('POST', $path, $handler);
    }

    public static function put(string $path, callable|string $handler): self
    {
        return self::addRoute('PUT', $path, $handler);
    }

    public static function delete(string $path, callable|string $handler): self
    {
        return self::addRoute('DELETE', $path, $handler);
    }

    private static function addRoute(string $method, string $path, callable|string $handler): self
    {
        $path = preg_replace('/{([^}]+)}/', '(?P<$1>[^/]+)', $path);
        $route = new self();
        self::$routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler, 'middlewares' => &$route->middlewares];
        return $route;
    }

    public function addMiddleware(string $middlewareClass): self
    {
        $this->middlewares[] = $middlewareClass;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $middlewareClass = 'App\\Middlewares\\' . $name;

        if (class_exists($middlewareClass)) {
            return $this->addMiddleware($middlewareClass);
        }

        throw new \Exception("Middleware {$name} not found.");
    }

    public static function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match('#^' . $route['path'] . '$#', $path, $matches)) {
                $handler = $route['handler'];
                $middlewares = $route['middlewares'];

                // Process middlewares
                $next = function () use ($handler, $matches) {
                    if (is_string($handler)) {
                        list($controller, $action) = explode('::', $handler);
                        $controllerClass = 'App\\Controllers\\' . $controller;

                        if (class_exists($controllerClass)) {
                            $controllerInstance = new $controllerClass();
                            if (method_exists($controllerInstance, $action)) {
                                return call_user_func_array([$controllerInstance, $action], array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
                            } else {
                                self::handleError(500, 'Method ' . $action . ' not found in ' . $controllerClass);
                            }
                        } else {
                            self::handleError(500, 'Controller ' . $controllerClass . ' not found');
                        }
                    } elseif (is_callable($handler)) {
                        return call_user_func_array($handler, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
                    }
                };

                foreach (array_reverse($middlewares) as $middlewareClass) {
                    $next = function () use ($middlewareClass, $next) {
                        $middleware = new $middlewareClass();
                        return $middleware->handle($next);
                    };
                }

                return $next();
            }
        }

        self::handleError(404, 'Route Not Found');
    }

    public static function handleError($code, $message)
    {
        (new Response())
            ->setStatusCode($code)
            ->json(['error' => $message])
            ->send();
    }
}
