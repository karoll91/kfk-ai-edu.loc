<?php
/**
 * @var array $modules
 * @var array $progress — [module_id => progress_row]
 */
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Modullar</h1>
        <p class="page-sub">8 ta ko'nikma moduli — har birida 2 ta amaliy mashq</p>
    </div>
</div>

<div class="modules-grid">
    <?php foreach ($modules as $module): ?>
        <?php $progress = $progress[$module['id']] ?? []; ?>
        <?php include __DIR__ . '/../partials/module-card.php'; ?>
    <?php endforeach; ?>
</div>
