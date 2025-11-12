<?php

namespace Mp\Controle\Controllers;

use Flight;
use Mp\Controle\Services\IncomeService;

class IncomeController
{
    public function __construct(private IncomeService $service) {}

    public function index()
    {
        try {
            $data = $this->service->monthlyIncomeGet();
            Flight::json($data, 200);
        } catch (\Throwable $e) {
            Flight::json(['status' => 500, 'message' => 'Não foi possivel buscar rendimento mensal: ' . $e->getMessage()], 500);
        }
    }

    public function insert()
    {
        try {
            $req = Flight::request();

            $payload = $req->data->getData();

            if (!is_array($payload) || empty($payload)) {
                Flight::json(['status' => 500, 'message' => 'Não há dados a serem inseridos!'], 500);
            }

            $data = $this->service->insertIncome($payload);

            Flight::json($data, 200);
        } catch (\Throwable $e) {
            Flight::json(['status' => 500, 'message' => 'Não foi possível inserir rendimento: ' . $e->getMessage()], 500);
        }
    }
}
