<?php

namespace Mp\Controle\Services;
use Mp\Controle\Repositories\CardsRepository;
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

    public function updateCard(int $id, array $payload) {
        $cardRepository = new CardsRepository($this->pdo);
        return $cardRepository->update($id, $payload['name'], $payload['color']);
    }
}