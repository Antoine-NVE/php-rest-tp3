<?php

namespace modele\dao;

use Exception;
use modele\entites\Utilisateur;
use PDOException;

class UtilisateurDao
{
    public function __construct() {}

    public function register(Utilisateur $utilisateur): Utilisateur
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            $hashedPassword = password_hash($utilisateur->getPassword(), PASSWORD_DEFAULT);

            // On vient créer et exécuter la requête
            $sql = "INSERT INTO t_utilisateur (lastname, firstname, email, password) VALUES (:lastname, :firstname, :email, :password)";
            $query = $pdo->prepare($sql);
            $query->bindValue('lastname', htmlspecialchars(strip_tags($utilisateur->getLastname())));
            $query->bindValue('firstname', htmlspecialchars(strip_tags($utilisateur->getFirstname())));
            $query->bindValue('email', htmlspecialchars(strip_tags($utilisateur->getEmail())));
            $query->bindValue('password', $hashedPassword);
            $query->execute();

            $utilisateur->setId($pdo->lastInsertId());
            $utilisateur->setPassword($hashedPassword);

            return $utilisateur;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function login(Utilisateur $utilisateur): Utilisateur
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = "SELECT * FROM t_utilisateur where email = :email";
            $query = $pdo->prepare($sql);
            $query->bindValue('email', htmlspecialchars(strip_tags($utilisateur->getEmail())));
            $query->execute();
            $data = $query->fetch();

            // On vérifie que les identifiants sont corrects
            if (!$data || !password_verify($utilisateur->getPassword(), $data['password'])) {
                throw new Exception("Identifiants incorrects", 401);
            }

            $utilisateur->setId($data['id']);
            $utilisateur->setLastname($data['lastname']);
            $utilisateur->setFirstname($data['firstname']);
            $utilisateur->setPassword($data['password']);

            return $utilisateur;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function read(int $userId): Utilisateur
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setId($userId);
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = "SELECT * FROM t_utilisateur where id = :id";
            $query = $pdo->prepare($sql);
            $query->bindValue('id', htmlspecialchars(strip_tags($utilisateur->getId())));
            $query->execute();
            $data = $query->fetch();

            $utilisateur->setEmail($data['email']);
            $utilisateur->setLastname($data['lastname']);
            $utilisateur->setFirstname($data['firstname']);
            $utilisateur->setPassword($data['password']);

            return $utilisateur;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
