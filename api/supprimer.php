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
 * @OA\Delete(
 *     path="/api/v1.0/produit/delete/{id}",
 *     summary="Supprime un produit",
 *     description="Cet endpoint permet de supprimer un produit en utilisant son ID, soit via l'URL soit dans le corps de la requête.",
 *     tags={"Produits"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         description="ID du produit à supprimer",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produit supprimé avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Suppression effectuée")
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
 *             @OA\Property(property="message", type="string", example="Erreur lors de la suppression du produit")
 *         )
 *     )
 * )
 */

require_once '../Autoloader.php';
require_once '../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // On vérifie la méthode utilisée
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        throw new Exception('Méthode non autorisée', 405);
    }

    // On récupère le token stocké dans les cookies
    $cookieService = new CookieService();
    $token = $cookieService->getAuthToken();

    // On vérifie que le token est valide
    $jwtService = new JwtService();
    $utilisateur = $jwtService->verifyAuthToken($token);

    // On vérifie le token en BDD
    $tokenDao = new TokenDao();
    $tokenDao->verify($token, $utilisateur);

    // On récupère les données du body
    $jsonInput = file_get_contents("php://input");
    $data = json_decode($jsonInput);

    // On vérifie que l'utilisateur a envoyé un ID, soit par l'URL soit par le body
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
    } elseif (isset($data->id)) {
        $id = (int)$data->id;
    } else {
        throw new Exception('L\'id est requis et doit être un nombre valide', 400);
    }

    // On vient supprimer le produit en BDD
    $produitDao = new ProduitDao();
    $produitDao->supprimer($id);

    http_response_code(200);
    echo json_encode(['message' => 'Suppression effectuée']);
} catch (PDOException $e) {
    // On met un code d'erreur 500 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 500);
    echo json_encode(['message' => $e->getMessage()]);
} catch (Exception $e) {
    // On met un code d'erreur 400 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 400);
    echo json_encode(['message' => $e->getMessage()]);
}
