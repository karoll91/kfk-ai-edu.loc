<?php

return [
    'api_key'    => $_ENV['CLAUDE_API_KEY']    ?? '',
    'model'      => $_ENV['CLAUDE_MODEL']      ?? 'claude-sonnet-4-20250514',
    'max_tokens' => (int)($_ENV['CLAUDE_MAX_TOKENS'] ?? 1000),
    'api_url'    => 'https://api.anthropic.com/v1/messages',
    'version'    => '2023-06-01',

    'system_prompt' => "Sen KompMind o'quv platformasining AI yordamchisisisan. "
        . "Siz kompozitsion fikrlash ko'nikmalarini rivojlantirish bo'yicha ixtisoslashgansiz. "
        . "Talabaning javobini baholashda Bloom taksonomiyasi darajalarini hisobga ol. "
        . "Faqat o'zbek tilida javob ber. "
        . "Qisqa, aniq va ragbatlantiruvchi tarzda gapir.",

    'help_prompt_template' => "Talaba quyidagi mashqni bajarishda yordam so'ramoqda:\n\n"
        . "Mashq: %s\n\n"
        . "Talabaning savoli: %s\n\n"
        . "Iltimos, to'g'ridan-to'g'ri javob bermasdan, yo'naltiruvchi savollar va maslahatlar bering.",

    'review_prompt_template' => "Quyidagi talaba ishini Bloom taksonomiyasi bo'yicha baholab ber:\n\n"
        . "Mashq: %s\n\n"
        . "Talaba javobi:\n%s\n\n"
        . "JSON formatida qaytar:\n"
        . "{\"score\": 0-100, \"feedback\": \"...\", \"strengths\": [\"...\"], \"improvements\": [\"...\"]}",
];
