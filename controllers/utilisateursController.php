<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateursController {
    private PDO $pdo;
    private Utilisateur $utilisateurModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->utilisateurModel = new Utilisateur($this->pdo);
    }

    public function login(array $data) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include '../views/utilisateurs/login.php';
            return;
        }

        $identifiant = $data['identifiant'];
        $password = $data['password'];
        $utilisateur = $this->utilisateurModel->getUtilisateurByPseudoOrEmail($identifiant);

        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['pseudo'] = $utilisateur['pseudo'];
            $_SESSION['role'] = $utilisateur['role'];

            if ($utilisateur['role'] === 'admin') {
                header('Location: /loup-garou-crud/public/index.php?action=admin');
            } else {
                header('Location: /loup-garou-crud/public/index.php');
            }
            exit;
        } else {
            die("Erreur : Identifiants incorrects.");
        }
    }

    public function register(array $data) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include '../views/utilisateurs/register.php';
            return;
        }

        $pseudo = $data['pseudo'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'] ?? 'user';

        if (empty($pseudo) || empty($email) || empty($password)) {
            die("Erreur : Tous les champs sont requis.");
        }

        $result = $this->utilisateurModel->registerUser($pseudo, $email, $password, $role);

        if ($result) {
            header('Location: /loup-garou-crud/public/index.php?action=login');
            exit;
        } else {
            die("Erreur : Échec de l'inscription.");
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /loup-garou-crud/public/index.php');
        exit;
    }
}
?>
