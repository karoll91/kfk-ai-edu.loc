<?php
/**
 * @var array $modules
 */
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Modullar boshqaruvi</h1>
        <p class="page-sub">Jami: <?= count($modules) ?> ta modul</p>
    </div>
    <a href="/admin" class="btn btn-ghost btn-sm">← Admin paneli</a>
</div>

<div class="section-block">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nomi</th>
                    <th>Mashqlar</th>
                    <th>Holat</th>
                    <th>Amal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $m): ?>
                <tr class="<?= !$m['is_active'] ? 'row-disabled' : '' ?>">
                    <td class="text-muted"><?= (int)$m['sort_order'] ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= (int)$m['exercise_count'] ?> ta</td>
                    <td>
                        <span class="status-badge <?= $m['is_active'] ? 'status-active' : 'status-inactive' ?>">
                            <?= $m['is_active'] ? 'Faol' : 'Nofaol' ?>
                        </span>
                    </td>
                    <td>
                        <form action="/admin/modules/<?= $m['id'] ?>/toggle" method="POST" style="display:inline">
                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
                            <button type="submit" class="btn btn-ghost btn-sm <?= $m['is_active'] ? 'btn-danger-ghost' : '' ?>"
                                    onclick="return confirm('Ishonchingiz komilmi?')">
                                <?= $m['is_active'] ? 'O\'chirish' : 'Faollashtirish' ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
