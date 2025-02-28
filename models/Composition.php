<?php

class Composition {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCompositions() {
        $stmt = $this->pdo->query("SELECT * FROM compositions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}
?>
