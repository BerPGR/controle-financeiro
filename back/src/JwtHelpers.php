<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function jwt_issue_token(array $user): string {
    $config = Flight::get('jwt.config');

    $now = time();
    $exp = $now + $config['ttl'];

    $payload = [
                'iss'   => $config['iss'],
        'aud'   => $config['aud'],
        'iat'   => $now,
        'nbf'   => $now,
        'exp'   => $exp,
        'sub'   => (string)$user['id'],
        'email' => $user['email'],
        'role'  => $user['role'],
    ];

    return JWT::encode($payload, $config['secret'], 'HS256');
}

function jwt_decode_token(string $token) {
    $config = Flight::get('jwt.config');
    $decoded = JWT::decode($token, new Key($config['secret'], 'HS256'));

    if (($decoded->iss ?? '') !== $config['iss'] || ($decoded->aud ?? '') !== $config['aud']) {
        throw new Exception('Iss ou aud inválido');
    }

    return $decoded;
}
