<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$router = require APP_PATH . '/routes.php';
$router->dispatch();
