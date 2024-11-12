<?php

namespace modele\dao;

use DateTime;
use Exception;
use modele\entites\Produit;
use PDOException;

class ProduitDao
{
    public function __construct() {}

    public function creer(Produit $produit): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = "INSERT into T_PRODUIT (nom, description, prix, date_creation) values (:nom, :description, :prix, :date_creation)";
            $query = $pdo->prepare($sql);
            $query->bindValue('nom', htmlspecialchars(strip_tags($produit->getNom())));
            $query->bindValue('description', htmlspecialchars(strip_tags($produit->getDescription())));
            $query->bindValue('prix', htmlspecialchars(strip_tags($produit->getPrix())));
            $query->bindValue('date_creation', htmlspecialchars(strip_tags($produit->getDateCreation()->format('Y-m-d H:i:s'))));
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function lireUn(int $id): Produit
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = 'SELECT * FROM T_PRODUIT WHERE id = :id';
            $query = $pdo->prepare($sql);
            $query->bindValue('id', $id);
            $query->execute();

            // On récupère le produit et vérifie qu'il y en a un
            $data = $query->fetch();
            if (!$data) {
                throw new Exception('Produit n\'existe pas', 404);
            }

            // On vient créer une instance de Produit
            $produit = new Produit();
            $produit->setId($data['id']);
            $produit->setNom($data['nom']);
            $produit->setDescription($data['description']);
            $produit->setPrix($data['prix']);
            $produit->setDateCreation(new DateTime($data['date_creation']));

            return $produit;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function lire(): array
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = 'SELECT * FROM T_PRODUIT';
            $query = $pdo->prepare($sql);
            $query->execute();

            // On récupère les produits et vérifie qu'il y en a au moins un
            $data = $query->fetchAll();
            if (empty($data)) {
                throw new Exception('Impossible d\'obtenir les produits', 404);
            }

            // On vient remplir un tableau avec des instances de Produit
            $produits = [];
            foreach ($data as $pdt) {
                $produit = new Produit();
                $produit->setId($pdt['id']);
                $produit->setNom($pdt['nom']);
                $produit->setDescription($pdt['description']);
                $produit->setPrix($pdt['prix']);
                $produit->setDateCreation(new DateTime($pdt['date_creation']));

                $produits[] = $produit;
            }

            return $produits;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function modifier(Produit $produit): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer la requête
            $sql = "UPDATE T_PRODUIT SET ";
            $fields = [];
            $params = [];

            // On regarde quels champs ont été renseignés
            if (trim($produit->getNom()) !== '') {
                $fields[] = "nom = :nom";
                $params[':nom'] = htmlspecialchars(strip_tags($produit->getNom()));
            }
            if (trim($produit->getDescription()) !== '') {
                $fields[] = "description = :description";
                $params[':description'] = htmlspecialchars(strip_tags($produit->getDescription()));
            }
            if ($produit->getPrix() !== 0) {
                $fields[] = "prix = :prix";
                $params[':prix'] = htmlspecialchars(strip_tags($produit->getPrix()));
            }
            if ($produit->getDateCreation()->format('Y-m-d H:i:s') !== '1970-01-01 00:00:00') {
                $fields[] = "date_creation = :date_creation";
                $params[':date_creation'] = htmlspecialchars(strip_tags($produit->getDateCreation()->format('Y-m-d H:i:s')));
            }

            // Si aucun champ n'a été renseigné
            if (empty($fields)) {
                throw new Exception("Aucune donnée à mettre à jour", 400);
            }

            // On rajoute la clause WHERE
            $sql .= implode(", ", $fields) . " WHERE id = :id";
            $params[':id'] = $produit->getId();

            // On exécute
            $query = $pdo->prepare($sql);
            $query->execute($params);

            // On s'assure qu'un produit a été modifié
            if ($query->rowCount() === 0) {
                throw new Exception('Aucune modification effectuée', 400);
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function supprimer(int $id): void
    {
        try {
            $connexion = new Connexion();
            $pdo = $connexion->getPDO();

            // On vient créer et exécuter la requête
            $sql = "DELETE FROM T_PRODUIT WHERE id = :id";
            $query = $pdo->prepare($sql);
            $query->bindValue('id', $id);
            $query->execute();

            // On s'assure qu'un produit a été supprimé
            if ($query->rowCount() === 0) {
                throw new Exception('Produit non trouvé', 404);
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
