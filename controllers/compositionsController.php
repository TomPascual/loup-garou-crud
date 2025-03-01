<?php

require_once __DIR__ . '/../config/Database.php';
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
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        if (empty($data['nom']) || empty($data['description']) || empty($data['nombre_joueurs']) || empty($data['cartes'])) {
            die("Erreur : Tous les champs sont requis.");
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
            header('Location: /loup-garou-crud/public/index.php');
            exit;
        } else {
            die("Erreur : Échec de l'ajout de la composition.");
        }
    }

    public function edit(int $id, array $data) {
        if ($_SESSION['role'] !== 'admin') {
            die("Erreur : Accès interdit.");
        }
    
        $nom = $data['nom'];
        $description = $data['description'];
        $nombre_joueurs = $data['nombre_joueurs'];
        $cartes = $data['cartes']; // Vérifie si ce champ est bien transmis dans le formulaire
    
        $result = $this->compositionModel->updateComposition($id, $nom, $description, $nombre_joueurs, $cartes);
    
        if ($result) {
            header('Location: /loup-garou-crud/public/index.php');
            exit;
        } else {
            die("Erreur : Échec de la mise à jour de la composition.");
        }
    }
    

    public function delete(int $id) {
        if ($_SESSION['role'] !== 'admin') {
            die("Erreur : Accès interdit.");
        }

        $result = $this->compositionModel->deleteComposition($id);

        if ($result) {
            header('Location: /loup-garou-crud/public/index.php');
            exit;
        } else {
            die("Erreur : Échec de la suppression de la composition.");
        }
    }

    public function getCompositionById(int $id) {
        return $this->compositionModel->getCompositionById($id);
    }
}
?>
