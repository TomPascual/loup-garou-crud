<?php
// models/Utilisateur.php

/**
 * Classe Utilisateur
 * 
 * Cette classe gère les opérations liées aux utilisateurs, notamment l'inscription, la connexion, et la gestion des rôles.
 */
class Utilisateur {
    /**
     * @var PDO $pdo Instance de la connexion à la base de données.
     */
    private $pdo;

    /**
     * Constructeur de la classe Utilisateur.
     * 
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère un utilisateur par son pseudo ou email.
     * 
     * @param string $identifiant Le pseudo ou l'email de l'utilisateur.
     * @return array|false Retourne un tableau associatif contenant les informations de l'utilisateur, ou false si non trouvé.
     */
    public function getUtilisateurByPseudoOrEmail($identifiant) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ? OR email = ?");
        $stmt->execute([$identifiant, $identifiant]);
        return $stmt->fetch();
    }

    /**
     * Inscrit un nouvel utilisateur.
     * 
     * @param string $pseudo Le pseudo de l'utilisateur.
     * @param string $email L'adresse email de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur (sera haché).
     * @param string $role (optionnel) Le rôle de l'utilisateur, par défaut 'user'.
     * @return bool Retourne true si l'inscription a réussi, sinon false.
     */
    public function registerUser($pseudo, $email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (pseudo, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$pseudo, $email, $hashedPassword, $role]);
    }

    /**
     * Vérifie si un utilisateur est administrateur.
     * 
     * @param int $userId L'identifiant de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a le rôle 'admin', sinon false.
     */
    public function isAdmin($userId) {
        $stmt = $this->pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        return $user && $user['role'] === 'admin';
    }

    /**
     * Fonction de connexion d'un utilisateur.
     * 
     * Vérifie le pseudo ou l'email et le mot de passe de l'utilisateur. Si les informations sont correctes, 
     * retourne les informations de l'utilisateur.
     * 
     * @param string $identifiant Le pseudo ou l'email de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @return array|false Retourne un tableau associatif contenant les informations de l'utilisateur, ou false si la connexion échoue.
     */
    public function login($identifiant, $password) {
        $user = $this->getUtilisateurByPseudoOrEmail($identifiant);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
