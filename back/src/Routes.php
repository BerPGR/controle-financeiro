<?php

use Mp\Financias\Controllers\CardsController;

Flight::route("GET /api/cards", function ()  {
    (new CardsController(Flight::db()))->index();
});

Flight::route("POST /api/cards", function () {
    (new CardsController(Flight::db()))->insert();
});
