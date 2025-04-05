<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Composition.php';
require_once __DIR__ . '/../models/Carte.php';

class CompositionsController {
    private PDO $pdo;
    private Composition $compositionModel;
    private Carte $carteModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->compositionModel = new Composition($this->pdo);
        $this->carteModel = new Carte($this->pdo);
    }

    public function index() {
        // Récupérer les compositions populaires
        $topLikedCompositions = $this->compositionModel->getTopLikedCompositions();
        // Récupérer les cartes disponibles
        $cartesDisponibles = $this->carteModel->getAllCartes();
        // Récupérer les compositions triées par ordre alphabétique
        $compositionsAlphabetical = $this->compositionModel->getCompositionsAlphabetical();
    
        // S'assurer que ces variables ne sont pas vides ou nulles avant de les envoyer à la vue
        if (!$topLikedCompositions) {
            $topLikedCompositions = [];
        }
    
        if (!$cartesDisponibles) {
            $cartesDisponibles = [];
        }
    
        if (!$compositionsAlphabetical) {
            $compositionsAlphabetical = [];
        }
    
        // Passer les variables à la vue
        return compact('topLikedCompositions', 'cartesDisponibles', 'compositionsAlphabetical');
    }
    
    

    public function create(array $data) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?action=login&error=connect_required');
            exit;
        }

         
        $nom = trim($data['nom'] ?? '');
        $description = trim($data['description'] ?? '');
        $nombre_joueurs = (int) ($data['nombre_joueurs'] ?? 0);
        $cartes = $data['cartes'] ?? [];

        $errors = [];


        if (strlen($nom) < 3 || strlen($nom) > 30) {
            $errors[] = "Le nom doit contenir entre 3 et 30 caractères.";
        }

        if (strlen($description) < 10 || strlen($description) > 500) {
            $errors[] = "La description doit contenir entre 10 et 500 caractères.";
        }

        if ($nombre_joueurs < 5) {
            $errors[] = "Le nombre de joueurs doit être d'au moins 5.";
        }

        $totalCartes = array_sum(array_map('intval', $cartes));
        if ($totalCartes !== $nombre_joueurs) {
            $errors[] = "Le nombre total de cartes sélectionnées ($totalCartes) doit être exactement égal au nombre de joueurs ($nombre_joueurs).";
        }

        if (!empty($errors)) {
            
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
            return; 
        }

        $user_id = $_SESSION['user_id'];
        $result = $this->compositionModel->createComposition(
            $data['nom'],
            $data['description'],
            $data['nombre_joueurs'],
            $data['cartes'],
            $user_id
        );

        if ($result) {
            header('Location: /index.php');
            exit;
        } else {
            die("Erreur : Échec de l'ajout de la composition.");
        }
    }

    public function edit(int $id, array $data) {
        if (!isset($_SESSION['user_id'])) {
            die("Erreur : Accès interdit.");
        }
    
        // Vérifier que toutes les données nécessaires sont présentes
        if (empty($data['nom']) || empty($data['description']) || empty($data['nombre_joueurs']) || empty($data['cartes'])) {
            die("Erreur : Tous les champs sont requis.");
        }
    
        // Récupérer les valeurs du formulaire
        $nom = $data['nom'];
        $description = $data['description'];
        $nombre_joueurs = $data['nombre_joueurs'];
        $cartes = $data['cartes'];
    
        // Appeler la mise à jour dans le modèle
        $result = $this->compositionModel->updateComposition($id, $nom, $description, $nombre_joueurs, $cartes);
    
        if ($result) {
            header('Location: /index.php');
            exit;
        } else {
            die("Erreur : La modification de la composition a échoué.");
        }
    }
    

    public function delete(int $id) {
        if ($_SESSION['role'] !== 'admin') {
            die("Erreur : Accès interdit.");
        }

        $result = $this->compositionModel->deleteComposition($id);

        if ($result) {
            header('Location: /index.php');
            exit;
        } else {
            die("Erreur : Échec de la suppression de la composition.");
        }
    }

    public function getCompositionById(int $id) {
        return $this->compositionModel->getCompositionById($id);
    }
    public function getAllCartes() {
        return $this->carteModel->getAllCartes();
    }
    
}
?>
