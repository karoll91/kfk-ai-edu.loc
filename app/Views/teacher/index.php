<?php
/**
 * @var array $pending
 * @var array $students
 */
?>
<div class="page-header">
    <h1 class="page-title">O'qituvchi paneli</h1>
    <span class="badge-count"><?= count($pending) ?> ta kutmoqda</span>
</div>

<!-- Baholash kutayotgan ishlar -->
<div class="section-block">
    <h2 class="block-title">Baholash kerak</h2>
    <?php if (empty($pending)): ?>
        <p class="empty-state">Hozircha baholash kerak bo'lgan ish yo'q.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Talaba</th>
                        <th>Mashq</th>
                        <th>Modul</th>
                        <th>AI bali</th>
                        <th>Holat</th>
                        <th>Amal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending as $sub): ?>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <span class="user-init"><?= mb_substr($sub['user_name'], 0, 1) ?></span>
                                <?= htmlspecialchars($sub['user_name']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($sub['exercise_title']) ?></td>
                        <td><?= htmlspecialchars($sub['module_name']) ?></td>
                        <td>
                            <?php if ($sub['score'] !== null): ?>
                                <span class="score-badge score-badge--<?= $sub['score'] >= 70 ? 'good' : 'low' ?>">
                                    <?= $sub['score'] ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $sub['status'] ?>">
                                <?= ['submitted'=>'Yangi','ai_reviewed'=>'AI ko\'rdi','teacher_reviewed'=>'Baholandi'][$sub['status']] ?? $sub['status'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="/teacher/grade/<?= $sub['id'] ?>" class="btn btn-primary btn-sm">Baho berish</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Talabalar ro'yxati -->
<div class="section-block">
    <h2 class="block-title">Talabalar (<?= count($students) ?>)</h2>
    <div class="students-grid">
        <?php foreach ($students as $st): ?>
        <a href="/teacher/student/<?= $st['id'] ?>" class="student-card student-card--link">
            <span class="user-init user-init--lg"><?= mb_substr($st['name'], 0, 1) ?></span>
            <div class="student-card-info">
                <div class="student-name"><?= htmlspecialchars($st['name']) ?></div>
                <div class="student-email text-muted"><?= htmlspecialchars($st['email']) ?></div>
                <div class="student-stats">
                    <span class="student-stat">📝 <?= (int)$st['submissions_count'] ?> ta</span>
                    <span class="student-stat">⭐ <?= $st['avg_score'] > 0 ? $st['avg_score'] . ' ball' : '—' ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
