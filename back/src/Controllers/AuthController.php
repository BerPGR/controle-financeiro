<?php

namespace Mp\Controle\Controllers;

use Flight;
use Mp\Controle\Services\AuthService;

class AuthController
{
    public function __construct(private AuthService $service)
    {
    }

    public function register(): void
    {
        $payload = $this->getPayload();

        try {
            $response = $this->service->register($payload);
            Flight::json($response, 201);
        } catch (\InvalidArgumentException $e) {
            Flight::json(['error' => true, 'message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    public function login(): void
    {
        $payload = $this->getPayload();

        try {
            $response = $this->service->login($payload);
            Flight::json($response, 200);
        } catch (\InvalidArgumentException $e) {
            Flight::json(['error' => true, 'message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Flight::json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    private function getPayload(): array
    {
        $req = Flight::request();
        $raw = $req->getBody();
        $payload = json_decode($raw, true);

        if (!is_array($payload) || empty($payload)) {
            $payload = $req->data ? $req->data->getData() : [];
        }

        return $payload;
    }
}