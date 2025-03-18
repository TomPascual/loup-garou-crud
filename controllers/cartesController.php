<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Carte.php';

class CartesController {
    private PDO $pdo;
    private Carte $carteModel;

    public function __construct() {
        // Création de la connexion à la base de données et du modèle Carte
        $this->pdo = Database::getInstance()->getConnection();
        $this->carteModel = new Carte($this->pdo);
    }

    // Afficher toutes les cartes
    public function index() {
        $cartesDisponibles = $this->carteModel->getAllCartes(); // Récupérer toutes les cartes
        if (empty($cartesDisponibles)) {
            die("Erreur : Aucune carte disponible dans la base de données.");
        }
        $cartes = $cartesDisponibles;
        include '../views/cartes/index.php';
    }
    
    
    

    // Créer une nouvelle carte
    public function create(array $data, array $files) {
        // Validation des champs requis
        if (empty($data['nom']) || empty($data['description']) || empty($data['categorie'])) {
            throw new Exception("Erreur : Tous les champs sont requis.");
        }
    
        // Gestion de l'upload de l'image
        $photoUrl = null;
        if (isset($files['photo']) && $files['photo']['error'] == UPLOAD_ERR_OK) {
            $photoTmpPath = $files['photo']['tmp_name'];
            $photoName = basename($files['photo']['name']);
            $photoExtension = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
    
            // Vérification des extensions autorisées
            $extensionsAutorisees = ['jpg', 'png', 'pdf'];
    
            // Vérification du type MIME pour éviter les fichiers malicieux
            $mimeTypesAutorises = ['image/jpeg', 'image/png', 'application/pdf'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $photoTmpPath);
            finfo_close($finfo);
    
            if (!in_array($photoExtension, $extensionsAutorisees) || !in_array($mimeType, $mimeTypesAutorises)) {
                throw new Exception("Erreur : Seuls les fichiers JPG, PNG et PDF sont autorisés.");
            }
    
            $photoDestinationPath = __DIR__ . '/../uploads/' . $photoName;
    
            // Créer le dossier 'uploads' s'il n'existe pas
            if (!file_exists(__DIR__ . '/../uploads')) {
                mkdir(__DIR__ . '/../uploads', 0777, true);
            }
    
            if (move_uploaded_file($photoTmpPath, $photoDestinationPath)) {
                $photoUrl = '/loup-garou-crud/uploads/' . $photoName;
            } else {
                throw new Exception("Erreur : Impossible de télécharger la photo.");
            }
        }
    
        try {
            // Appel à la méthode du modèle pour ajouter la carte
            $result = $this->carteModel->createCarte($data['nom'], $data['description'], $photoUrl, $data['categorie']);
            if ($result) {
                header('Location: /loup-garou-crud/public/index.php?action=cartes');
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Erreur : Échec de l'ajout de la carte. Détails : " . $e->getMessage());
        }
    }
    

    // Modifier une carte
public function edit(int $id, array $data, array $files) {
    if (empty($data['nom']) || empty($data['description']) || empty($data['categorie'])) {
        throw new Exception("Erreur : Tous les champs sont requis.");
    }

    // Si aucune nouvelle photo, on garde l'ancienne
    $photoUrl = $data['current_photo'] ?? null;

    if (isset($files['photo']) && $files['photo']['error'] == UPLOAD_ERR_OK) {
        $photoTmpPath = $files['photo']['tmp_name'];
        $photoName = basename($files['photo']['name']);
        $photoExtension = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));

        // Vérification des extensions autorisées
        $extensionsAutorisees = ['jpg', 'png', 'pdf'];

        // Vérification du type MIME pour éviter les fichiers malicieux
        $mimeTypesAutorises = ['image/jpeg', 'image/png', 'application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $photoTmpPath);
        finfo_close($finfo);

        if (!in_array($photoExtension, $extensionsAutorisees) || !in_array($mimeType, $mimeTypesAutorises)) {
            throw new Exception("Erreur : Seuls les fichiers JPG, PNG et PDF sont autorisés.");
        }

        $photoDestinationPath = __DIR__ . '/../uploads/' . $photoName;

        if (move_uploaded_file($photoTmpPath, $photoDestinationPath)) {
            $photoUrl = '/loup-garou-crud/uploads/' . $photoName;
        }
    }

    try {
        // Mise à jour de la carte
        $result = $this->carteModel->updateCarte($id, $data['nom'], $data['description'], $photoUrl, $data['categorie']);
        if ($result) {
            header('Location: /loup-garou-crud/public/index.php?action=cartes');
            exit;
        }
    } catch (Exception $e) {
        throw new Exception("Erreur : Échec de la mise à jour de la carte. Détails : " . $e->getMessage());
    }
}

    // Supprimer une carte
    public function delete(int $id) {
        try {
            // Suppression de la carte
            $result = $this->carteModel->deleteCarte($id);
            if ($result) {
                header('Location: /loup-garou-crud/public/index.php?action=cartes');
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Erreur : Échec de la suppression de la carte. Détails : " . $e->getMessage());
        }
    }

    // Récupérer une carte par son ID
    public function getCarteById(int $id) {
        return $this->carteModel->getCarteById($id);
    }
}
?>
