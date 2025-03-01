<?php

class Composition {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }



    public function createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id) {
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
        $cartes_json = json_encode($cartes);
        $stmt = $this->pdo->prepare("UPDATE compositions SET nom = ?, description = ?, nombre_joueurs = ?, cartes = ? WHERE id = ?");
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $id]);
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
                         (SELECT COUNT(*) FROM likes WHERE composition_id = c.id) AS likes
                  FROM compositions c 
                  ORDER BY likes DESC 
                  LIMIT 5";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCompositionsAlphabetical() {
        $stmt = $this->pdo->query("SELECT * FROM compositions ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
}
?>
