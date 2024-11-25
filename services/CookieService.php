<?php

namespace services;

use Exception;

class CookieService
{
    public function __construct() {}

    public function setAuthToken(string $token): void
    {
        $options = [
            'expires' => time() + (60 * 60),
            'path' => 'php-rest-tp3',
            'domain' => 'localhost',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ];

        setcookie('auth_token', $token, $options);
    }

    public function getAuthToken(): string
    {
        if (!isset($_COOKIE['auth_token'])) {
            throw new Exception('Non connecté', 401);
        }

        return $_COOKIE['auth_token'];
    }

    public function unsetAuthToken(): void
    {
        setcookie('auth_token', '');
    }
}
