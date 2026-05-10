<?php
/**
 * @var int    $exerciseId
 * @var string $exerciseTitle
 */
?>
<div class="ai-helper-modal" id="ai-helper" style="display:none">
    <div class="ai-helper-inner">
        <div class="ai-helper-header">
            <span class="ai-badge">🤖 AI Yordamchi</span>
            <button class="ai-close" onclick="closeAiHelper()">×</button>
        </div>
        <div class="ai-helper-body">
            <p class="ai-helper-hint">Savolingizni yozing — AI to'g'ridan-to'g'ri javob bermaydi, yo'naltirib beradi.</p>
            <textarea id="ai-question"
                      class="form-control"
                      rows="3"
                      placeholder="Masalan: Platformani tahlil qilishni qayerdan boshlasam bo'ladi?"></textarea>
            <button class="btn btn-primary mt-sm" onclick="askAi(<?= (int)$exerciseId ?>)">
                So'rash →
            </button>
        </div>
        <div id="ai-response" class="ai-response" style="display:none">
            <div class="ai-response-label">AI javobi:</div>
            <div id="ai-response-text" class="ai-response-text"></div>
        </div>
        <div id="ai-loading" class="ai-loading" style="display:none">
            <span class="spinner"></span> AI o'ylayapti...
        </div>
    </div>
</div>
<div class="ai-helper-overlay" id="ai-overlay" onclick="closeAiHelper()" style="display:none"></div>
