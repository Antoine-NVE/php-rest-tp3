<?php

namespace api;

use DateTime;
use Exception;
use modele\dao\ProduitDao;
use modele\dao\TokenDao;
use modele\dao\UtilisateurDao;
use modele\entites\Produit;
use PDOException;
use OpenApi\Annotations as OA;
use services\CookieService;
use services\JwtService;

/**
 * @OA\Info(title="API Antoine NAVETTE", version="1.0.0")
 */

/**
 * @OA\Post(
 *     path="/api/v1.0/produit/new",
 *     summary="Créer un produit",
 *     tags={"Produits"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom", "description", "prix", "date_creation"},
 *             @OA\Property(property="nom", type="string", example="Produit A"),
 *             @OA\Property(property="description", type="string", example="Description du produit A"),
 *             @OA\Property(property="prix", type="number", format="float", example=19.99),
 *             @OA\Property(property="date_creation", type="string", format="date", example="2024-10-10")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Produit créé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Produit a été créé")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation des données",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Le nom est requis et doit être une chaîne non vide")
 *         )
 *     ),
 *     @OA\Response(
 *         response=405,
 *         description="Méthode non autorisée",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Méthode non autorisée")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur")
 *         )
 *     )
 * )
 */

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

    // On récupère le token stocké dans les cookies
    $cookieService = new CookieService();
    $token = $cookieService->getAuthToken();

    // On vérifie que le token est valide
    $jwtService = new JwtService();
    $userId = $jwtService->verifyAuthToken($token);

    // On récupère les informations de l'utilisateur
    $utilisateurDao = new UtilisateurDao();
    $utilisateur = $utilisateurDao->read($userId);

    // On vérifie le token en BDD
    $tokenDao = new TokenDao();
    $tokenDao->verify($token, $utilisateur);

    // On récupère les données du body
    $jsonInput = file_get_contents("php://input");
    $data = json_decode($jsonInput);

    // On vient vérifier la présence de chacun des attributs requis
    if (!isset($data->nom) || !is_string($data->nom) || trim($data->nom) === '') {
        throw new Exception('Le nom est requis et doit être une chaîne non vide', 400);
    }
    if (!isset($data->description) || !is_string($data->description) || trim($data->description) === '') {
        throw new Exception('La description est requise et doit être une chaîne non vide', 400);
    }
    if (!isset($data->prix) || !is_numeric($data->prix)) {
        throw new Exception('Le prix est requis et doit être un nombre valide', 400);
    }
    if (!isset($data->date_creation) || trim($data->date_creation) === '') {
        throw new Exception('La date de création est requise', 400);
    }

    // On crée une instance de Produit
    $produit = new Produit();
    $produit->setNom($data->nom);
    $produit->setDescription($data->description);
    $produit->setPrix($data->prix);
    $produit->setDateCreation(new DateTime($data->date_creation));

    // On l'insère en BDD
    $produitDao = new ProduitDao();
    $produitDao->creer($produit);

    http_response_code(201);
    echo json_encode(['message' => 'Produit a été créé']);
} catch (PDOException $e) {
    // On met un code d'erreur 500 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 500);
    echo json_encode(['message' => $e->getMessage()]);
} catch (Exception $e) {
    // On met un code d'erreur 400 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 400);
    echo json_encode(['message' => $e->getMessage()]);
}
