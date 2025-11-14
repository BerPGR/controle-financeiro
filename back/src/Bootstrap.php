<?php

use Dotenv\Dotenv;
use Mp\Controle\utils\CorsUtil;
use Mp\Controle\DInjection;

$dotend = Dotenv::createImmutable(__DIR__ . "/../");
$dotend->safeLoad();

$CorsUtil = new CorsUtil();
Flight::before('start', [$CorsUtil, 'handleCors']);

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

Flight::set('jwt.config', [
    'secret' => $_ENV['JWT_SECRET'],
    'iss' => $_ENV['JWT_ISS'],
    'aud' => $_ENV['JWT_AUD'],
    'ttl' => (int)$_ENV['JWT_AT_TTL']
]);

$config = Flight::get('config');
Flight::register('db', PDO::class, [
    "{$config['db']['driver']}:host={$config['db']['host']};port={$config['db']['port']};dbname={$config['db']['database']}",
    $config['db']['username'],
    $config['db']['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
]);

Flight::before('start', function () use ($config) {
    DInjection::inject($config);
});

Flight::map('error', function (Throwable $e) {
    Flight::response()->header('Access-Control-Allow-Origin', 'http://localhost:5173');
    Flight::response()->header('Access-Control-Allow-Credentials', 'true');
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
