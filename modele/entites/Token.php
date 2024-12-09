<?php

namespace modele\entites;

use DateTime;

class Token
{
    private int $id;
    private string $token;
    private DateTime $date_expiration;
    private bool $actif;
    private string $domaine;
    private int $utilisateur_id;

    public function __construct() {}

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setDateExpiration(DateTime $date_expiration): void
    {
        $this->date_expiration = $date_expiration;
    }

    public function getDateExpiration(): DateTime
    {
        return $this->date_expiration;
    }

    public function setActif(bool $actif): void
    {
        $this->actif = $actif;
    }

    public function getActif(): bool
    {
        return $this->actif;
    }

    public function setDomaine(string $domaine): void
    {
        $this->domaine = $domaine;
    }

    public function getDomaine(): string
    {
        return $this->domaine;
    }

    public function setUtilisateurId(int $utilisateur_id): void
    {
        $this->utilisateur_id = $utilisateur_id;
    }

    public function getUtilisateurId(): int
    {
        return $this->utilisateur_id;
    }
}
