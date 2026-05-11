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
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $message): ?>
            <?php require __DIR__ . '/../partials/alert.php'; ?>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <main class="main-content">
        <?= $content ?>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="/assets/js/app.js"></script>
</body>
</html>
