<?php

namespace Mp\Financias\Services;
use Mp\Financias\Repositories\CardsRepository;
use PDO;

class CardsService {
    public function __construct(private PDO $pdo){}

    public function getAllCards() {
        $cardsRepository = new CardsRepository($this->pdo);
        return $cardsRepository->all();
    }

    public function insertCard(array $payload) {
        $cardsRepository = new CardsRepository($this->pdo);
        $id = $cardsRepository->insert($payload['name'],  $payload['color']);

        return ['id' => $id, 'name' => $payload['name'], 'color'=> $payload['color']];
    }
}