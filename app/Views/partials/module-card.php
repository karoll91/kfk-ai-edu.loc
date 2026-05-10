<?php
/**
 * @var array  $module   — modul ma'lumotlari
 * @var array  $progress — foydalanuvchi taraqqiyoti (ixtiyoriy)
 */
$pct  = $progress['percent'] ?? 0;
$done = $progress['exercises_done'] ?? 0;
$total= $module['exercise_count'] ?? 2;
?>
<div class="module-card">
    <?php if ($module['badge']): ?>
        <span class="module-badge module-badge--<?= $module['badge'] ?>">
            <?= $module['badge'] === 'new' ? 'Yangi' : 'Mashhur' ?>
        </span>
    <?php endif; ?>

    <div class="module-icon"><?= $module['icon'] ?></div>
    <h3 class="module-name"><?= htmlspecialchars($module['name']) ?></h3>
    <p class="module-desc"><?= htmlspecialchars($module['description'] ?? '') ?></p>

    <div class="module-meta">
        <span class="module-count"><?= $total ?> ta mashq</span>
        <?php if ($done > 0): ?>
            <span class="module-done"><?= $done ?>/<?= $total ?> bajarildi</span>
        <?php endif; ?>
    </div>

    <?php if ($pct > 0): ?>
        <div class="progress-bar-wrap">
            <div class="progress-bar" style="width: <?= $pct ?>%"></div>
        </div>
        <span class="progress-label"><?= $pct ?>%</span>
    <?php endif; ?>

    <a href="/modules/<?= $module['id'] ?>" class="btn btn-outline btn-sm mt-sm">Boshlash →</a>
</div>
