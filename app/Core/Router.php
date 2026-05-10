<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $action, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post(string $path, string $action, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $action, $middleware);
    }

    public function put(string $path, string $action, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $action, $middleware);
    }

    public function delete(string $path, string $action, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $action, $middleware);
    }

    private function addRoute(string $method, string $path, string $action, array $middleware): void
    {
        $this->routes[] = [
            'method'     => $method,
            'path'       => $path,
            'pattern'    => $this->pathToRegex($path),
            'action'     => $action,
            'middleware' => $middleware,
        ];
    }

    private function pathToRegex(string $path): string
    {
        // {id} → (?P<id>[^/]+)
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = rtrim($uri, '/') ?: '/';

        // PUT/DELETE orqali forma yuborishni qo'llab-quvvatlash
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            if (!preg_match($route['pattern'], $uri, $matches)) continue;

            // URL parametrlarini olish
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            // Middleware ishga tushirish
            foreach ($route['middleware'] as $mw) {
                $this->runMiddleware($mw);
            }

            // Controller@method formatini ajratish
            [$controllerName, $controllerMethod] = explode('@', $route['action']);

            // Namespace qo'shish
            if (!str_contains($controllerName, '\\')) {
                $controllerName = "App\\Controllers\\{$controllerName}";
            } else {
                $controllerName = "App\\Controllers\\{$controllerName}";
            }

            if (!class_exists($controllerName)) {
                $this->abort(404, "Controller topilmadi: {$controllerName}");
                return;
            }

            $controller = new $controllerName();
            $controller->$controllerMethod($params);
            return;
        }

        $this->abort(404);
    }

    private function runMiddleware(string $name): void
    {
        $map = [
            'auth'       => \App\Middleware\AuthMiddleware::class,
            'csrf'       => \App\Middleware\CsrfMiddleware::class,
            'rate_limit' => \App\Middleware\RateLimitMiddleware::class,
            'teacher'    => \App\Middleware\RoleMiddleware::class . ':teacher',
            'admin'      => \App\Middleware\RoleMiddleware::class . ':admin',
        ];

        if (!isset($map[$name])) return;

        [$class, $arg] = array_pad(explode(':', $map[$name], 2), 2, null);
        $arg !== null ? (new $class())->handle($arg) : (new $class())->handle();
    }

    public function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $messages = [404 => 'Sahifa topilmadi', 403 => 'Ruxsat yo\'q', 500 => 'Server xatosi'];
        $text = $message ?: ($messages[$code] ?? 'Xato');

        if ($this->isApiRequest()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $text, 'code' => $code]);
        } else {
            echo "<h1>{$code}</h1><p>{$text}</p>";
        }
        exit;
    }

    private function isApiRequest(): bool
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return str_starts_with($uri, '/api/') ||
               (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }
}
