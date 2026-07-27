<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        require_once CONFIG_PATH . '/routes.php';
    }

    public function add(string $method, string $uri, string $controller, string $action): void
    {
        $this->routes[] = compact('method', 'uri', 'controller', 'action');
    }

    public function dispatch(): void
    {
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $basePath    = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $requestUri  = '/' . ltrim(substr($requestUri, strlen($basePath)), '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
                $controllerClass = 'App\\Controllers\\' . $route['controller'];
                if (!class_exists($controllerClass)) {
                    $this->abort(500, 'Controller not found');
                    return;
                }
                $controller = new $controllerClass();
                $action     = $route['action'];
                $controller->$action();
                return;
            }
        }

        $this->abort(404, 'Page Not Found');
    }

    private function abort(int $code, string $message): void
    {
        http_response_code($code);
        echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        exit;
    }
}
