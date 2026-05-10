<?php
/**
 * @var string $label   — ko'rsatma matni
 * @var int    $percent — 0-100
 * @var string $color   — 'teal' | 'purple' | 'amber' (ixtiyoriy)
 */
$color = $color ?? 'teal';
?>
<div class="prog-item">
    <div class="prog-header">
        <span class="prog-label"><?= htmlspecialchars($label) ?></span>
        <span class="prog-val"><?= (int)$percent ?>%</span>
    </div>
    <div class="prog-bar-bg">
        <div class="prog-bar prog-bar--<?= $color ?>"
             style="width: <?= (int)$percent ?>%"
             data-width="<?= (int)$percent ?>"></div>
    </div>
</div>
