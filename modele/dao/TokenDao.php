<?php

namespace modele\dao;

use Exception;
use modele\entites\Token;
use modele\entites\Utilisateur;
use PDOException;

class TokenDao
{
    public function __construct() {}

    public function insert(Token $token): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = "INSERT INTO t_token (token, date_expiration, domaine, actif, utilisateur_id) values (:token, :date_expiration, :domaine, :actif, :utilisateur_id)";
            $query = $pdo->prepare($sql);
            $query->bindValue('token', $token->getToken());
            $query->bindValue('date_expiration', $token->getDateExpiration()->format('Y-m-d H:i:s'));
            $query->bindValue('domaine', $token->getDomaine());
            $query->bindValue('actif', $token->getActif());
            $query->bindValue('utilisateur_id', $token->getUtilisateurId());

            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function verify(Token $token, Utilisateur $utilisateur): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = 'SELECT * FROM t_token WHERE token = :token AND date_expiration > NOW() AND domaine = "localhost" AND actif = 1 AND utilisateur_id = :id';
            $query = $pdo->prepare($sql);
            $query->bindValue('token', $token->getToken());
            $query->bindValue('id', $utilisateur->getId());
            $query->execute();
            $data = $query->fetch();
            if (!$data) {
                throw new Exception('Token invalide', 401);
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function setInactive(Token $token, Utilisateur $utilisateur): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = 'UPDATE t_token SET actif = 0 WHERE token = :token AND utilisateur_id = :id';
            $query = $pdo->prepare($sql);
            $query->bindValue('token', $token->getToken());
            $query->bindValue('id', $utilisateur->getId());
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
