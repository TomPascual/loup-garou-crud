<?php

class Composition {
    private PDO $pdo;


    private function validateComposition($nom, $description)
{
    if (strlen($description) < 5) {
        throw new Exception("La description doit contenir au moins 5 caractères.");
    }
}
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id) {
        $this->validateComposition($nom, $description);
        $cartes_json = json_encode($cartes);
        $stmt = $this->pdo->prepare("INSERT INTO compositions (nom, description, nombre_joueurs, cartes, utilisateur_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $utilisateur_id]);
    }

    public function getCompositionById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM compositions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateComposition($id, $nom, $description, $nombre_joueurs, $cartes) {
        $cartes_json = json_encode($cartes); // Convertir les cartes en JSON
        $this->validateComposition($nom, $description);
    
        $stmt = $this->pdo->prepare("UPDATE compositions 
                                    SET nom = ?, description = ?, nombre_joueurs = ?, cartes = ? 
                                    WHERE id = ?");
        $result = $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $id]);
    
        if (!$result) {
            var_dump($stmt->errorInfo()); // Affiche l'erreur SQL en cas de problème
            exit;
        }
    
        return $result;
    }
    

    public function deleteComposition($id) {
        $stmt = $this->pdo->prepare("DELETE FROM compositions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function filterByCardsAndPlayers(array $cardIds, int $nombre_joueurs) {
        $placeholders = implode(',', array_fill(0, count($cardIds), '?'));
        $query = "SELECT DISTINCT c.* FROM compositions c
                  JOIN compositions_cartes cc ON c.id = cc.composition_id
                  WHERE cc.carte_id IN ($placeholders) AND c.nombre_joueurs = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([...$cardIds, $nombre_joueurs]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isAuthor($userId, $compositionId) {
        $stmt = $this->pdo->prepare("SELECT utilisateur_id FROM compositions WHERE id = ?");
        $stmt->execute([$compositionId]);
        $composition = $stmt->fetch();
        
        return $composition && $composition['utilisateur_id'] == $userId;
    }
    public function getAllCompositions($search = '') {
        $query = 'SELECT c.*, u.pseudo AS utilisateur, 
                  (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
                  FROM compositions c
                  JOIN utilisateurs u ON c.utilisateur_id = u.id';
    
        if ($search) {
            $query .= ' WHERE c.nom LIKE :search';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query($query);
        }
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTopLikedCompositions() {
        $query = "SELECT c.*, 
                         u.pseudo AS utilisateur, 
                         (SELECT COUNT(*) FROM likes WHERE composition_id = c.id) AS likes
                  FROM compositions c
                  JOIN utilisateurs u ON c.utilisateur_id = u.id
                  ORDER BY likes DESC 
                  LIMIT 5";
    
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getCompositionsAlphabetical() {
        $query = "SELECT compositions.*, 
                         utilisateurs.pseudo AS utilisateur, 
                         (SELECT COUNT(*) FROM likes WHERE likes.composition_id = compositions.id) AS likes
                  FROM compositions
                  JOIN utilisateurs ON compositions.utilisateur_id = utilisateurs.id
                  ORDER BY compositions.nom ASC";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function hasUserLiked($compositionId, $userId) {
        if (!$userId) return false; // Si l'utilisateur n'est pas connecté
    
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE composition_id = ? AND user_id = ?");
        $stmt->execute([$compositionId, $userId]);
        return $stmt->fetchColumn() > 0;
    }
    
    
    public function toggleLike($compositionId, $userId) {
        // Vérifie si l'utilisateur a déjà liké la composition
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE composition_id = ? AND user_id = ?");
        $stmt->execute([$compositionId, $userId]);
        $hasLiked = $stmt->fetchColumn() > 0;
    
        if ($hasLiked) {
            // Si déjà liké, on supprime le like
            $stmt = $this->pdo->prepare("DELETE FROM likes WHERE composition_id = ? AND user_id = ?");
            $stmt->execute([$compositionId, $userId]);
        } else {
            // Sinon, on ajoute un like
            $stmt = $this->pdo->prepare("INSERT INTO likes (composition_id, user_id) VALUES (?, ?)");
            $stmt->execute([$compositionId, $userId]);
        }
    }
    
    
    
}
?>
