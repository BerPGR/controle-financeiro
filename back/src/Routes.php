<?php

use Mp\Controle\Controllers\CardsController;
use Mp\Controle\Controllers\EntriesController;
use Mp\Controle\Middlewares\AuthMiddleware;

$app = Flight::app();

Flight::group('/api', function () {
    Flight::route('GET /cards', [CardsController::class, 'index']);
    
    Flight::route('POST /cards', [CardsController::class, 'insert']);
    
    Flight::route('GET /cards/@cardId/entries', [EntriesController::class, 'index']);
    
    Flight::route('POST /cards/@cardId/entries', [EntriesController::class, 'insert']);
    
    Flight::route('DELETE /entries/@id', [EntriesController::class, 'delete']);
    
    Flight::route('PUT /cards/@id', [CardsController::class, 'update']);
    
    Flight::route('DELETE /cards/@id', [CardsController::class, 'delete']);
    
    Flight::route('GET /entries/incomes', [EntriesController::class, 'allMonthlyIncomes']);
    
    Flight::route('GET /cards/summary', [CardsController::class, 'summary']);
}, [new AuthMiddleware($app)]);
