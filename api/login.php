<?php

namespace api;

use Exception;
use modele\Api;
use modele\dao\UtilisateurDao;
use modele\entites\Utilisateur;
use PDOException;

require_once '../Autoloader.php';
require_once '../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    // On vérifie la méthode utilisée
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée', 405);
    }

    // On récupère les données du body
    $jsonInput = file_get_contents("php://input");
    $data = json_decode($jsonInput);

    // On vient vérifier la présence de chacun des attributs requis
    if (!isset($data->email) || !is_string($data->email) || trim($data->email) === '') {
        throw new Exception('L\'email est requis et doit être une chaîne non vide', 400);
    }
    if (!isset($data->password) || !is_string($data->password) || trim($data->password) === '') {
        throw new Exception('Le mot de passe est requis et doit être une chaîne non vide', 400);
    }

    $utilisateur = new Utilisateur();
    $utilisateur->setEmail($data->email);
    $utilisateur->setPassword($data->password);

    // On l'insère en BDD
    $utilisateurDao = new UtilisateurDao();
    $utilisateur = $utilisateurDao->login($utilisateur);

    http_response_code(201);
    echo json_encode(['Message' => 'Identifiants corrects']);
} catch (PDOException $e) {
    // On met un code d'erreur 500 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 500);
    echo json_encode(['message' => $e->getMessage()]);
} catch (Exception $e) {
    // On met un code d'erreur 400 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 400);
    echo json_encode(['message' => $e->getMessage()]);
}
