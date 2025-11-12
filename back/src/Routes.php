<?php

use Mp\Controle\Controllers\CardsController;
use Mp\Controle\Controllers\EntriesController;
use Mp\Controle\Controllers\IncomeController;

Flight::route('GET /api/cards', [CardsController::class, 'index']);

Flight::route('POST /api/cards', [CardsController::class, 'insert']);

Flight::route('GET /api/cards/@cardId/entries', [EntriesController::class, 'index']);

Flight::route('POST /api/cards/@cardId/entries', [EntriesController::class, 'insert']);

Flight::route('DELETE /api/entries/@id', [EntriesController::class, 'delete']);

Flight::route('PUT /api/cards/@id', [CardsController::class, 'update']);

Flight::route('DELETE /api/cards/@id', [CardsController::class, 'delete']);

Flight::route('GET /api/income', [IncomeController::class, 'index']);

Flight::route('POST /api/income', [IncomeController::class, 'insert']);