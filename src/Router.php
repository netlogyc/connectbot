<?php
// src/Router.php

namespace Netlogyc\Connectbot;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        // Normalizar prefijo cuando la app cuelga de /chatbotnl/public
        $prefix = '/chatbotnl/public';
        if (str_starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix));
            if ($path === '' || $path === false) {
                $path = '/';
            }
        }

        $action = $this->routes[$method][$path] ?? null;

        if (!$action) {
            http_response_code(404);
            echo '404 - Página no encontrada';
            return;
        }

        [$controllerName, $methodName] = explode('@', $action);
        $controllerClass = 'Netlogyc\\Connectbot\\Http\\Controllers\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo 'Controlador no encontrado';
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo 'Método del controlador no encontrado';
            return;
        }

        $controller->{$methodName}();
    }
}
