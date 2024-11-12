<?php

namespace modele\entites;

use DateTime;

class Produit
{
    private int $id;
    private string $nom;
    private string $description;
    private int $prix;
    private DateTime $date_creation;

    public function __construct() {}

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrix(): int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): void
    {
        $this->prix = $prix;
    }

    public function getDateCreation(): DateTime
    {
        return $this->date_creation;
    }

    public function setDateCreation(DateTime $date_creation): void
    {
        $this->date_creation = $date_creation;
    }
}
