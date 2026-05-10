<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$router = new \App\Core\Router();

require_once __DIR__ . '/../app/routes.php';

$router->dispatch();
