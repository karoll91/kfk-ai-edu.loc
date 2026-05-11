<?php
/**
 * @var array $progress       — modul taraqqiyoti
 * @var array $submissions    — oxirgi topshiriqlar
 * @var int   $overallPercent
 */
$user = \App\Core\Auth::user();
$colors = ['teal','purple','amber','teal','purple','amber','teal','purple'];
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Salom, <?= htmlspecialchars($user['name']) ?>!</h1>
        <p class="page-sub">Umumiy taraqqiyotingiz: <strong><?= $overallPercent ?>%</strong></p>
    </div>
    <a href="/modules" class="btn btn-primary">Mashq boshlash →</a>
</div>

<!-- Umumiy progress -->
<div class="dashboard-overview">
    <div class="overview-ring">
        <svg viewBox="0 0 80 80" class="ring-svg">
            <circle cx="40" cy="40" r="34" fill="none" stroke="var(--border-color)" stroke-width="6"/>
            <circle cx="40" cy="40" r="34" fill="none" stroke="#1D9E75" stroke-width="6"
                    stroke-linecap="round"
                    stroke-dasharray="<?= round(213.63 * $overallPercent / 100, 1) ?> 213.63"
                    transform="rotate(-90 40 40)"/>
        </svg>
        <div class="ring-label">
            <span class="ring-pct"><?= $overallPercent ?>%</span>
            <span class="ring-sub">umumiy</span>
        </div>
    </div>

    <div class="overview-stats">
        <div class="stat-card">
            <span class="stat-card-num"><?= count($submissions) ?></span>
            <span class="stat-card-lbl">Bajarilgan</span>
        </div>
        <div class="stat-card">
            <span class="stat-card-num"><?= count(array_filter($progress, fn($p) => (int)$p['percent'] === 100)) ?></span>
            <span class="stat-card-lbl">Tugallangan modul</span>
        </div>
        <div class="stat-card">
            <span class="stat-card-num"><?= count(array_filter($progress, fn($p) => (int)$p['exercises_done'] > 0)) ?></span>
            <span class="stat-card-lbl">Boshlangan</span>
        </div>
    </div>
</div>

<!-- Ko'nikmalar taraqqiyoti -->
<div class="section-block">
    <h2 class="block-title">Ko'nikma darajalari</h2>
    <div class="progress-list">
        <?php foreach ($progress as $i => $p): ?>
            <?php
            $label   = $p['module_name'] . ' ' . $p['icon'];
            $percent = $p['percent'];
            $color   = $colors[$i % count($colors)];
            include __DIR__ . '/../partials/progress-bar.php';
            ?>
        <?php endforeach; ?>
        <?php if (empty($progress)): ?>
            <p class="empty-state">Hali hech qanday modul boshlanmagan. <a href="/modules">Boshlash →</a></p>
        <?php endif; ?>
    </div>
</div>

<!-- Oxirgi faoliyat -->
<?php if (!empty($submissions)): ?>
<div class="section-block">
    <h2 class="block-title">Oxirgi faoliyat</h2>
    <div class="activity-list">
        <?php foreach (array_slice($submissions, 0, 5) as $sub): ?>
        <div class="activity-item">
            <div class="activity-info">
                <span class="activity-module"><?= htmlspecialchars($sub['module_name']) ?></span>
                <span class="activity-exercise"><?= htmlspecialchars($sub['exercise_title']) ?></span>
            </div>
            <div class="activity-score">
                <?php if ($sub['score'] !== null): ?>
                    <span class="score-badge score-badge--<?= $sub['score'] >= 70 ? 'good' : 'low' ?>">
                        <?= $sub['score'] ?> ball
                    </span>
                <?php else: ?>
                    <span class="score-badge score-badge--pending">Kutmoqda</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
