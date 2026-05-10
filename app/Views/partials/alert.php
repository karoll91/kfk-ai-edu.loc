<?php
/**
 * @var string $type    — 'success' | 'error' | 'warning' | 'info'
 * @var string $message
 */
$icons = ['success' => '✓', 'error' => '✕', 'warning' => '⚠', 'info' => 'ℹ'];
$icon  = $icons[$type ?? 'info'] ?? 'ℹ';
?>
<div class="alert alert-<?= htmlspecialchars($type ?? 'info') ?>">
    <span class="alert-icon"><?= $icon ?></span>
    <span class="alert-msg"><?= htmlspecialchars($message ?? '') ?></span>
    <button class="alert-close" onclick="this.parentElement.remove()">×</button>
</div>
