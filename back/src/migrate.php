<?php

$sql = "
CREATE DATABASE IF NOT EXISTS financias;

USE financias;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    color VARCHAR(20) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    card_id INT NOT NULL,
    kind ENUM('EXPENSE','INVESTMENT') NOT NULL,
    description VARCHAR(255) NULL,
    amount DECIMAL(12,2) NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_entries_card FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE CASCADE 
);
";

// Como o Flight::register('db', PDO::class, ...) ainda não foi injetado (está no before('start')),
// precisamos acessar a instância do PDO diretamente ou replicar a conexão.

try {
    // Acessa as configurações de DB
    $config = Flight::get('config')['db'];

    // Cria a string DSN (Data Source Name)
    $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']}";

    // Cria a conexão PDO
    $pdo = new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Executa os comandos SQL
    $pdo->exec($sql);
} catch (\PDOException $e) {
    // Se o banco de dados não estiver pronto ou as credenciais estiverem erradas.
    // Lembre-se: o host é 'db' e a porta é '3306' de dentro do contêiner.
    die("❌ Erro ao criar tabelas: " . $e->getMessage() . "\n");
} catch (\Throwable $e) {
    die("❌ Erro de inicialização: " . $e->getMessage() . "\n");
}
