<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class RoleMiddleware
{
    public function handle(mixed $requiredRole = null): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $userRole = Auth::user()['role'] ?? '';

        $allowed = match ($requiredRole) {
            'admin'   => ['admin'],
            'teacher' => ['admin', 'teacher'],
            'student' => ['admin', 'teacher', 'student'],
            default   => [],
        };

        if (!in_array($userRole, $allowed, true)) {
            http_response_code(403);
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (str_starts_with($uri, '/api/')) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Bu sahifaga kirishga ruxsat yo\'q', 'code' => 403]);
            } else {
                $_SESSION['flash']['error'] = 'Bu sahifaga kirishga ruxsat yo\'q.';
                header('Location: /dashboard');
            }
            exit;
        }
    }
}
