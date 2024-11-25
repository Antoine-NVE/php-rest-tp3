<?php

namespace modele\dao;

use Exception;
use modele\entites\Utilisateur;
use PDOException;

class TokenDao
{
    public function __construct() {}

    public function insert(string $token, Utilisateur $utilisateur): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = "INSERT INTO t_token (token, date_expiration, domaine, actif, t_utilisateur_id) values (:token, :date_expiration, :domaine, :actif, :t_utilisateur_id)";
            $query = $pdo->prepare($sql);
            $query->bindValue('token', $token);
            $query->bindValue('date_expiration', date('Y-m-d H:i:s', time() + (60 * 60)));
            $query->bindValue('domaine', 'localhost');
            $query->bindValue('actif', true);
            $query->bindValue('t_utilisateur_id', $utilisateur->getId());

            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function verify(string $token, Utilisateur $utilisateur): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = 'SELECT * FROM t_token WHERE token = :token AND date_expiration > NOW() AND domaine = "localhost" AND actif = 1 AND t_utilisateur_id = :id';
            $query = $pdo->prepare($sql);
            $query->bindValue('token', $token);
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

    public function setInactive(string $token, Utilisateur $utilisateur): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = 'UPDATE t_token SET actif = 0 WHERE token = :token AND t_utilisateur_id = :id';
            $query = $pdo->prepare($sql);
            $query->bindValue('token', $token);
            $query->bindValue('id', $utilisateur->getId());
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
