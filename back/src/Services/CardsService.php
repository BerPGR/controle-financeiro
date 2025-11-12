<?php

namespace Mp\Controle\Services;
use Mp\Controle\Repositories\CardsRepository;
use PDO;

class CardsService {
    public function __construct(private CardsRepository $repository){}

    public function getAllCards() {
        return $this->repository->all();
    }

    public function insertCard(array $payload) {
        $id = $this->repository->insert($payload['name'],  $payload['color']);

        return ['id' => $id, 'name' => $payload['name'], 'color'=> $payload['color']];
    }

    public function updateCard(int $id, array $payload) {
        return $this->repository->update($id, $payload['name'], $payload['color']);
    }

    public function deleteCard(int $id) {
        return $this->repository->delete($id);
    }
}