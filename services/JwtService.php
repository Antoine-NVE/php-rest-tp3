<?php

namespace services;

use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use modele\dao\UtilisateurDao;
use modele\entites\Token;
use modele\entites\Utilisateur;

class JwtService
{
    private string $secretKey = 'azerty';

    public function __construct() {}

    // On génère un token JWT
    public function generateAuthToken(Utilisateur $utilisateur): Token
    {
        try {
            $payload = [
                'id' => $utilisateur->getId(),
                'iat' => time(),
                'exp' => time() + (60 * 60)
            ];

            $token = new Token();
            $token->setToken(JWT::encode($payload, $this->secretKey, 'HS256'));
            $token->setDateExpiration(new DateTime(date('Y-m-d H:i:s', time() + (60 * 60))));
            $token->setDomaine('localhost');
            $token->setActif(true);
            $token->setUtilisateurId($utilisateur->getId());
            return $token;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    // On vérifie un token JWT
    public function verifyAuthToken(Token $token): Utilisateur
    {
        try {
            $data = JWT::decode($token->getToken(), new Key($this->secretKey, 'HS256'));

            $utilisateurDao = new UtilisateurDao();
            $utilisateur = $utilisateurDao->read($data->id);

            if (!$utilisateur) {
                throw new Exception('Utilisateur introuvable', 401);
            }

            return $utilisateur;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 401);
        }
    }
}
