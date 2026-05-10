<?php
/**
 * @var array $exercise
 * @var array $submission
 */
$score   = (int)($submission['teacher_score'] ?? $submission['score'] ?? 0);
$isGood  = $score >= 70;
$feedback = $submission['ai_feedback'] ?? '';
?>

<div class="result-page">
    <div class="result-hero <?= $isGood ? 'result-hero--good' : 'result-hero--low' ?>">
        <div class="result-score-circle">
            <span class="result-score-num"><?= $score ?></span>
            <span class="result-score-max">/100</span>
        </div>
        <div class="result-hero-text">
            <h1 class="result-title">
                <?= $isGood ? '🎉 Ajoyib natija!' : '💪 Davom eting!' ?>
            </h1>
            <p class="result-sub"><?= htmlspecialchars($exercise['title']) ?></p>
        </div>
    </div>

    <!-- AI Feedback -->
    <?php if ($feedback): ?>
    <div class="feedback-section">
        <h2 class="feedback-title">🤖 AI tahlili</h2>
        <div class="feedback-body">
            <?= nl2br(htmlspecialchars($feedback)) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- O'qituvchi bahosi -->
    <?php if ($submission['teacher_feedback']): ?>
    <div class="feedback-section feedback-section--teacher">
        <h2 class="feedback-title">👨‍🏫 O'qituvchi izohi</h2>
        <div class="feedback-body"><?= nl2br(htmlspecialchars($submission['teacher_feedback'])) ?></div>
        <?php if ($submission['teacher_score'] !== null): ?>
            <span class="score-badge score-badge--<?= $submission['teacher_score'] >= 70 ? 'good' : 'low' ?>">
                O'qituvchi bali: <?= $submission['teacher_score'] ?>
            </span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Tugmalar -->
    <div class="result-actions">
        <a href="/exercises/<?= $exercise['id'] ?>" class="btn btn-ghost">Qayta ko'rish</a>
        <a href="/modules/<?= $exercise['module_id'] ?>" class="btn btn-primary">Modulga qaytish →</a>
        <a href="/dashboard" class="btn btn-ghost">Dashboard</a>
    </div>
</div>
