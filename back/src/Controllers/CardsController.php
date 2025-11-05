<?php

namespace Mp\Financias\Controllers;

use Flight;
use Mp\Financias\Services\CardsService;
use PDO;

class CardsController {
    public function __construct(private PDO $pdo) {}

    public function index() {
        $cardsService = new CardsService($this->pdo);
        $cards = $cardsService->getAllCards();
        Flight::json($cards, 200);
    }

    public function insert() {
        $req = Flight::request();

        $raw = $req->getBody(); 
        $payload = json_decode($raw, true);

        if (!is_array($payload) || empty($payload)) {
            $payload = $req->data ? $req->data->getData() : [];
        }

        $service = new CardsService($this->pdo);
        $createdCard = $service->insertCard($payload);

        Flight::json($createdCard,201);
    }
}