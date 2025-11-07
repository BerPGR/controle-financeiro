<?php

use Mp\Controle\Controllers\CardsController;

Flight::route('GET /api/cards', function () {
    $pdo = Flight::db();
    (new CardsController($pdo))->index();
});

Flight::route('POST /api/cards', function () {
    try {
        $pdo = Flight::db();
    } catch (\Throwable $e) {
        Flight::json([
            'error' => true,
            'message' => 'Falha ao conectar no banco',
            'details' => $e->getMessage()
        ], 500);
        return;
    }
    return (new CardsController($pdo))->insert();
});