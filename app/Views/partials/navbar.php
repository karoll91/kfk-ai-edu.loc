<header class="navbar">
    <div class="container navbar-inner">
        <a href="/" class="navbar-logo">Komp<span>Mind</span></a>

        <nav class="navbar-links">
            <a href="/modules" class="nav-link">Modullar</a>
            <a href="/#about"  class="nav-link">Haqida</a>
        </nav>

        <div class="navbar-actions">
            <?php if (\App\Core\Auth::check()): ?>
                <?php $user = \App\Core\Auth::user(); ?>
                <div class="user-menu">
                    <button class="user-menu-btn">
                        <span class="user-avatar"><?= mb_substr($user['name'], 0, 1) ?></span>
                        <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                        <span class="chevron">▾</span>
                    </button>
                    <div class="user-dropdown">
                        <a href="/dashboard" class="dropdown-item">📊 Dashboard</a>
                        <a href="/profile"   class="dropdown-item">👤 Profil</a>
                        <hr>
                        <form action="/logout" method="POST" style="margin:0">
                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
                            <button type="submit" class="dropdown-item dropdown-item--danger">🚪 Chiqish</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="/login"    class="btn btn-ghost">Kirish</a>
                <a href="/register" class="btn btn-primary">Boshlash</a>
            <?php endif; ?>
        </div>
    </div>
</header>
