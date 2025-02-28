<?php
// controllers/utilisateursController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// Démarre une session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instance du modèle Utilisateur pour gérer les interactions avec les utilisateurs
$utilisateurModel = new Utilisateur($pdo);

/**
 * Ce contrôleur gère les actions liées à l'authentification et à la gestion des utilisateurs.
 * 
 * Actions disponibles :
 * - 'login' : Connexion d'un utilisateur
 * - 'register' : Inscription d'un nouvel utilisateur
 * - 'admin' : Accès au tableau de bord de l'administrateur
 * - 'logout' : Déconnexion de l'utilisateur
 */
$action = $_GET['action'] ?? '';

switch ($action) {

    /**
     * Action 'login' - Connexion d'un utilisateur.
     * 
     * Permet à un utilisateur de se connecter en fournissant son identifiant et son mot de passe.
     * Si la connexion réussit, les informations de l'utilisateur sont stockées dans la session.
     * 
     * Paramètres POST attendus :
     * - identifiant : Identifiant de l'utilisateur (string)
     * - password : Mot de passe de l'utilisateur (string)
     */
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = $_POST['identifiant'];
            $password = $_POST['password'];
            $utilisateur = $utilisateurModel->login($identifiant, $password);

            if ($utilisateur) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['pseudo'] = $utilisateur['pseudo'];
                $_SESSION['role'] = $utilisateur['role']; // Stocker le rôle de l'utilisateur dans la session

                // Rediriger en fonction du rôle de l'utilisateur
                if ($utilisateur['role'] === 'admin') {
                    header('Location: /loup-garou-crud/public/index.php?action=admin');
                } else {
                    header('Location: /loup-garou-crud/public/index.php');
                }
                exit;
            } else {
                echo "Erreur : identifiant ou mot de passe incorrect.";
            }
        } else {
            include '../views/utilisateurs/login.php';
        }
        break;

    /**
     * Action 'register' - Inscription d'un nouvel utilisateur.
     * 
     * Permet à un utilisateur de s'inscrire en fournissant un pseudo, un email, et un mot de passe.
     * Le rôle de l'utilisateur est défini par défaut à 'user'.
     * 
     * Paramètres POST attendus :
     * - pseudo : Pseudo de l'utilisateur (string)
     * - email : Email de l'utilisateur (string)
     * - password : Mot de passe de l'utilisateur (string)
     */
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Enregistrer un utilisateur avec le rôle par défaut 'user'
            if ($utilisateurModel->registerUser($pseudo, $email, $password, 'user')) {
                header('Location: /loup-garou-crud/public/index.php?action=login');
                exit;
            } else {
                echo "Erreur : inscription échouée.";
            }
        } else {
            include '../views/utilisateurs/register.php';
        }
        break;

    /**
     * Action 'admin' - Accès au tableau de bord de l'administrateur.
     * 
     * Cette action permet à un utilisateur avec un rôle 'admin' d'accéder au tableau de bord de l'administrateur.
     * Si l'utilisateur n'est pas administrateur, un message d'accès refusé est affiché.
     */
    case 'admin':
        // Seulement l'admin peut accéder à cette action
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            include '../views/admin/dashboard.php';
        } else {
            echo "Accès refusé.";
        }
        break;

    /**
     * Action 'logout' - Déconnexion de l'utilisateur.
     * 
     * Détruit la session de l'utilisateur et redirige vers la page de connexion.
     */
    case 'logout':
        // Détruire la session et rediriger vers la page de connexion
        session_destroy();
        header('Location: /loup-garou-crud/public/index.php?action=login');
        exit;

    /**
     * Action par défaut - Affichage de la page de connexion.
     * 
     * Si aucune action spécifique n'est demandée, la page de connexion est affichée.
     */
    default:
        include '../views/utilisateurs/login.php';
        break;
}
