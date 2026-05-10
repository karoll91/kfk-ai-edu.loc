<?php

declare(strict_types=1);

namespace App\Core;

// JWT kutubxonasi mavjud bo'lsa ishlatiladi, yo'qsa sessiya bilan ishlaydi

class Auth
{
    private static string $secret;
    private static int $expireHours;

    private static function init(): void
    {
        self::$secret      = config('auth', 'jwt_secret');
        self::$expireHours = config('auth', 'jwt_expire') ?? 1;
    }

    // Token yaratish (JWT kutubxonasi bor bo'lsa — JWT, yo'qsa — oddiy HMAC token)
    public static function createToken(array $user): string
    {
        self::init();
        $payload = [
            'iss'  => config('app', 'url'),
            'iat'  => time(),
            'exp'  => time() + (self::$expireHours * 3600),
            'sub'  => $user['id'],
            'user' => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ],
        ];

        if (class_exists('Firebase\JWT\JWT')) {
            return \Firebase\JWT\JWT::encode($payload, self::$secret, 'HS256');
        }

        // Oddiy base64 token (JWT o'rniga)
        $data      = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $data, self::$secret);
        return $data . '.' . $signature;
    }

    // Tokenni tekshirish
    public static function verifyToken(string $token): ?array
    {
        self::init();

        if (class_exists('Firebase\JWT\JWT')) {
            try {
                $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(self::$secret, 'HS256'));
                return (array) $decoded->user;
            } catch (\Exception $e) {
                return null;
            }
        }

        // Oddiy token tekshirish
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) return null;
        [$data, $sig] = $parts;
        if (!hash_equals(hash_hmac('sha256', $data, self::$secret), $sig)) return null;
        $payload = json_decode(base64_decode($data), true);
        if (!$payload || ($payload['exp'] ?? 0) < time()) return null;
        return $payload['user'] ?? null;
    }

    // Sessiyaga foydalanuvchi saqlash
    public static function login(array $user, bool $remember = false): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            // DB ga remember_token saqlash
            Database::query(
                "UPDATE users SET remember_token = ? WHERE id = ?",
                [$token, $user['id']]
            );
            setcookie('remember_token', $token, [
                'expires'  => time() + (config('auth', 'jwt_refresh') * 86400),
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Strict',
                'secure'   => config('app', 'env') === 'production',
            ]);
        }
    }

    // Chiqish
    public static function logout(): void
    {
        if (isset($_SESSION['user'])) {
            // Remember token ni tozalash
            Database::query(
                "UPDATE users SET remember_token = NULL WHERE id = ?",
                [$_SESSION['user']['id']]
            );
        }
        $_SESSION = [];
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
    }

    // Login bo'lganmi?
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    // Joriy foydalanuvchi
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    // Rol tekshirish
    public static function hasRole(string $role): bool
    {
        return (self::user()['role'] ?? '') === $role;
    }

    // Parolni hash qilish
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => config('auth', 'bcrypt_cost') ?? 12
        ]);
    }

    // Parolni tekshirish
    public static function checkPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    // CSRF token yaratish
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // CSRF tokenni tekshirish
    public static function verifyCsrf(string $token): bool
    {
        return isset($_SESSION['csrf_token']) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }
}
