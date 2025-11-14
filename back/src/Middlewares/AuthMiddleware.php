<?php

namespace Mp\Controle\Middlewares;

use flight\Engine;

class AuthMiddleware {
    public function __construct(protected Engine $app) {}

    public function before(array $params) {
        $authorization = $this->app->request()->getHeader('Authorization');

        if (empty($authorization) || !str_starts_with($authorization,'Bearer ')) {
            $this->app->jsonHalt(['error' => 'Missing Bearer token'], 401);
        }

        $token = substr($authorization, 7);

        try {
            $decoded = jwt_decode_token($token);

            $this->app->set('auth.user', [
                'id' => (int)$decoded->sub,
                'email' => $decoded->email ?? null
            ]);
        } catch (\Throwable $e) {
            $this->app->jsonHalt([
                'error' => 'Invalid or expired token',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}