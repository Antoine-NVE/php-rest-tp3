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
 * @OA\Put(
 *     path="/api/v1.0/produit/update",
 *     summary="Met à jour un produit existant",
 *     description="Cet endpoint permet de mettre à jour un produit avec les nouvelles informations fournies. L'ID du produit est requis pour effectuer la modification.",
 *     tags={"Produits"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="nom", type="string", example="Produit modifié"),
 *             @OA\Property(property="description", type="string", example="Description mise à jour"),
 *             @OA\Property(property="prix", type="number", format="float", example=19.99),
 *             @OA\Property(property="date_creation", type="string", format="date-time", example="2024-01-01 10:00:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produit mis à jour avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Modification effectuée")
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
 *             @OA\Property(property="message", type="string", example="Erreur lors de la modification du produit")
 *         )
 *     )
 * )
 */

require_once '../Autoloader.php';
require_once '../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // On vérifie la méthode utilisée
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

    // On vient vérifier la présence de chacun des attributs requis
    if (!isset($data->id) || !is_numeric($data->id)) {
        throw new Exception('L\'id est requis et doit être un nombre valide', 400);
    }

    // On vient créer une instance de Produit
    $produit = new Produit();
    $produit->setId($data->id);

    // On renseigne les attributs ou l'on met des valeurs par défaut
    // Les valeurs par défaut sont obligatoires car sinon les getters renvoient des erreurs
    if (isset($data->nom) && is_string($data->nom) && trim($data->nom) !== '') {
        $produit->setNom(trim($data->nom));
    } else {
        $produit->setNom('');
    }
    if (isset($data->description) && is_string($data->description) && trim($data->description) !== '') {
        $produit->setDescription(trim($data->description));
    } else {
        $produit->setDescription('');
    }
    if (isset($data->prix) && is_numeric($data->prix)) {
        $produit->setPrix($data->prix);
    } else {
        $produit->setPrix(0);
    }
    if (isset($data->date_creation) && trim($data->date_creation) !== '') {
        $produit->setDateCreation(new DateTime($data->date_creation));
    } else {
        $produit->setDateCreation(new DateTime('1970-01-01 00:00:00'));
    }

    // On vient modifier le produit en BDD
    $produitDao = new ProduitDao();
    $produitDao->modifier($produit);

    http_response_code(200);
    echo json_encode(['message' => 'Modification effectuée']);
} catch (PDOException $e) {
    // On met un code d'erreur 500 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 500);
    echo json_encode(['message' => $e->getMessage()]);
} catch (Exception $e) {
    // On met un code d'erreur 400 par défaut
    http_response_code($e->getCode() !== 0 ? $e->getCode() : 400);
    echo json_encode(['message' => $e->getMessage()]);
}
