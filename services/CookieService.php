<?php

namespace services;

use Exception;
use modele\entites\Token;

class CookieService
{
    private int $expires = 3600; // 1 heure
    private string $path = '/';
    private string $domain = 'localhost';
    private bool $secure = true;
    private bool $httponly = true;
    private string $samesite = 'Strict';

    public function __construct() {}

    // On définit le cookie auth_token
    public function setAuthToken(Token $token): void
    {
        $options = [
            'expires' => time() + $this->expires,
            'path' => $this->path,
            'domain' => $this->domain,
            'secure' => $this->secure,
            'httponly' => $this->httponly,
            'samesite' => $this->samesite
        ];

        setcookie('auth_token', $token->getToken(), $options);
    }

    // On récupère le cookie auth_token
    public function getAuthToken(): Token
    {
        if (!isset($_COOKIE['auth_token'])) {
            throw new Exception('Non connecté', 401);
        }

        $token = new Token();
        $token->setToken($_COOKIE['auth_token']);

        return $token;
    }

    // On supprime le cookie auth_token
    public function unsetAuthToken(): void
    {
        $options = [
            'expires' => time() - 1,
            'path' => $this->path,
            'domain' => $this->domain,
            'secure' => $this->secure,
            'httponly' => $this->httponly,
            'samesite' => $this->samesite
        ];

        setcookie('auth_token', '', $options);
    }
}
