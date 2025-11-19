<?php

namespace Mp\Controle\Controllers;

use Flight;
use Mp\Controle\Services\CardsService;

class CardsController
{
    public function __construct(private CardsService $service) {}

    public function index(int $id)
    {
        try {
            $cards = $this->service->getAllCards($id);
            Flight::json($cards, 200);
        } catch (\Throwable $e) {
            Flight::json($e->getMessage(), 500);
        }
    }

    public function insert()
    {
        try {
            $req = Flight::request();

            $raw = $req->getBody();
            $payload = json_decode($raw, true);

            if (!is_array($payload) || empty($payload)) {
                $payload = $req->data ? $req->data->getData() : [];
            }

            $createdCard = $this->service->insertCard($payload);

            Flight::json($createdCard, 201);
        } catch (\Throwable $e) {
            return Flight::json(['error' => 500, 'message' => "Erro ao inserir card: {$e->getMessage()}"], 500);
        }
    }

    public function update(int $id)
    {
        try {
            $req = Flight::request();
            $payload = $req->data->getData();

            $data = $this->service->updateCard($id, $payload);

            Flight::json($data, 204);
        } catch (\Throwable $e) {
            return Flight::json($e->getMessage(), 500);
        }
    }

    public function delete(int $id) {
        try {
            $data = $this->service->deleteCard($id);
            Flight::json($data,204);
        } catch (\Throwable $e) {
            Flight::json(['status' => 500, 'message' => 'Erro ao deletar card: ' . $e->getMessage()], 500);
        }
    }

    public function summary() {
        try {
            $data = $this->service->summary();
            Flight::json($data, 200);
        } catch (\Throwable $e) {
            Flight::json('Não foi possível buscar o sumário dos dados: ' . $e->getMessage(), 500);
        }
    }
}
