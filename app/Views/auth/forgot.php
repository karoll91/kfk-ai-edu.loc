<div class="auth-card">
    <h1 class="auth-title">Parolni tiklash</h1>
    <p class="auth-sub">Email manzilingizni kiriting — ko'rsatmalar yuboramiz</p>

    <form action="/forgot-password" method="POST" class="auth-form">
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

        <button type="submit" class="btn btn-primary btn-block">Yuborish</button>
    </form>

    <p class="auth-footer">
        Parolni esladingizmi? <a href="/login">Kirish</a>
    </p>
</div>
