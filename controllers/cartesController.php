<?php
// controllers/cartesController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Carte.php';

// Instance du modèle Carte pour gérer les actions liées aux cartes
$carteModel = new Carte($pdo);

/**
 * Ce contrôleur gère les actions CRUD (Create, Read, Update, Delete) pour les cartes.
 * 
 * Les actions sont capturées à partir du paramètre GET 'action'. 
 * Principales actions disponibles :
 * - 'create_carte': Ajouter une nouvelle carte
 * - 'edit_carte': Modifier une carte existante
 * - 'delete_carte': Supprimer une carte
 * - Afficher toutes les cartes par défaut
 */
$action = $_GET['action'] ?? '';

switch ($action) {

    /**
     * Action 'create_carte' - Création d'une nouvelle carte.
     * 
     * Cette partie gère la création de cartes à partir des données soumises via POST, 
     * y compris la gestion de l'upload de fichier (photo) et des champs requis.
     * 
     * Paramètres POST attendus :
     * - nom : Nom de la carte (string)
     * - description : Description de la carte (string)
     * - categorie : Catégorie de la carte (string, optionnel)
     * - photo : Fichier image de la carte (optionnel)
     */
    case 'create_carte':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $description = $_POST['description'];
            $categorie = $_POST['categorie'] ?? '';

            if (empty($categorie)) {
                echo "Erreur : La catégorie est requise.";
                exit;
            }

            // Gestion de l'upload de la photo
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
                $photoTmpPath = $_FILES['photo']['tmp_name'];
                $photoName = basename($_FILES['photo']['name']);
                $photoDestinationPath = __DIR__ . '/../uploads/' . $photoName;

                // Créer le répertoire 'uploads' s'il n'existe pas
                if (!file_exists(__DIR__ . '/../uploads')) {
                    mkdir(__DIR__ . '/../uploads', 0777, true);
                }

                // Déplacer le fichier uploadé
                if (move_uploaded_file($photoTmpPath, $photoDestinationPath)) {
                    $photoUrl = '/loup-garou-crud/uploads/' . $photoName;

                    // Créer la carte avec la photo et la catégorie
                    $result = $carteModel->createCarte($nom, $description, $photoUrl, $categorie);
                    
                    if ($result) {
                        header('Location: /loup-garou-crud/public/index.php?action=cartes');
                        exit;
                    } else {
                        echo "Erreur : Échec de l'ajout de la carte.";
                    }
                } else {
                    echo "Erreur : Impossible de télécharger la photo.";
                }
            } else {
                echo "Erreur : Tous les champs sont requis, y compris la photo.";
            }
        } else {
            include '../views/cartes/create.php';
        }
        break;

    /**
     * Action 'edit_carte' - Modification d'une carte existante.
     * 
     * Cette partie gère la mise à jour d'une carte via POST, avec une option 
     * de mise à jour de l'image. Si aucune nouvelle image n'est uploadée, l'ancienne est conservée.
     * 
     * Paramètres POST attendus :
     * - nom : Nom de la carte (string)
     * - description : Description de la carte (string)
     * - categorie : Catégorie de la carte (string)
     * - photo : Fichier image de la carte (optionnel)
     */
    case 'edit_carte':
        if (isset($_GET['id'])) {
            $carteId = $_GET['id'];
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = $_POST['nom'];
                $description = $_POST['description'];
                $categorie = $_POST['categorie'];
                $photoUrl = $_POST['current_photo'];

                // Gestion de l'upload d'une nouvelle photo
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
                    $photoTmpPath = $_FILES['photo']['tmp_name'];
                    $photoName = basename($_FILES['photo']['name']);
                    $photoDestinationPath = __DIR__ . '/../uploads/' . $photoName;

                    if (move_uploaded_file($photoTmpPath, $photoDestinationPath)) {
                        $photoUrl = '/loup-garou-crud/uploads/' . $photoName;
                    }
                }

                // Mise à jour de la carte
                $result = $carteModel->updateCarte($carteId, $nom, $description, $photoUrl, $categorie);

                if ($result) {
                    header('Location: /loup-garou-crud/public/index.php?action=cartes');
                    exit;
                } else {
                    echo "Erreur : Échec de la mise à jour de la carte.";
                }
            } else {
                $carte = $carteModel->getCarteById($carteId);
                include '../views/cartes/edit.php';
            }
        }
        break;

    /**
     * Action 'delete_carte' - Suppression d'une carte existante.
     * 
     * Cette partie gère la suppression d'une carte spécifique via son ID.
     * 
     * Paramètre GET attendu :
     * - id : Identifiant de la carte à supprimer (int)
     */
    case 'delete_carte':
        if (isset($_GET['id'])) {
            $carteId = $_GET['id'];
            $result = $carteModel->deleteCarte($carteId);

            if ($result) {
                header('Location: /loup-garou-crud/public/index.php?action=cartes');
                exit;
            } else {
                echo "Erreur : Échec de la suppression de la carte.";
            }
        }
        break;

    /**
     * Action par défaut - Affichage de toutes les cartes.
     * 
     * Si aucune action n'est spécifiée, cette partie récupère et affiche toutes les cartes.
     */
    default:
        $cartes = $carteModel->getAllCartes();
        include '../views/cartes/index.php';
        break;
}
