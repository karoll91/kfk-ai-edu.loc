'use strict';

// Parol ko'rsatish/yashirish
function togglePassword(id) {
    const input = document.getElementById(id);
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
}

// Flash xabarlarni 5 soniyadan keyin o'chirish
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => alert.remove(), 5000);
});

// AI helper modal
function openAiHelper() {
    document.getElementById('ai-helper')?.style.setProperty('display', 'block');
    document.getElementById('ai-overlay')?.style.setProperty('display', 'block');
    document.getElementById('ai-question')?.focus();
}

function closeAiHelper() {
    document.getElementById('ai-helper')?.style.setProperty('display', 'none');
    document.getElementById('ai-overlay')?.style.setProperty('display', 'none');
}

async function askAi(exerciseId) {
    const question = document.getElementById('ai-question')?.value?.trim();
    if (!question) return;

    const loading  = document.getElementById('ai-loading');
    const response = document.getElementById('ai-response');
    const respText = document.getElementById('ai-response-text');

    loading?.style.setProperty('display', 'flex');
    response?.style.setProperty('display', 'none');

    try {
        const fd = new FormData();
        fd.append('exercise_id', exerciseId);
        fd.append('question', question);

        const res  = await fetch('/api/ai/help', { method: 'POST', body: fd });
        const data = await res.json();

        loading?.style.setProperty('display', 'none');
        if (data.text) {
            respText.textContent = data.text;
            response?.style.setProperty('display', 'block');
        }
    } catch {
        loading?.style.setProperty('display', 'none');
        if (respText) respText.textContent = 'Xato yuz berdi. Qaytadan urinib ko\'ring.';
        response?.style.setProperty('display', 'block');
    }
}

// Progress bar animatsiya (sahifa yuklanganda)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.prog-bar[data-width]').forEach(bar => {
        bar.style.width = '0';
        requestAnimationFrame(() => {
            setTimeout(() => {
                bar.style.width = bar.dataset.width + '%';
            }, 200);
        });
    });
});
