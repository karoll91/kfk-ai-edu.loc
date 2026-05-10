<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Database;

class RateLimitMiddleware
{
    public function handle(mixed $action = null): void
    {
        $action ??= 'login';
        $ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $maxAttempts = config('auth', 'max_attempts') ?? 5;
        $blockMins   = config('auth', 'block_minutes') ?? 15;

        $row = Database::fetchOne(
            "SELECT * FROM rate_limits WHERE ip_address = ? AND action = ?",
            [$ip, $action]
        );

        if ($row) {
            // Bloklash muddati tekshirish
            if ($row['blocked_until'] && strtotime($row['blocked_until']) > time()) {
                $remaining = ceil((strtotime($row['blocked_until']) - time()) / 60);
                http_response_code(429);
                $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                if (str_starts_with($uri, '/api/')) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => "Juda ko'p urinish. {$remaining} daqiqadan so'ng qayta urinib ko'ring.",
                        'code'  => 429,
                    ]);
                } else {
                    $_SESSION['flash']['error'] = "Juda ko'p urinish. {$remaining} daqiqadan so'ng qayta urinib ko'ring.";
                    header('Location: /login');
                }
                exit;
            }

            // Urinishlar sonini oshirish
            $newAttempts = $row['attempts'] + 1;
            $blockedUntil = null;

            if ($newAttempts >= $maxAttempts) {
                $blockedUntil = date('Y-m-d H:i:s', time() + ($blockMins * 60));
            }

            Database::query(
                "UPDATE rate_limits SET attempts = ?, blocked_until = ?, last_attempt = NOW() WHERE ip_address = ? AND action = ?",
                [$newAttempts, $blockedUntil, $ip, $action]
            );
        } else {
            Database::query(
                "INSERT INTO rate_limits (ip_address, action, attempts) VALUES (?, ?, 1)",
                [$ip, $action]
            );
        }
    }

    // Muvaffaqiyatli kirishdan keyin tozalash
    public static function clear(string $ip, string $action = 'login'): void
    {
        Database::query(
            "DELETE FROM rate_limits WHERE ip_address = ? AND action = ?",
            [$ip, $action]
        );
    }
}
