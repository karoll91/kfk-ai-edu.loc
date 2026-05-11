<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'KompMind') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/modules.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
</head>
<body class="dashboard-body">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <div class="dashboard-layout">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <?php $role = \App\Core\Auth::user()['role'] ?? 'student'; ?>
                <a href="/dashboard"  class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/dashboard') ? 'active' : '' ?>">
                    <span class="sidebar-icon">📊</span> Dashboard
                </a>
                <a href="/modules"   class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/modules') ? 'active' : '' ?>">
                    <span class="sidebar-icon">📚</span> Modullar
                </a>
                <a href="/creative"  class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/creative') ? 'active' : '' ?>">
                    <span class="sidebar-icon">🎨</span> Ijodiy vazifalar
                </a>
                <?php if (in_array($role, ['teacher', 'admin'])): ?>
                <a href="/teacher"   class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/teacher') ? 'active' : '' ?>">
                    <span class="sidebar-icon">👨‍🏫</span> O'qituvchi paneli
                </a>
                <?php endif; ?>
                <?php if ($role === 'admin'): ?>
                <a href="/admin"     class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin') ? 'active' : '' ?>">
                    <span class="sidebar-icon">⚙️</span> Admin
                </a>
                <?php endif; ?>
                <hr class="sidebar-divider">
                <a href="/profile"   class="sidebar-link">
                    <span class="sidebar-icon">👤</span> Profil
                </a>
                <form action="/logout" method="POST" style="margin:0">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
                    <button type="submit" class="sidebar-link sidebar-link--danger">
                        <span class="sidebar-icon">🚪</span> Chiqish
                    </button>
                </form>
            </nav>
        </aside>

        <div class="dashboard-content">
            <?php if (!empty($_SESSION['flash'])): ?>
                <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                    <?php require __DIR__ . '/../partials/alert.php'; ?>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/exercises.js"></script>
</body>
</html>
