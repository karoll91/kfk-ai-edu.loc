<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'KompMind') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="auth-wrapper">
        <a href="/" class="auth-logo">Komp<span>Mind</span></a>

        <?php if (!empty($_SESSION['flash'])): ?>
            <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
                <div class="alert alert-<?= $type ?> mb-md">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $content ?>
    </div>
    <script src="/assets/js/app.js"></script>
</body>
</html>
