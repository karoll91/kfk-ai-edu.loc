<?php

declare(strict_types=1);

/** @var \App\Core\Router $router */

// ─── Ochiq sahifalar ─────────────────────────────────────────
$router->get('/',           'HomeController@index');

// ─── Autentifikatsiya ────────────────────────────────────────
$router->get('/login',      'AuthController@loginForm');
$router->post('/login',     'AuthController@login',        ['csrf', 'rate_limit']);
$router->get('/register',   'AuthController@registerForm');
$router->post('/register',  'AuthController@register',     ['csrf']);
$router->post('/logout',    'AuthController@logout',       ['csrf']);
$router->get('/forgot-password',  'AuthController@forgotForm');
$router->post('/forgot-password', 'AuthController@forgot',      ['csrf']);

// ─── Talaba sahifalari (login kerak) ─────────────────────────
$router->get('/dashboard',         'ProgressController@dashboard',      ['auth']);
$router->get('/profile',           'ProgressController@profile',        ['auth']);
$router->post('/profile/password', 'ProgressController@updatePassword', ['auth', 'csrf']);

$router->get('/modules',             'ModuleController@index', ['auth']);
$router->get('/modules/{id}',        'ModuleController@show',  ['auth']);

$router->get('/exercises/{id}',                  'ExerciseController@show',   ['auth']);
$router->post('/exercises/{id}/submit',          'ExerciseController@submit', ['auth', 'csrf']);
$router->get('/exercises/{id}/result/{sid}',     'ExerciseController@result', ['auth']);

// ─── O'qituvchi paneli ───────────────────────────────────────
$router->get('/teacher',             'TeacherController@index',     ['auth', 'teacher']);
$router->get('/teacher/grade/{id}',  'TeacherController@gradeForm', ['auth', 'teacher']);
$router->post('/teacher/grade/{id}', 'TeacherController@grade',     ['auth', 'teacher', 'csrf']);

// ─── Admin paneli ─────────────────────────────────────────────
$router->get('/admin',               'AdminController@index',      ['auth', 'admin']);
$router->post('/admin/users/{id}/toggle', 'AdminController@toggleUser', ['auth', 'admin', 'csrf']);

// ─── REST API (JSON) ─────────────────────────────────────────
$router->post('/api/ai/help',         'API\AiApiController@help',   ['auth']);
$router->post('/api/ai/review',       'API\AiApiController@review', ['auth']);
$router->get('/api/progress/me',      'API\ProgressApiController@me', ['auth']);
