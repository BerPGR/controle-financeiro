<?php

use Dotenv\Dotenv;
use Flight;
use PDO;

/**
 * CORS — envia SEMPRE, antes de qualquer saída.
 */
function send_cors_headers(): void {
    // Use a env var do compose; fallback para o front local
    $allowedOrigin = getenv('FRONT_ORIGIN') ?: 'http://localhost:5173';
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    // Se for usar credenciais (cookies/Authorization + withCredentials):
    // - NUNCA use "*"
    // - Devolva o origin exato
    if ($allowedOrigin === '*' || $origin === $allowedOrigin) {
        header("Access-Control-Allow-Origin: " . ($allowedOrigin === '*' ? '*' : $origin));
        header('Vary: Origin');
    }

    // Se não precisa de credenciais, pode deixar false
    header('Access-Control-Allow-Credentials: false');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
}

// Envia CORS para toda requisição
send_cors_headers();

// Responde rápido o preflight
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// --------- BOOTSTRAP ----------
$dotenv = Dotenv::createImmutable(dirname(__DIR__)); // .env na raiz do projeto
$dotenv->safeLoad();

Flight::set('config', [
    'front_origin' => getenv('FRONT_ORIGIN') ?: 'http://localhost:5173',
    'db' => [
        'driver'   => $_ENV['DB_DRIVER']   ?? 'mysql',
        'host'     => $_ENV['DB_HOST']     ?? '127.0.0.1',
        'port'     => $_ENV['DB_PORT']     ?? '3306',
        'database' => $_ENV['DB_DATABASE'] ?? 'financias',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? 'MySqlUser270113!',
        'charset'  => 'utf8mb4',
        'timezone' => $_ENV['DB_TIMEZONE'] ?? '+00:00',
    ],
]);

// (Opcional) garantir CORS também quando Flight trata erros / 404
Flight::map('error', function (Throwable $e) {
    send_cors_headers();
    Flight::json([
        'error'   => true,
        'message' => $e->getMessage(),
        'type'    => get_class($e),
        'file'    => $e->getFile(),
        'line'    => $e->getLine()
    ], 500);
});

Flight::map('notFound', function () {
    send_cors_headers();
    Flight::json(['error' => true, 'message' => 'Rota não encontrada'], 400);
});

// DB
$config = Flight::get('config');
Flight::register('db', PDO::class, [
    "{$config['db']['driver']}:host={$config['db']['host']};port={$config['db']['port']};dbname={$config['db']['database']}",
    $config['db']['username'],
    $config['db']['password'],
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ]
]);

require __DIR__ . '/Routes.php';