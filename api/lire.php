<?php

namespace api;

use Exception;
use modele\dao\ProduitDao;
use modele\dao\TokenDao;
use modele\dao\UtilisateurDao;
use PDOException;
use OpenApi\Annotations as OA;
use services\CookieService;
use services\JwtService;

/**
 * @OA\Get(
 *     path="/api/v1.0/produit/list",
 *     summary="Récupère la liste des produits",
 *     description="Retourne un tableau contenant tous les produits disponibles avec leurs détails.",
 *     tags={"Produits"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des produits récupérée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="nom", type="string", example="Produit A"),
 *                 @OA\Property(property="description", type="string", example="Description du produit A"),
 *                 @OA\Property(property="prix", type="number", format="float", example=29.99),
 *                 @OA\Property(property="date_creation", type="string", format="date-time", example="2023-01-01 12:00:00")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Requête incorrecte",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="L'id est requis et doit être un nombre valide")
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
 *         description="Erreur serveur interne",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Erreur lors de la récupération des produits")
 *         )
 *     )
 * )
 */

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

    // On lit les produits depuis la BDD
    $produitDao = new ProduitDao();
    $produits = $produitDao->lire();

    // On transforme les instances de Produit en tableaux associatifs
    $produitsArray = [];
    foreach ($produits as $produit) {
        $produitsArray[] = [
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'prix' => $produit->getPrix(),
            'date_creation' => $produit->getDateCreation()->format('Y-m-d H:i:s'),
        ];
    }

    http_response_code(200);
    echo json_encode($produitsArray);
} catch (PDOException $e) {
    // On met un code d'erreur 500 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 500);
    echo json_encode(['message' => $e->getMessage()]);
} catch (Exception $e) {
    // On met un code d'erreur 400 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 400);
    echo json_encode(['message' => $e->getMessage()]);
}
