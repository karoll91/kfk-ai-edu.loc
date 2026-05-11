<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    // View render qilish
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Ma'lumotlarni o'zgaruvchilarga chiqarish
        extract($data);

        // View faylining to'liq yo'li
        $viewFile   = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View topilmadi: {$view}");
        }

        // View kontentini bufer orqali olish
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Layout bilan birlashtirish
        if ($layout && file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    // JSON javob qaytarish
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Yo'naltirish
    protected function redirect(string $url, int $status = 302): void
    {
        header("Location: {$url}", true, $status);
        exit;
    }

    // Oldingi sahifaga qaytish (faqat bir xil origin)
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $host    = $_SERVER['HTTP_HOST'] ?? '';
        $parsed  = parse_url($referer);
        $safe    = $parsed && ($parsed['host'] ?? '') === $host
            ? $referer
            : '/';
        $this->redirect($safe);
    }

    // Flash xabar qo'shish
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    // Login bo'lgan foydalanuvchi
    protected function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    // POST ma'lumotini olish (tozalab)
    protected function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    // Barcha POST/GET ma'lumotlari
    protected function all(): array
    {
        $data = array_merge($_GET, $_POST);
        return array_map(fn($v) => is_string($v) ? trim($v) : $v, $data);
    }

    // Fayl yuklash
    protected function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    // Xatolik javob
    protected function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $this->json(['error' => $message ?: 'Xato', 'code' => $code], $code);
    }
}
