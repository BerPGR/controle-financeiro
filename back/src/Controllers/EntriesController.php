<?php

namespace Mp\Controle\Controllers;

use Flight;
use Mp\Controle\Services\EntriesService;

class EntriesController {
    public function __construct(private EntriesService $service) {}

    public function index($cardId) {
        try {
            $entries = $this->service->getAllEntriesFromCard($cardId);
            Flight::json($entries, 200);
        } catch (\Throwable $e) {
            Flight::json(["error"=> $e->getMessage(), 'status' => 500], 500);
        }
    }

    public function insert($cardId) {
        try {
            $req = Flight::request();

            $raw = $req->getBody();
            $payload = json_decode($raw, true);

            if (!is_array($payload) || empty($payload)) {
                $payload = $req->data ? $req->data->getData() : [];
            }

            $createdEntry = $this->service->createEntry($cardId, $payload);
            if ($createdEntry) {
                Flight::json($createdEntry, 200);
            }
        } catch (\Throwable $e) {
            Flight::json(['error'=> $e->getMessage()], 500);
        }
    }
}