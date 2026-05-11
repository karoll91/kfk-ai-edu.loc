<?php /** @var array $submission */ ?>

<div class="page-header">
    <a href="/teacher" class="btn btn-ghost btn-sm">← Orqaga</a>
    <div>
        <h1 class="page-title">Baho berish</h1>
        <p class="page-sub">
            <strong><?= htmlspecialchars($submission['module_name']) ?></strong>
            — <?= htmlspecialchars($submission['exercise_title']) ?>
            &nbsp;·&nbsp;
            👤 <?= htmlspecialchars($submission['user_name']) ?>
            <span class="text-muted">(<?= htmlspecialchars($submission['user_email']) ?>)</span>
        </p>
    </div>
</div>

<div class="grade-layout">
    <!-- Talaba javobi -->
    <div class="grade-submission">
        <h2 class="block-title">Talaba javobi</h2>
        <?php if ($submission['ai_feedback']): ?>
        <div class="ai-feedback-box mb-md">
            <strong>🤖 AI bali: <?= $submission['score'] ?? '—' ?></strong>
            <p><?= nl2br(htmlspecialchars($submission['ai_feedback'])) ?></p>
        </div>
        <?php endif; ?>
        <div class="submission-content">
            <?= nl2br(htmlspecialchars($submission['content'])) ?>
        </div>
    </div>

    <!-- Baho shakli -->
    <div class="grade-form-wrap">
        <h2 class="block-title">O'qituvchi bahosi</h2>
        <form action="/teacher/grade/<?= $submission['id'] ?>" method="POST" class="grade-form">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">

            <div class="form-group">
                <label class="form-label">Ball (0 — 100)</label>
                <div class="score-input-wrap">
                    <input type="range" name="teacher_score" id="score-range"
                           min="0" max="100"
                           value="<?= $submission['teacher_score'] ?? $submission['score'] ?? 70 ?>"
                           oninput="document.getElementById('score-display').textContent=this.value">
                    <span class="score-display" id="score-display"><?= $submission['teacher_score'] ?? $submission['score'] ?? 70 ?></span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Izoh va tavsiyalar</label>
                <textarea name="teacher_feedback"
                          class="form-control"
                          rows="6"
                          placeholder="Talabaga izoh va tavsiyalar yozing..."
                          required
                          minlength="10"><?= htmlspecialchars($submission['teacher_feedback'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Bahoni saqlash</button>
        </form>
    </div>
</div>
