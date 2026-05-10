<?php
/**
 * @var array $module
 * @var array $exercises
 * @var array $submitted — [exercise_id => submission]
 */
$bloomLabels = [1=>'Bilish',2=>'Tushunish',3=>'Qo\'llash',4=>'Tahlil',5=>'Baholash',6=>'Yaratish'];
?>

<div class="page-header">
    <a href="/modules" class="btn btn-ghost btn-sm">← Modullar</a>
</div>

<div class="module-hero">
    <div class="module-hero-icon"><?= $module['icon'] ?></div>
    <div>
        <h1 class="page-title"><?= htmlspecialchars($module['name']) ?></h1>
        <p class="page-sub"><?= htmlspecialchars($module['description'] ?? '') ?></p>
        <span class="bloom-badge">
            Bloom darajasi: <?= $bloomLabels[$module['bloom_level']] ?? '' ?>
        </span>
    </div>
</div>

<div class="exercises-list">
    <h2 class="block-title">Mashqlar</h2>

    <?php foreach ($exercises as $i => $ex): ?>
        <?php $sub = $submitted[$ex['id']] ?? null; ?>
        <div class="exercise-card <?= $sub ? 'exercise-card--done' : '' ?>">
            <div class="exercise-num"><?= $i + 1 ?></div>
            <div class="exercise-info">
                <h3 class="exercise-title"><?= htmlspecialchars($ex['title']) ?></h3>
                <p class="exercise-desc"><?= htmlspecialchars($ex['description']) ?></p>
            </div>
            <div class="exercise-action">
                <?php if ($sub): ?>
                    <span class="score-badge score-badge--<?= ($sub['score'] ?? 0) >= 70 ? 'good' : 'low' ?>">
                        <?= $sub['score'] ?? '—' ?> ball
                    </span>
                    <a href="/exercises/<?= $ex['id'] ?>" class="btn btn-ghost btn-sm">Qayta ko'rish</a>
                <?php else: ?>
                    <a href="/exercises/<?= $ex['id'] ?>" class="btn btn-primary btn-sm">Boshlash →</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
