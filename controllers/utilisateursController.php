<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateursController {
    private PDO $pdo;
    private Utilisateur $utilisateurModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->utilisateurModel = new Utilisateur($this->pdo);
    }

    public function login(array $data) {
        $identifiant = $data['identifiant'];
        $password = $data['password'];
        $utilisateur = $this->utilisateurModel->getUtilisateurByPseudoOrEmail($identifiant);
    
        if (!$utilisateur || !password_verify($password, $utilisateur['password'])) {
            $_SESSION['login_error'] = "Identifiants incorrects.";
            header("Location: /index.php?action=login");
            exit();
        }
    
        // Connexion réussie : on stocke bien le rôle
        $_SESSION['user_id'] = $utilisateur['id'];
        $_SESSION['pseudo'] = $utilisateur['pseudo'];
        $_SESSION['role'] = $utilisateur['role']; // 
    
        header("Location: /index.php");
        
        exit();
        
    }
    

    public function register($data) {
        if (empty($data['pseudo']) || empty($data['email']) || empty($data['password'])) {
            $_SESSION['register_error'] = "Tous les champs sont requis.";
            $_SESSION['register_data'] = $data;
            header("Location: /index.php?action=register");
            exit();
        }
    
    
        // Rôle par défaut "utilisateur"
        $role = 'utilisateur';
    
        // Enregistrement de l'utilisateur
        $success = $this->utilisateurModel->registerUser($data['pseudo'], $data['email'], $data['password'], $role);
    
        if (!$success) {
            $_SESSION['register_error'] = "Cet email est déjà utilisé ou une erreur est survenue.";
            $_SESSION['register_data'] = $data;
            header("Location: /index.php?action=register");
            exit();
        }
    
        $_SESSION['register_success'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
        header("Location: /index.php?action=login");
        exit();
    }
    
    

    public function logout() {
        session_destroy();
        header('Location: /index.php');
        exit;
    }
}
?>
