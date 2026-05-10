<div class="auth-card">
    <h1 class="auth-title">Tizimga kirish</h1>
    <p class="auth-sub">KompMind platformasiga xush kelibsiz</p>

    <form action="/login" method="POST" class="auth-form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">

        <div class="form-group">
            <label class="form-label" for="email">Email manzil</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   placeholder="example@mail.uz"
                   required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">
                Parol
                <a href="/forgot-password" class="form-label-link">Parolni unutdingizmi?</a>
            </label>
            <div class="input-with-toggle">
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       placeholder="••••••••"
                       required>
                <button type="button" class="input-toggle" onclick="togglePassword('password')">👁</button>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" id="remember" name="remember" class="check-input">
            <label for="remember" class="check-label">30 kun eslab qol</label>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Kirish</button>
    </form>

    <p class="auth-footer">
        Hisobingiz yo'qmi? <a href="/register">Ro'yxatdan o'ting</a>
    </p>
</div>
