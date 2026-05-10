<?php
/**
 * @var array $stats
 * @var array $users
 * @var array $aiStats
 */
?>
<div class="page-header">
    <h1 class="page-title">Admin paneli</h1>
    <span class="text-muted"><?= date('d.m.Y H:i') ?></span>
</div>

<!-- Statistika kartochkalari -->
<div class="stats-cards">
    <div class="stat-card">
        <span class="stat-card-num"><?= $stats['students'] ?></span>
        <span class="stat-card-lbl">Talabalar</span>
    </div>
    <div class="stat-card">
        <span class="stat-card-num"><?= $stats['teachers'] ?></span>
        <span class="stat-card-lbl">O'qituvchilar</span>
    </div>
    <div class="stat-card">
        <span class="stat-card-num"><?= $stats['submissions'] ?></span>
        <span class="stat-card-lbl">Topshiriqlar</span>
    </div>
    <div class="stat-card">
        <span class="stat-card-num"><?= $stats['ai_requests'] ?></span>
        <span class="stat-card-lbl">AI so'rovlar</span>
    </div>
    <div class="stat-card">
        <span class="stat-card-num"><?= number_format($stats['total_tokens']) ?></span>
        <span class="stat-card-lbl">Jami tokenlar</span>
    </div>
</div>

<!-- AI so'rovlar jadvali -->
<?php if (!empty($aiStats)): ?>
<div class="section-block">
    <h2 class="block-title">AI faoliyati (oxirgi 30 kun)</h2>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Sana</th><th>So'rovlar</th><th>Tokenlar</th></tr></thead>
            <tbody>
                <?php foreach ($aiStats as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= $row['requests'] ?></td>
                    <td><?= number_format($row['tokens']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Foydalanuvchilar -->
<div class="section-block">
    <h2 class="block-title">Foydalanuvchilar</h2>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Ism</th><th>Email</th><th>Rol</th><th>Topshiriqlar</th><th>O'rtacha bal</th><th>Holat</th><th>Amal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr class="<?= !$u['is_active'] ? 'row-disabled' : '' ?>">
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                    <td><?= $u['submissions_count'] ?></td>
                    <td><?= round($u['avg_score'], 1) ?></td>
                    <td>
                        <span class="status-badge <?= $u['is_active'] ? 'status-active' : 'status-inactive' ?>">
                            <?= $u['is_active'] ? 'Faol' : 'Bloklangan' ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($u['role'] !== 'admin'): ?>
                        <form action="/admin/users/<?= $u['id'] ?>/toggle" method="POST" style="display:inline">
                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
                            <button type="submit" class="btn btn-ghost btn-sm btn-danger-ghost"
                                    onclick="return confirm('Ishonchingiz komilmi?')">
                                <?= $u['is_active'] ? 'Bloklash' : 'Faollashtirish' ?>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
