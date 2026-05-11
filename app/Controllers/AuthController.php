<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Validator;
use App\Models\User;
use App\Middleware\RateLimitMiddleware;

class AuthController extends Controller
{
    public function loginForm(array $params = []): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        $this->render('auth.login', ['title' => 'Kirish'], 'auth');
    }

    public function login(array $params = []): void
    {
        $v = Validator::make($_POST, [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($v->fails()) {
            $this->flash('error', $v->firstError());
            $this->redirect('/login');
            return;
        }

        $user = User::findByEmail($this->input('email'));

        if (!$user || !Auth::checkPassword($this->input('password'), $user['password_hash'])) {
            $this->flash('error', 'Email yoki parol noto\'g\'ri');
            $this->redirect('/login');
            return;
        }

        if (!$user['is_active']) {
            $this->flash('error', 'Hisobingiz faol emas');
            $this->redirect('/login');
            return;
        }

        // Muvaffaqiyatli kirish — rate limit tozalash
        RateLimitMiddleware::clear($_SERVER['REMOTE_ADDR'] ?? '', 'login');
        Auth::login($user, isset($_POST['remember']));

        $redirect = $_SESSION['redirect_after_login'] ?? '/dashboard';
        unset($_SESSION['redirect_after_login']);
        // Open redirect himoyasi: faqat bir xil origin yo'llariga ruxsat
        if (!is_string($redirect) || !str_starts_with($redirect, '/') || str_starts_with($redirect, '//')) {
            $redirect = '/dashboard';
        }
        $this->redirect($redirect);
    }

    public function registerForm(array $params = []): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        $this->render('auth.register', ['title' => 'Ro\'yxatdan o\'tish'], 'auth');
    }

    public function register(array $params = []): void
    {
        $v = Validator::make($_POST, [
            'name'                  => 'required|min:2|max:100',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        if ($v->fails()) {
            $this->flash('error', $v->firstError());
            $this->redirect('/register');
            return;
        }

        if (User::exists('email', $this->input('email'))) {
            $this->flash('error', 'Bu email allaqachon ro\'yxatdan o\'tgan');
            $this->redirect('/register');
            return;
        }

        $userId = User::create([
            'name'          => $this->input('name'),
            'email'         => $this->input('email'),
            'password_hash' => Auth::hashPassword($this->input('password')),
            'role'          => 'student',
            'email_verified_at' => date('Y-m-d H:i:s'), // Hozircha auto-verify
        ]);

        $user = User::find($userId);
        Auth::login($user);

        $this->flash('success', 'Xush kelibsiz, ' . $user['name'] . '!');
        $this->redirect('/dashboard');
    }

    public function forgotForm(array $params = []): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        $this->render('auth.forgot', ['title' => 'Parolni tiklash'], 'auth');
    }

    public function forgot(array $params = []): void
    {
        $v = Validator::make($_POST, [
            'email' => 'required|email',
        ]);

        if ($v->fails()) {
            $this->flash('error', $v->firstError());
            $this->redirect('/forgot-password');
            return;
        }

        // Xavfsizlik: email mavjud-yo'qligini oshkor etmaymiz
        $this->flash('success', 'Agar bu email ro\'yxatda bo\'lsa, ko\'rsatmalar yuborildi.');
        $this->redirect('/forgot-password');
    }

    public function logout(array $params = []): void
    {
        Auth::logout();
        $this->redirect('/');
    }
}
