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
    private PDO $pdo;

    private function validateCarte($nom, $description)
{
    if (strlen($description) < 5) {
        throw new Exception("La description doit contenir au moins 5 caractères.");
    }
}
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Récupère toutes les cartes.
     * 
     * @return array Retourne un tableau associatif contenant toutes les cartes.
     */
    public function getAllCartes() {
        $stmt = $this->pdo->query("SELECT * FROM cartes"); // Exécution de la requête pour récupérer toutes les cartes
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne les résultats sous forme de tableau associatif
    }
    
    /**
     * Récupère une carte par son identifiant.
     * 
     * @param int $id Identifiant de la carte.
     * @return array|false Retourne un tableau associatif contenant les informations de la carte, ou false si non trouvé.
     */
    public function getCarteById($id) {
        if (!$this->pdo) {
            die("Erreur : connexion à la base de données non disponible.");
        }
    
        $query = "SELECT * FROM cartes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
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
        $this->validateCarte($nom, $description); 
        try {
            $stmt = $this->pdo->prepare('INSERT INTO cartes (nom, description, photo, categorie) VALUES (?, ?, ?, ?)');
    
            if (!$stmt) {
                $errorInfo = $this->pdo->errorInfo(); // Récupérer les erreurs PDO
                throw new Exception("Erreur lors de la préparation de la requête SQL : " . implode(" | ", $errorInfo));
            }
    
            return $stmt->execute([$nom, $description, $photo, $categorie]);
        } catch (PDOException $e) {
            throw new Exception("Erreur SQL : " . $e->getMessage());
        }
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
        $this->validateCarte($nom, $description); 
        try {
            $stmt = $this->pdo->prepare('UPDATE cartes SET nom = ?, description = ?, photo = ?, categorie = ? WHERE id = ?');
            return $stmt->execute([$nom, $description, $photo, $categorie, $id]);
        } catch (PDOException $e) {
            throw new Exception("Erreur SQL lors de la mise à jour : " . $e->getMessage());
        }
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
