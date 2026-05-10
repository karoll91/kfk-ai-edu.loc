<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class CsrfMiddleware
{
    public function handle(mixed $arg = null): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

        if (!Auth::verifyCsrf($token)) {
            http_response_code(419);
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (str_starts_with($uri, '/api/')) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'CSRF token yaroqsiz', 'code' => 419]);
            } else {
                echo '<h1>419 — So\'rov muddati tugagan</h1><p>Sahifani yangilang va qayta urinib ko\'ring.</p>';
            }
            exit;
        }

        // Tokenni yangilash (token rotation)
        unset($_SESSION['csrf_token']);
    }
}
