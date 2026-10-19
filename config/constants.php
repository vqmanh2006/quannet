<?php

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('BASE_URL', rtrim($_ENV['BASE_URL'] ?? '', '/'));
define('APP_NAME', 'QuanNet Management');
