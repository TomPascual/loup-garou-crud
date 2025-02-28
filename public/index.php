<?php
// index.php

/**
 * Point d'entrée principal de l'application.
 * 
 * Ce fichier charge les contrôleurs appropriés en fonction de l'action demandée via le paramètre GET 'action'.
 * Il gère également la vérification de la session utilisateur et la gestion des différentes routes.
 */

// Vérifie si la session est déjà démarrée, sinon elle est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupère l'action à partir de l'URL (paramètre GET 'action')
$action = $_GET['action'] ?? '';

/**
 * Routeur principal de l'application.
 * 
 * Charge le contrôleur correspondant à l'action demandée. Si aucune action spécifique n'est donnée,
 * le contrôleur par défaut (compositionsController) est chargé.
 */
switch ($action) {
    // Actions liées aux cartes
    case 'cartes':
    case 'create_carte':
    case 'edit_carte': 
    case 'delete_carte':
        require_once '../controllers/cartesController.php';
        break;

    // Actions liées aux compositions
    case 'create_composition':
    case 'edit':
    case 'delete_composition':
        require_once '../controllers/compositionsController.php';
        break;

    // Actions liées aux utilisateurs (connexion, inscription, déconnexion)
    case 'login':
    case 'register':
    case 'logout':
        require_once '../controllers/utilisateursController.php';
        break;

    // Action par défaut : affiche les compositions
    default:
        require_once '../controllers/compositionsController.php';
        break;
}
