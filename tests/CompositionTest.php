<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Composition.php';
class CompositionTest extends TestCase
{
    // Test de la création d'une nouvelle composition
    public function testCreateComposition()
    {
        // Mocker PDO pour simuler la connexion à la base de données
        $pdo = $this->createMock(PDO::class);

        // Simuler l'objet PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);

        // Simuler le succès de l'exécution
        $stmt->method('execute')->willReturn(true);

        // Créer une instance de Composition
        $composition = new Composition($pdo);

        // Définir les données de test
        $nom = 'Nouvelle Composition';
        $description = 'Description test';
        $nombre_joueurs = 5;
        $cartes = ['carte1', 'carte2'];
        $utilisateur_id = 1;

        // Tester la méthode createComposition
        $resultat = $composition->createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id);

        // Vérifier que la méthode renvoie "true"
        $this->assertTrue($resultat);
    }

    // Test du filtre des compositions par cartes et nombre de joueurs
    public function testFilterByCardsAndPlayers()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);

        // Simuler PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);

        // Simuler le retour des résultats
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'nom' => 'Test Composition', 'nombre_joueurs' => 5]
        ]);

        // Créer une instance de Composition
        $composition = new Composition($pdo);

        // Tester avec des cartes spécifiques et un nombre de joueurs
        $cardIds = [1, 2];
        $nombre_joueurs = 5;

        // Appeler la méthode et récupérer le résultat
        $resultat = $composition->filterByCardsAndPlayers($cardIds, $nombre_joueurs);

        // Vérifier que le nombre de résultats est correct
        $this->assertCount(1, $resultat);
        $this->assertEquals('Test Composition', $resultat[0]['nom']);
    }

    // Test pour vérifier si un utilisateur est l'auteur d'une composition
    public function testIsAuthor()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);

        // Simuler PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);

        // Simuler le retour des résultats
        $stmt->method('fetch')->willReturn(['utilisateur_id' => 1]);

        // Créer une instance de Composition
        $composition = new Composition($pdo);

        // Tester si l'utilisateur est l'auteur
        $userId = 1;
        $compositionId = 1;

        $resultat = $composition->isAuthor($userId, $compositionId);

        // Vérifier que le résultat est vrai
        $this->assertTrue($resultat);
    }
}
