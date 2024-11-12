<?php

namespace modele\dao;

use Exception;
use PDO;
use PDOException;

class Connexion
{
    private $pdo;

    private $host = 'localhost';
    private $dbName = 'db_labrest';
    private $username = 'root';
    private $password = '';

    public function __construct()
    {
        try {
            // Création d'une instance PDO
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8",
                $this->username,
                $this->password
            );

            // Mise en place des paramètres
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), 503);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getPDO()
    {
        // On retourne notre instance PDO
        return $this->pdo;
    }
}
