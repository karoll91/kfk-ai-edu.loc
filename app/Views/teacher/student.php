<?php
/**
 * @var array  $student     — foydalanuvchi ma'lumotlari
 * @var array  $submissions — talabaning barcha topshiriqlari
 */
$totalDone   = count($submissions);
$avgScore    = $totalDone
    ? round(array_sum(array_map(fn($s) => (float)($s['teacher_score'] ?? $s['score'] ?? 0), $submissions)) / $totalDone, 1)
    : 0;
?>

<div class="page-header">
    <a href="/teacher" class="btn btn-ghost btn-sm">← Orqaga</a>
    <div>
        <h1 class="page-title"><?= htmlspecialchars($student['name']) ?></h1>
        <p class="page-sub"><?= htmlspecialchars($student['email']) ?></p>
    </div>
</div>

<!-- Statistika -->
<div class="student-overview">
    <div class="stat-card">
        <span class="stat-card-num"><?= $totalDone ?></span>
        <span class="stat-card-lbl">Jami topshiriq</span>
    </div>
    <div class="stat-card">
        <span class="stat-card-num"><?= count(array_filter($submissions, fn($s) => $s['status'] === 'teacher_reviewed')) ?></span>
        <span class="stat-card-lbl">Baholangan</span>
    </div>
    <div class="stat-card">
        <span class="stat-card-num"><?= count(array_filter($submissions, fn($s) => $s['status'] === 'ai_reviewed')) ?></span>
        <span class="stat-card-lbl">AI ko'rdi</span>
    </div>
    <div class="stat-card">
        <span class="stat-card-num"><?= $avgScore > 0 ? $avgScore : '—' ?></span>
        <span class="stat-card-lbl">O'rtacha ball</span>
    </div>
</div>

<!-- Barcha topshiriqlar -->
<div class="section-block">
    <h2 class="block-title">Barcha topshiriqlar</h2>
    <?php if (empty($submissions)): ?>
        <p class="empty-state">Bu talaba hali hech qanday topshiriq yuborмagan.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Modul</th>
                        <th>Mashq</th>
                        <th>AI bali</th>
                        <th>O'qituvchi bali</th>
                        <th>Holat</th>
                        <th>Sana</th>
                        <th>Amal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $sub): ?>
                    <tr>
                        <td><?= htmlspecialchars($sub['module_name']) ?></td>
                        <td><?= htmlspecialchars($sub['exercise_title']) ?></td>
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
                            <?php if ($sub['teacher_score'] !== null): ?>
                                <span class="score-badge score-badge--<?= $sub['teacher_score'] >= 70 ? 'good' : 'low' ?>">
                                    <?= $sub['teacher_score'] ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $sub['status'] ?>">
                                <?= ['submitted' => 'Yangi', 'ai_reviewed' => "AI ko'rdi", 'teacher_reviewed' => 'Baholandi'][$sub['status']] ?? $sub['status'] ?>
                            </span>
                        </td>
                        <td class="text-muted" style="white-space:nowrap">
                            <?= $sub['submitted_at'] ? date('d.m.Y', strtotime($sub['submitted_at'])) : '—' ?>
                        </td>
                        <td>
                            <a href="/teacher/grade/<?= $sub['id'] ?>" class="btn btn-primary btn-sm">
                                <?= $sub['status'] === 'teacher_reviewed' ? 'Qayta baho' : 'Baho berish' ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
