<?php

declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/config/constants.php';

spl_autoload_register(static function (string $className): void {
    $paths = [
        APP_ROOT . '/core/' . $className . '.php',
        APP_ROOT . '/app/Controllers/' . $className . '.php',
        APP_ROOT . '/app/Models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

$router = new Router();

$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/computers', [ComputerController::class, 'index']);
$router->get('/computers/create', [ComputerController::class, 'create']);
$router->post('/computers/store', [ComputerController::class, 'store']);
$router->get('/computers/edit', [ComputerController::class, 'edit']);
$router->post('/computers/update', [ComputerController::class, 'update']);
$router->post('/computers/delete', [ComputerController::class, 'delete']);

$router->get('/customers', [CustomerController::class, 'index']);
$router->get('/customers/create', [CustomerController::class, 'create']);
$router->post('/customers/store', [CustomerController::class, 'store']);
$router->get('/customers/edit', [CustomerController::class, 'edit']);
$router->post('/customers/update', [CustomerController::class, 'update']);
$router->post('/customers/delete', [CustomerController::class, 'delete']);

$router->get('/sessions', [SessionController::class, 'index']);
$router->get('/sessions/open', [SessionController::class, 'open']);
$router->post('/sessions/store', [SessionController::class, 'store']);
$router->get('/sessions/close', [SessionController::class, 'close']);
$router->post('/sessions/close', [SessionController::class, 'completeClose']);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
} catch (Throwable $throwable) {
    error_log('Unhandled application error: ' . $throwable->getMessage());
    http_response_code(500);
    echo 'Đã có lỗi xảy ra. Vui lòng thử lại sau.';
}
