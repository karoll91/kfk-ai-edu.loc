'use strict';

// Belgilar sanagichi
const textarea = document.getElementById('answer-text');
const counter  = document.getElementById('char-count');
const submitBtn = document.getElementById('submit-btn');

if (textarea && counter) {
    function updateCount() {
        const len = textarea.value.length;
        counter.textContent = len + ' ta belgi';
        counter.style.color = len < 50 ? 'var(--red)' : 'var(--text-hint)';
        if (submitBtn) submitBtn.disabled = len < 50;
    }
    textarea.addEventListener('input', updateCount);
    updateCount();
}

// Shakl yuborishda tugmani faolsizlantirish
document.getElementById('exercise-form')?.addEventListener('submit', function () {
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Baholanmoqda...';
    }
});
