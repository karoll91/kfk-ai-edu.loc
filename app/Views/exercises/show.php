<?php
/**
 * @var array      $exercise
 * @var array|null $submission
 */
$hasSubmission = !empty($submission);
?>

<div class="page-header">
    <a href="/modules/<?= $exercise['module_id'] ?>" class="btn btn-ghost btn-sm">← Modulga qaytish</a>
</div>

<div class="exercise-layout">
    <!-- CHAPDA: Mashq matni -->
    <div class="exercise-task">
        <div class="exercise-task-header">
            <span class="exercise-module-tag"><?= htmlspecialchars($exercise['module_name'] ?? '') ?></span>
            <h1 class="page-title"><?= htmlspecialchars($exercise['title']) ?></h1>
            <p class="exercise-description"><?= nl2br(htmlspecialchars($exercise['description'])) ?></p>
        </div>

        <div class="task-instructions">
            <h3 class="task-instructions-title">Ko'rsatmalar:</h3>
            <div class="task-instructions-body">
                <?= nl2br(htmlspecialchars($exercise['instructions'])) ?>
            </div>
        </div>

        <?php if (!empty($exercise['creative_prompt'])): ?>
        <div class="creative-prompt-box">
            <span class="creative-prompt-label">🎨 Ijodiy topshiriq</span>
            <?php if (!empty($exercise['elements'])): ?>
                <?php $elements = json_decode($exercise['elements'], true) ?? []; ?>
                <div class="creative-elements">
                    <?php foreach ($elements as $el): ?>
                        <span class="creative-tag"><?= htmlspecialchars(is_string($el) ? $el : json_encode($el)) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <p class="creative-prompt-text"><?= nl2br(htmlspecialchars($exercise['creative_prompt'])) ?></p>
        </div>
        <?php endif; ?>

        <button class="btn btn-ghost btn-sm ai-trigger"
                onclick="openAiHelper()"
                title="AI dan yordam so'rash">
            🤖 AI dan yordam so'rash
        </button>
    </div>

    <!-- O'NGDA: Javob yozish -->
    <div class="exercise-answer">
        <?php if ($hasSubmission): ?>
            <div class="answer-submitted-banner">
                <span>✓ Javob topshirilgan</span>
                <?php if ($submission['score'] !== null): ?>
                    <span class="score-badge score-badge--<?= $submission['score'] >= 70 ? 'good' : 'low' ?>">
                        <?= $submission['score'] ?> / 100
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form action="/exercises/<?= $exercise['id'] ?>/submit" method="POST" id="exercise-form">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">

            <label class="form-label">Javobingiz:</label>
            <textarea name="content"
                      id="answer-text"
                      class="form-control answer-textarea"
                      rows="12"
                      placeholder="Javobingizni bu yerga yozing... (kamida 50 ta belgi)"
                      required
                      minlength="50"><?= htmlspecialchars($submission['content'] ?? '') ?></textarea>

            <div class="answer-meta">
                <span id="char-count" class="char-count">0 ta belgi</span>
                <span class="answer-hint">Kamida 50 ta belgi</span>
            </div>

            <div class="answer-actions">
                <button type="submit" class="btn btn-primary btn-block" id="submit-btn">
                    <?= $hasSubmission ? 'Qayta topshirish' : 'Topshirish va baholash →' ?>
                </button>
            </div>
        </form>

        <?php if ($hasSubmission && $submission['ai_feedback']): ?>
        <div class="ai-feedback-box">
            <h3 class="ai-feedback-title">🤖 AI baholash natijasi</h3>
            <p class="ai-feedback-text"><?= nl2br(htmlspecialchars($submission['ai_feedback'])) ?></p>
        </div>
        <?php endif; ?>

        <?php if ($hasSubmission && $submission['teacher_feedback']): ?>
        <div class="teacher-feedback-box">
            <h3 class="teacher-feedback-title">👨‍🏫 O'qituvchi bahosi</h3>
            <p><?= nl2br(htmlspecialchars($submission['teacher_feedback'])) ?></p>
            <?php if ($submission['teacher_score'] !== null): ?>
                <span class="score-badge score-badge--<?= $submission['teacher_score'] >= 70 ? 'good' : 'low' ?>">
                    O'qituvchi bali: <?= $submission['teacher_score'] ?>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/ai-helper.php'; ?>

<script>
const textarea = document.getElementById('answer-text');
const counter  = document.getElementById('char-count');
if (textarea) {
    textarea.addEventListener('input', () => {
        counter.textContent = textarea.value.length + ' ta belgi';
    });
    counter.textContent = textarea.value.length + ' ta belgi';
}

document.getElementById('exercise-form')?.addEventListener('submit', function() {
    document.getElementById('submit-btn').disabled = true;
    document.getElementById('submit-btn').textContent = 'Yuborilmoqda...';
});
</script>
