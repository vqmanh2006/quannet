<?php

declare(strict_types=1);

class Router
{
    /** @var array<string, array<string, array{0: string, 1: string}>> */
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void
    {
        $normalizedPath = $this->normalizePath($path);
        $this->routes[$method][$normalizedPath] = $handler;
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $path = $this->extractPath($requestUri);

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 - Không tìm thấy trang yêu cầu.';
            return;
        }

        [$controllerName, $action] = $handler;

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo 'Controller not found';
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo 'Action not found';
            return;
        }

        $controller->{$action}();
    }

    private function extractPath(string $requestUri): string
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = $path === false ? '/' : $path;

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('/index.php', '', $scriptName);

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        return $this->normalizePath($path ?: '/');
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '//' ? '/' : $path;
    }
}
