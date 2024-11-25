<?php

namespace modele\dao;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use modele\entites\Utilisateur;

class Api
{
    private string $secretKey = 'azerty';

    private function __construct() {}

    public static function generateToken(Utilisateur $utilisateur): string
    {
        try {
            $payload = [
                'id' => $utilisateur->getId(),
                'iat' => time(),
                'exp' => time() + (60 * 60)
            ];
            $token = JWT::encode($payload, self::$secretKey, 'HS256');

            $options = [
                'expires' => time() + (60 * 60),
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ];

            setcookie('auth_token', $token, $options);

            return $token;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    // public function verify()
    // {
    //     try {
    //         if (!$_COOKIE['auth_token']) {
    //             throw new Exception("Aucun token", 401);
    //         }
    //         JWT::decode($_COOKIE['auth_token'], new Key($this->secretKey, 'HS256'));
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage(), 401);
    //     }
    // }
}
