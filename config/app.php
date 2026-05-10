<?php

return [
    'name'     => $_ENV['APP_NAME']     ?? 'KompMind',
    'url'      => $_ENV['APP_URL']      ?? 'http://localhost',
    'env'      => $_ENV['APP_ENV']      ?? 'production',
    'debug'    => ($_ENV['APP_DEBUG']   ?? 'false') === 'true',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Tashkent',

    'version'  => '1.0.0',

    // Session
    'session_lifetime' => 120, // daqiqa

    // Upload
    'upload_max_size' => 5 * 1024 * 1024, // 5MB
    'upload_path'     => __DIR__ . '/../public/uploads/',
];
