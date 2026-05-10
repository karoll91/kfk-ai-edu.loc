<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class AuthMiddleware
{
    public function handle(mixed $arg = null): void
    {
        if (!Auth::check()) {
            // API so'rovlari uchun JSON javob
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (str_starts_with($uri, '/api/')) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Avtorizatsiya talab qilinadi', 'code' => 401]);
                exit;
            }
            // Oddiy sahifa uchun login ga yo'naltirish
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }
}
