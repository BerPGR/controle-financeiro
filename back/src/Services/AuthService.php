<?php

namespace Mp\Controle\Services;

use InvalidArgumentException;
use Mp\Controle\Repositories\AuthRepository;
use RuntimeException;

class AuthService
{
    public function __construct(private AuthRepository $repository)
    {
    }

    public function register(array $payload): array
    {
        $name = trim($payload['name'] ?? '');
        $email = strtolower(trim($payload['email'] ?? ''));
        $password = $payload['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Nome, email e senha são obrigatórios.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email inválido.');
        }

        if (strlen($password) < 6) {
            throw new InvalidArgumentException('A senha deve conter pelo menos 6 caracteres.');
        }

        if ($this->repository->findByEmail($email)) {
            throw new RuntimeException('Email já cadastrado.');
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $user = $this->repository->create($name, $email, $passwordHash);

        return $this->issueAuthResponse($user);
    }

    public function login(array $payload): array
    {
        $email = strtolower(trim($payload['email'] ?? ''));
        $password = $payload['password'] ?? '';

        if ($email === '' || $password === '') {
            throw new InvalidArgumentException('Email e senha são obrigatórios.');
        }

        $user = $this->repository->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new RuntimeException('Credenciais inválidas.');
        }

        return $this->issueAuthResponse($user);
    }

    private function issueAuthResponse(array $user): array
    {
        $token = \jwt_issue_token($user);

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => (int)$user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ],
        ];
    }
}