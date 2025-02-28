<?php
// models/Carte.php

/**
 * Classe Carte
 * 
 * Cette classe gère les opérations CRUD (Create, Read, Update, Delete) sur les cartes dans la base de données.
 */
class Carte
{
    /**
     * @var PDO $pdo Instance de la connexion à la base de données.
     */
    private $pdo;

    /**
     * Constructeur de la classe Carte.
     * 
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère toutes les cartes.
     * 
     * @return array Retourne un tableau associatif contenant toutes les cartes.
     */
    public function getAllCartes()
    {
        $stmt = $this->pdo->query('SELECT * FROM cartes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une carte par son identifiant.
     * 
     * @param int $id Identifiant de la carte.
     * @return array|false Retourne un tableau associatif contenant les informations de la carte, ou false si non trouvé.
     */
    public function getCarteById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM cartes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crée une nouvelle carte.
     * 
     * @param string $nom Nom de la carte.
     * @param string $description Description de la carte.
     * @param string $photo URL de la photo associée à la carte.
     * @param string $categorie Catégorie de la carte.
     * @return bool Retourne true si l'insertion a réussi, sinon false.
     */
    public function createCarte($nom, $description, $photo, $categorie)
    {
        $stmt = $this->pdo->prepare('INSERT INTO cartes (nom, description, photo, categorie) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$nom, $description, $photo, $categorie]);
    }

    /**
     * Met à jour une carte existante.
     * 
     * @param int $id Identifiant de la carte à mettre à jour.
     * @param string $nom Nouveau nom de la carte.
     * @param string $description Nouvelle description de la carte.
     * @param string $photo Nouvelle URL de la photo associée à la carte.
     * @param string $categorie Nouvelle catégorie de la carte.
     * @return bool Retourne true si la mise à jour a réussi, sinon false.
     */
    public function updateCarte($id, $nom, $description, $photo, $categorie)
    {
        $stmt = $this->pdo->prepare('UPDATE cartes SET nom = ?, description = ?, photo = ?, categorie = ? WHERE id = ?');
        return $stmt->execute([$nom, $description, $photo, $categorie, $id]);
    }

    /**
     * Supprime une carte par son identifiant.
     * 
     * @param int $id Identifiant de la carte à supprimer.
     * @return bool Retourne true si la suppression a réussi, sinon false.
     */
    public function deleteCarte($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM cartes WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
