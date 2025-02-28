<?php
// controllers/compositionsController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Composition.php';
require_once __DIR__ . '/../models/Carte.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// Démarre une session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instances des modèles nécessaires pour gérer les compositions, cartes et utilisateurs
$compositionModel = new Composition($pdo);
$carteModel = new Carte($pdo);
$utilisateurModel = new Utilisateur($pdo);

/**
 * Ce contrôleur gère les actions CRUD (Create, Read, Update, Delete) et les interactions avec les compositions.
 * 
 * Les actions incluent la création, la modification, la suppression, le filtrage et l'ajout de "like" aux compositions.
 */
$action = $_GET['action'] ?? '';

$isUserConnected = isset($_SESSION['user_id']);
$isAdmin = $isUserConnected && $_SESSION['role'] === 'admin';

switch ($action) {

    /**
     * Action 'create' - Créer une nouvelle composition.
     * 
     * Cette action permet à un utilisateur connecté de créer une nouvelle composition en fournissant un nom,
     * une description, un nombre de joueurs et une sélection de cartes. Si l'utilisateur n'est pas connecté, 
     * il est redirigé vers la page de connexion.
     * 
     * Paramètres POST attendus :
     * - nom : Nom de la composition (string)
     * - description : Description de la composition (string)
     * - nombre_joueurs : Nombre de joueurs pour cette composition (int)
     * - cartes : Liste des IDs des cartes sélectionnées (array)
     */
    case 'create':
        if (!$isUserConnected) {
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $description = $_POST['description'];
            $nombre_joueurs = $_POST['nombre_joueurs'];
            $cartes = $_POST['cartes'] ?? [];
            $utilisateur_id = $_SESSION['user_id'];

            if ($nom && $description && $nombre_joueurs && !empty($cartes)) {
                $compositionModel->createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id);
                header('Location: /loup-garou-crud/public/index.php');
                exit;
            } else {
                echo "Erreur : Tous les champs sont requis.";
            }
        } else {
            $cartesDisponibles = $carteModel->getAllCartes();
            include '../views/compositions/create.php';
        }
        break;

    /**
     * Action 'delete_composition' - Supprimer une composition.
     * 
     * Cette action permet de supprimer une composition si l'utilisateur connecté en est l'auteur ou est un administrateur.
     * 
     * Paramètre GET attendu :
     * - id : Identifiant de la composition à supprimer (int)
     */
    case 'delete_composition':
        $id = $_GET['id'] ?? null;

        if ($id && isset($_SESSION['user_id']) && ($compositionModel->isAuthor($_SESSION['user_id'], $id) || $isAdmin)) {
            $compositionModel->deleteComposition($id);
            header('Location: /loup-garou-crud/public/index.php');
            exit;
        }

    /**
     * Action 'edit' - Modifier une composition existante.
     * 
     * Cette action permet à un utilisateur connecté de modifier une composition s'il en est l'auteur ou s'il est administrateur.
     * Si une modification est soumise via POST, la composition est mise à jour avec les nouvelles informations fournies.
     * 
     * Paramètres POST attendus :
     * - nom : Nom de la composition (string)
     * - description : Description de la composition (string)
     * - nombre_joueurs : Nombre de joueurs pour cette composition (int)
     * - cartes : Liste des IDs des cartes sélectionnées (array)
     */
    case 'edit':
        if (!$isUserConnected) {
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        $id = $_GET['id'] ?? null;

        if ($id && isset($_SESSION['user_id']) && ($compositionModel->isAuthor($_SESSION['user_id'], $id) || $isAdmin)) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = $_POST['nom'];
                $description = $_POST['description'];
                $nombre_joueurs = $_POST['nombre_joueurs'];
                $cartes = $_POST['cartes'];

                $compositionModel->updateComposition($id, $nom, $description, $nombre_joueurs, $cartes);
                header('Location: /loup-garou-crud/public/index.php');
                exit;
            } else {
                $composition = $compositionModel->getCompositionById($id);

                if (!$composition) {
                    echo "Erreur : Composition introuvable.";
                    exit;
                }

                $cartesDisponibles = $carteModel->getAllCartes();
                include __DIR__ . '/../views/compositions/edit.php';
                exit;
            }
        } else {
            echo "Vous n'avez pas la permission de modifier cette composition.";
            exit;
        }

    /**
     * Action 'filter' - Filtrer les compositions.
     * 
     * Permet de filtrer les compositions par nombre de joueurs et/ou par les cartes sélectionnées.
     * Si aucun critère n'est fourni, toutes les compositions sont retournées.
     * 
     * Paramètres GET attendus :
     * - nombre_joueurs : Nombre de joueurs (int)
     * - card_ids : Liste des IDs des cartes (array)
     */
    case 'filter':
        $nombre_joueurs = $_GET['nombre_joueurs'] ?? null;
        $card_ids = $_GET['card_ids'] ?? [];

        if ($nombre_joueurs && !empty($card_ids)) {
            $filteredCompositions = $compositionModel->filterByCardsAndPlayers($card_ids, $nombre_joueurs);
        } elseif ($nombre_joueurs) {
            $filteredCompositions = $compositionModel->filterByNumberOfPlayers($nombre_joueurs);
        } elseif (!empty($card_ids)) {
            $filteredCompositions = $compositionModel->filterByExactCards($card_ids);
        } else {
            $filteredCompositions = $compositionModel->getAllCompositionsAlphabetical();
        }

        include '../views/compositions/_compositions_list.php';
        break;

    /**
     * Action par défaut - Affichage de toutes les compositions.
     * 
     * Si aucune action spécifique n'est demandée, cette action affiche toutes les compositions, 
     * triées par ordre alphabétique ou par les compositions les plus appréciées.
     */
    default:
        $topLikedCompositions = $compositionModel->getTopLikedCompositions();
        $compositionsAlphabetical = $compositionModel->getAllCompositionsAlphabetical();
        $cartesDisponibles = $carteModel->getAllCartes();
        include '../views/compositions/index.php';
        break;

    /**
     * Action 'like' - Gérer le "like" d'une composition.
     * 
     * Permet à un utilisateur connecté d'ajouter un "like" à une composition si ce n'est pas déjà fait.
     * 
     * Paramètres POST attendus :
     * - composition_id : ID de la composition à liker (int)
     */
    case 'like':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $compositionId = $_POST['composition_id'];
            $userId = $_SESSION['user_id'] ?? null;

            if ($userId) {
                if (!$compositionModel->hasUserLiked($compositionId, $userId)) {
                    $compositionModel->likeComposition($compositionId, $userId);
                }
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        break;
}
