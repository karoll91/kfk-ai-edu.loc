<div class="auth-card">
    <h1 class="auth-title">Ro'yxatdan o'tish</h1>
    <p class="auth-sub">Bepul hisob yarating va o'rganishni boshlang</p>

    <form action="/register" method="POST" class="auth-form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">

        <div class="form-group">
            <label class="form-label" for="name">Ism va familiya</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                   placeholder="Shohruh Mahkamov"
                   required autofocus minlength="2">
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email manzil</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   placeholder="example@mail.uz"
                   required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Parol</label>
            <div class="input-with-toggle">
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       placeholder="Kamida 8 ta belgi"
                       required minlength="8">
                <button type="button" class="input-toggle" onclick="togglePassword('password')">👁</button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Parolni tasdiqlang</label>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Parolni qayta kiriting"
                   required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Ro'yxatdan o'tish</button>
    </form>

    <p class="auth-footer">
        Hisobingiz bormi? <a href="/login">Kirish</a>
    </p>
</div>
