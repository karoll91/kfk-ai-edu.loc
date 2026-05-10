<?php

return [
    'jwt_secret'      => $_ENV['JWT_SECRET']       ?? 'change_me',
    'jwt_expire'      => (int)($_ENV['JWT_EXPIRE_HOURS'] ?? 1),    // soat
    'jwt_refresh'     => (int)($_ENV['JWT_REFRESH_DAYS']  ?? 30),  // kun
    'bcrypt_cost'     => 12,
    'max_attempts'    => 5,      // brute-force: max urinish
    'block_minutes'   => 15,     // bloklanish muddati
];
