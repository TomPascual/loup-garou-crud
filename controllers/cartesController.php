<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Carte.php';

class CartesController {
    private PDO $pdo;
    private Carte $carteModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->carteModel = new Carte($this->pdo);
    }

    public function index() {
        return $this->carteModel->getAllCartes();
    }

    public function create(array $data, array $files) {
        if (empty($data['nom']) || empty($data['description']) || empty($data['categorie'])) {
            die("Erreur : Tous les champs sont requis.");
        }

        // Gestion de l'upload de l'image
        $photoUrl = null;
        if (isset($files['photo']) && $files['photo']['error'] == UPLOAD_ERR_OK) {
            $photoTmpPath = $files['photo']['tmp_name'];
            $photoName = basename($files['photo']['name']);
            $photoDestinationPath = __DIR__ . '/../uploads/' . $photoName;

            // Créer le dossier uploads si absent
            if (!file_exists(__DIR__ . '/../uploads')) {
                mkdir(__DIR__ . '/../uploads', 0777, true);
            }

            if (move_uploaded_file($photoTmpPath, $photoDestinationPath)) {
                $photoUrl = '/loup-garou-crud/uploads/' . $photoName;
            } else {
                die("Erreur : Impossible de télécharger la photo.");
            }
        }

        $result = $this->carteModel->createCarte($data['nom'], $data['description'], $photoUrl, $data['categorie']);

        if ($result) {
            header('Location: /loup-garou-crud/public/index.php?action=cartes');
            exit;
        } else {
            die("Erreur : Échec de l'ajout de la carte.");
        }
    }

    public function edit(int $id, array $data, array $files) {
        if (empty($data['nom']) || empty($data['description']) || empty($data['categorie'])) {
            die("Erreur : Tous les champs sont requis.");
        }

        $photoUrl = $data['current_photo'] ?? null;

        // Gestion d'upload d'une nouvelle photo
        if (isset($files['photo']) && $files['photo']['error'] == UPLOAD_ERR_OK) {
            $photoTmpPath = $files['photo']['tmp_name'];
            $photoName = basename($files['photo']['name']);
            $photoDestinationPath = __DIR__ . '/../uploads/' . $photoName;

            if (move_uploaded_file($photoTmpPath, $photoDestinationPath)) {
                $photoUrl = '/loup-garou-crud/uploads/' . $photoName;
            }
        }

        $result = $this->carteModel->updateCarte($id, $data['nom'], $data['description'], $photoUrl, $data['categorie']);

        if ($result) {
            header('Location: /loup-garou-crud/public/index.php?action=cartes');
            exit;
        } else {
            die("Erreur : Échec de la mise à jour de la carte.");
        }
    }

    public function delete(int $id) {
        $result = $this->carteModel->deleteCarte($id);

        if ($result) {
            header('Location: /loup-garou-crud/public/index.php?action=cartes');
            exit;
        } else {
            die("Erreur : Échec de la suppression de la carte.");
        }
    }
    public function getCarteById(int $id) {
        return $this->carteModel->getCarteById($id);
    }
    
}
?>
