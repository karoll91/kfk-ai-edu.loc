<?php

declare(strict_types=1);

// ── Autoload ──────────────────────────────────────────────────
// Composer o'rnatilgan bo'lsa — undan foydalaniladi
// O'rnatilmagan bo'lsa — qo'lda autoload ishlaydi
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    // Qo'lda PSR-4 autoloader (App\ → app/)
    spl_autoload_register(function (string $class): void {
        // App\Core\Router → app/Core/Router.php
        if (str_starts_with($class, 'App\\')) {
            $relative = substr($class, 4); // 'App\' ni olib tashlash
            $file = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }
    });
}

// ── .env yuklash ──────────────────────────────────────────────
// Dotenv kutubxonasi o'rnatilgan bo'lsa — undan foydalaniladi
// O'rnatilmagan bo'lsa — oddiy parse_ini_file ishlatiladi
if (class_exists('Dotenv\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} else {
    // Minimal .env parser
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            if (!str_contains($line, '=')) continue;
            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);
            // Qo'shtirnoqlarni olib tashlash
            $value = trim($value, '"\'');
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}

// Timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Tashkent');

// Xatolarni ko'rsatish (faqat development)
if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Session boshlash
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => ($_ENV['APP_ENV'] ?? 'development') === 'production',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// Global config yordamchi funksiya
function config(string $file, string $key = ''): mixed
{
    static $cache = [];
    if (!isset($cache[$file])) {
        $path = __DIR__ . '/../config/' . $file . '.php';
        $cache[$file] = file_exists($path) ? require $path : [];
    }
    if ($key === '') return $cache[$file];
    return $cache[$file][$key] ?? null;
}

// Remember token orqali avtomatik sessiya tiklash
if (empty($_SESSION['user']) && !empty($_COOKIE['remember_token'])) {
    $rememberToken = $_COOKIE['remember_token'];
    // Faqat to'g'ri formatdagi token (64 hex belgi) bilan DBga murojaat
    if (strlen($rememberToken) === 64 && ctype_xdigit($rememberToken)) {
        $rememberedUser = \App\Core\Database::fetchOne(
            "SELECT * FROM users WHERE remember_token = ? AND is_active = 1",
            [$rememberToken]
        );
        if ($rememberedUser) {
            // Sessiya tiklanadi + token rotatsiya (yangi token cookie va DBga yoziladi)
            \App\Core\Auth::login($rememberedUser, true);
        } else {
            // Yaroqsiz token — cookie ni tozalash
            setcookie('remember_token', '', time() - 3600, '/');
        }
    }
}
