<?php

use Dotenv\Dotenv;
use Flight;
use Mp\Controle\DInjection;
use PDO;


$dotend = Dotenv::createImmutable(__DIR__);
$dotend->safeLoad();

Flight::set('config', [
    'app_env' => $_ENV['APP_ENV'] ?? "dev",
    'front_origin' => $_ENV['APP_FRONT_ORIGIN'] ?? 'http://localhost:5173',
    'db' => [
        'dsn' => $_ENV['DB_DSN'] ?? 'sqlite:' . __DIR__ . "/../database/banco.sqlite",
        'user' => $_ENV['user'] ?? 'root',
        'pass' => $_ENV['password'] ?? '',
    ],
]);

$config = Flight::get('config');
Flight::register('pdo', PDO::class, [
    $config['db']['dsn'],
    null,
    null,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
], function (PDO $pdo) {
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA synchronous = NORMAL');
    $pdo->exec('PRAGMA busy_timeout = 5000');
});


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
    DInjection::inject($conf);
});



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

Flight::map('notFound', function () {
    Flight::json(['error' => true, 'message' => 'Rota não encontrada'], 400);
});

require __DIR__ . '/Routes.php';
