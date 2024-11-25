<?php

namespace services;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use modele\entites\Utilisateur;

class JwtService
{
    private string $secretKey = 'azerty';

    public function __construct() {}

    public function generateAuthToken(Utilisateur $utilisateur): string
    {
        try {
            $payload = [
                'id' => $utilisateur->getId(),
                'iat' => time(),
                'exp' => time() + (60 * 60)
            ];
            return JWT::encode($payload, $this->secretKey, 'HS256');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    public function verifyAuthToken($token): int
    {
        try {
            $data = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            return $data->id;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 401);
        }
    }
}
