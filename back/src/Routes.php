<?php

use Mp\Controle\Controllers\CardsController;
use Mp\Controle\Controllers\EntriesController;


Flight::route('GET /api/cards', [CardsController::class, 'index']);

Flight::route('POST /api/cards', [CardsController::class, 'insert']);

Flight::route('GET /api/cards/@cardId/entries', [EntriesController::class, 'index']);

Flight::route('POST /api/cards/@cardId/entries', [EntriesController::class, 'insert']);
