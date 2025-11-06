<?php

use Flight;
use PDO;

Flight::set('config', [
    'front_origin' => 'http://localhost:5173',
    'db' => [
        'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'database' => $_ENV['DB_DATABASE'] ?? 'financias',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? 'MySqlUser270113!',
        'charset'  => 'utf8mb4',
        'timezone' => $_ENV['DB_TIMEZONE'] ?? '+00:00',
    ]
]);

Flight::before('start', function () {
    $conf = Flight::get('config');
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: ' . $conf['front_origin']);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
});

$config = Flight::get('config');
Flight::register('db', \flight\database\PdoWrapper::class, [
    "{$config['db']['driver']}:host={$config['db']['host']};port={$config['db']['port']};dbname={$config['db']['database']}",
    $config['db']['username'],
    $config['db']['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
]);

Flight::map('error', function (Throwable $e) {
    $body = [
        'error' => true,
        'message' => $e->getMessage(),
        'type' => get_class($e),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ];
    Flight::json($body, 500);
});

Flight::map('notFound', function() {
    Flight::json(['error' => true, 'message' => 'Rota não encontrada'], 400);
});

require_once __DIR__ . '/Routes.php';