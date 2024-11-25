<?php

namespace api;

use Exception;
use modele\dao\TokenDao;
use modele\dao\UtilisateurDao;
use PDOException;
use services\CookieService;
use services\JwtService;

require_once '../Autoloader.php';
require_once '../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    // On vérifie la méthode utilisée
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Méthode non autorisée', 405);
    }

    // On récupère et supprime le token des cookies
    $cookieService = new CookieService();
    $token = $cookieService->getAuthToken();
    $cookieService->unsetAuthToken();

    // On vérifie le token
    $jwtService = new JwtService();
    $userId = $jwtService->verifyAuthToken($token);

    // On récupère l'utilisateur
    $utilisateurDao = new UtilisateurDao();
    $user = $utilisateurDao->read($userId);

    // On rend inactif le token en BDD
    $tokenDao = new TokenDao();
    $tokenDao->setInactive($token, $user);

    http_response_code(200);
    echo json_encode(['message' => 'Déconnecté']);
} catch (PDOException $e) {
    // On met un code d'erreur 500 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 500);
    echo json_encode(['message' => $e->getMessage()]);
} catch (Exception $e) {
    // On met un code d'erreur 400 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 400);
    echo json_encode(['message' => $e->getMessage()]);
}
