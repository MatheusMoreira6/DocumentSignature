<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, string $action, bool $protected = false): void
    {
        $this->routes[$method][$path] = [
            'action' => $action,
            'protected' => $protected,
        ];
    }

    public function get(string $path, string $action, bool $protected = false): void
    {
        $this->add('GET', $path, $action, $protected);
    }

    public function post(string $path, string $action, bool $protected = false): void
    {
        $this->add('POST', $path, $action, $protected);
    }

    public function dispatch(): void
    {
        $route = $this->resolveRoute();

        $this->handleAuth($route);

        [$controller, $method] = $this->resolveAction($route['action']);

        $this->callController($controller, $method);
    }

    private function resolveRoute(): array
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';

        if (!isset($this->routes[$method][$path])) {
            $this->abort(404, "Page Not Found");
        }

        return $this->routes[$method][$path];
    }

    private function handleAuth(array $route): void
    {
        if ($route['protected'] && !isset($_SESSION['user_id'])) {
            $this->abort(401, "Unauthorized Access");
        }
    }

    private function resolveAction(string $action): array
    {
        if (!str_contains($action, '@')) {
            $this->abort(500, "Invalid route action format");
        }

        [$class, $method] = explode('@', $action);

        return [$class, $method];
    }

    private function callController(string $class, string $method): void
    {
        $class = "App\\Controllers\\" . $class;

        if (!class_exists($class)) {
            $this->abort(404, "Controller Not Found");
        }

        $controller = new $class();

        if (!method_exists($controller, $method)) {
            $this->abort(404, "Method Not Found");
        }

        $controller->$method();
    }

    private function abort(int $status, string $message = ""): void
    {
        http_response_code($status);
        echo $message;
        exit;
    }
}
