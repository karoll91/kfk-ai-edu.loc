<?php
/**
 * @var array $user  — Auth::user() ma'lumotlari
 */
$roleLabels = ['student' => 'Talaba', 'teacher' => "O'qituvchi", 'admin' => 'Admin'];
$roleLabel  = $roleLabels[$user['role']] ?? $user['role'];
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Profil</h1>
        <p class="page-sub">Shaxsiy ma'lumotlar va xavfsizlik sozlamalari</p>
    </div>
</div>

<!-- Ma'lumotlar kartochkasi -->
<div class="section-block">
    <h2 class="block-title">Hisob ma'lumotlari</h2>
    <div class="profile-card">
        <div class="profile-avatar">
            <?= mb_strtoupper(mb_substr($user['name'], 0, 1)) ?>
        </div>
        <div class="profile-info">
            <div class="profile-row">
                <span class="profile-label">Ism</span>
                <span class="profile-value"><?= htmlspecialchars($user['name']) ?></span>
            </div>
            <div class="profile-row">
                <span class="profile-label">Email</span>
                <span class="profile-value"><?= htmlspecialchars($user['email']) ?></span>
            </div>
            <div class="profile-row">
                <span class="profile-label">Rol</span>
                <span class="profile-value">
                    <span class="role-badge role-badge--<?= $user['role'] ?>"><?= $roleLabel ?></span>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Parol o'zgartirish -->
<div class="section-block">
    <h2 class="block-title">Parolni o'zgartirish</h2>
    <div class="profile-form-wrap">
        <form action="/profile/password" method="POST" class="profile-form">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">

            <div class="form-group">
                <label class="form-label" for="current_password">Hozirgi parol</label>
                <div class="input-with-toggle">
                    <input type="password"
                           id="current_password"
                           name="current_password"
                           class="form-control"
                           placeholder="••••••••"
                           required>
                    <button type="button" class="input-toggle" onclick="togglePassword('current_password')">👁</button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password">Yangi parol</label>
                <div class="input-with-toggle">
                    <input type="password"
                           id="new_password"
                           name="new_password"
                           class="form-control"
                           placeholder="Kamida 8 ta belgi"
                           required minlength="8">
                    <button type="button" class="input-toggle" onclick="togglePassword('new_password')">👁</button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password_confirmation">Yangi parolni tasdiqlang</label>
                <div class="input-with-toggle">
                    <input type="password"
                           id="new_password_confirmation"
                           name="new_password_confirmation"
                           class="form-control"
                           placeholder="••••••••"
                           required minlength="8">
                    <button type="button" class="input-toggle" onclick="togglePassword('new_password_confirmation')">👁</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Parolni saqlash</button>
        </form>
    </div>
</div>
