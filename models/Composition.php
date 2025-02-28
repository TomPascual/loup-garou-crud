<?php
// models/Composition.php

/**
 * Classe Composition
 * 
 * Cette classe gère les opérations CRUD (Create, Read, Update, Delete) ainsi que d'autres fonctionnalités liées aux compositions.
 */
class Composition
{
    /**
     * @var PDO $pdo Instance de la connexion à la base de données.
     */
    private $pdo;

    /**
     * Constructeur de la classe Composition.
     * 
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère toutes les compositions avec le nombre de "likes".
     * 
     * @param string $search (optionnel) Mot-clé pour rechercher des compositions par nom.
     * @return array Retourne un tableau associatif contenant toutes les compositions.
     */
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

    /**
     * Récupère une composition par son identifiant.
     * 
     * @param int $id Identifiant de la composition.
     * @return array|false Retourne un tableau associatif contenant les informations de la composition, ou false si non trouvé.
     */
    public function getCompositionById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM compositions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle composition.
     * 
     * @param string $nom Nom de la composition.
     * @param string $description Description de la composition.
     * @param int $nombre_joueurs Nombre de joueurs dans cette composition.
     * @param array $cartes Liste des cartes associées à la composition, encodées en JSON.
     * @param int $utilisateur_id Identifiant de l'utilisateur qui crée la composition.
     * @return bool Retourne true si la création a réussi, sinon false.
     */
    public function createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id)
    {
        $cartes_json = json_encode($cartes);
        $stmt = $this->pdo->prepare('INSERT INTO compositions (nom, description, nombre_joueurs, cartes, utilisateur_id) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $utilisateur_id]);
    }

    /**
     * Met à jour une composition existante.
     * 
     * @param int $id Identifiant de la composition à mettre à jour.
     * @param string $nom Nouveau nom de la composition.
     * @param string $description Nouvelle description de la composition.
     * @param int $nombre_joueurs Nouveau nombre de joueurs.
     * @param array $cartes Nouvelles cartes associées à la composition, encodées en JSON.
     * @return bool Retourne true si la mise à jour a réussi, sinon false.
     */
    public function updateComposition($id, $nom, $description, $nombre_joueurs, $cartes)
    {
        $cartes_json = json_encode($cartes);
        $stmt = $this->pdo->prepare('UPDATE compositions SET nom = ?, description = ?, nombre_joueurs = ?, cartes = ? WHERE id = ?');
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $id]);
    }

    /**
     * Supprime une composition par son identifiant.
     * 
     * @param int $id Identifiant de la composition à supprimer.
     * @return bool Retourne true si la suppression a réussi, sinon false.
     */
    public function deleteComposition($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM compositions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Ajoute un "like" pour une composition donnée.
     * 
     * @param int $compositionId Identifiant de la composition.
     * @param int $userId Identifiant de l'utilisateur qui ajoute le like.
     * @return bool Retourne true si le like a été ajouté, sinon false.
     */
    public function likeComposition($compositionId, $userId) {
        if (!$this->hasUserLiked($compositionId, $userId)) {
            $stmt = $this->pdo->prepare('INSERT INTO likes (composition_id, user_id, type) VALUES (?, ?, "like")');
            return $stmt->execute([$compositionId, $userId]);
        }
        return false;
    }

    /**
     * Vérifie si un utilisateur a déjà liké une composition.
     * 
     * @param int $compositionId Identifiant de la composition.
     * @param int $userId Identifiant de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a déjà liké la composition, sinon false.
     */
    public function hasUserLiked($compositionId, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT id FROM likes WHERE composition_id = ? AND user_id = ? AND type = "like"');
        $stmt->execute([$compositionId, $userId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Récupère les 5 compositions les plus aimées.
     * 
     * @return array Retourne un tableau associatif contenant les 5 compositions les plus aimées.
     */
    public function getTopLikedCompositions()
    {
        $stmt = $this->pdo->query('
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            ORDER BY likes DESC
            LIMIT 5
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les compositions triées par ordre alphabétique.
     * 
     * @return array Retourne un tableau associatif contenant toutes les compositions triées par nom.
     */
    public function getAllCompositionsAlphabetical() {
        $stmt = $this->pdo->query('
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            ORDER BY c.nom ASC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtre les compositions par nombre de joueurs.
     * 
     * @param int $nombre_joueurs Nombre de joueurs pour filtrer les compositions.
     * @return array Retourne un tableau associatif contenant les compositions filtrées par nombre de joueurs.
     */
    public function filterByNumberOfPlayers($nombre_joueurs)
    {
        $stmt = $this->pdo->prepare('
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            WHERE c.nombre_joueurs = ?
            ORDER BY c.nom ASC
        ');
        $stmt->execute([$nombre_joueurs]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtre les compositions par un ensemble de cartes spécifiques.
     * 
     * @param array $cardIds Tableau des identifiants des cartes pour filtrer les compositions.
     * @return array Retourne un tableau associatif contenant les compositions filtrées par les cartes.
     */
    public function filterByExactCards($cardIds)
    {
        $query = '
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            WHERE 1=1
        ';

        // Ajouter une condition pour chaque carte sélectionnée
        if (!empty($cardIds)) {
            foreach ($cardIds as $cardId) {
                $query .= ' AND CAST(JSON_UNQUOTE(JSON_EXTRACT(c.cartes, \'$."' . $cardId . '"\')) AS UNSIGNED) > 0';
            }
        }

        $query .= ' ORDER BY c.nom ASC';

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtre les compositions par cartes et nombre de joueurs.
     * 
     * @param array $cardIds Tableau des identifiants des cartes pour filtrer les compositions.
     * @param int $nombre_joueurs Nombre de joueurs pour filtrer les compositions.
     * @return array Retourne un tableau associatif contenant les compositions filtrées par cartes et nombre de joueurs.
     */
    public function filterByCardsAndPlayers($cardIds, $nombre_joueurs)
    {
        $query = '
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            WHERE c.nombre_joueurs = :nombre_joueurs
        ';

        // Ajouter une condition pour chaque carte sélectionnée
        if (!empty($cardIds)) {
            foreach ($cardIds as $cardId) {
                $query .= ' AND CAST(JSON_UNQUOTE(JSON_EXTRACT(c.cartes, \'$."' . $cardId . '"\')) AS UNSIGNED) > 0';
            }
        }

        $query .= ' ORDER BY c.nom ASC';

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nombre_joueurs' => $nombre_joueurs]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si un utilisateur est l'auteur d'une composition donnée.
     * 
     * @param int $userId Identifiant de l'utilisateur.
     * @param int $compositionId Identifiant de la composition.
     * @return bool Retourne true si l'utilisateur est l'auteur, sinon false.
     */
    public function isAuthor($userId, $compositionId)
    {
        $stmt = $this->pdo->prepare('SELECT utilisateur_id FROM compositions WHERE id = ?');
        $stmt->execute([$compositionId]);
        $composition = $stmt->fetch();
        
        return $composition && $composition['utilisateur_id'] == $userId;
    }

    /**
     * Récupère toutes les cartes disponibles.
     * 
     * @return array Retourne un tableau associatif contenant toutes les cartes.
     */
    public function getAllCartes()
    {
        $stmt = $this->pdo->query('SELECT * FROM cartes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
