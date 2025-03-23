<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Composition.php';

class CompositionTest extends TestCase
{
    public function testCreateComposition()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $pdo->method('lastInsertId')->willReturn("1");

        // Créer une instance de Composition
        $composition = new Composition($pdo);

        // Définir les données de test
        $nom = 'Nouvelle Composition';
        $description = 'Description test';
        $nombre_joueurs = 5;
        $cartes = [1, 2]; 
        $utilisateur_id = 1;

        // Tester la méthode createComposition
        $resultat = $composition->createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id);

        // Vérifier que la méthode renvoie "true"
        $this->assertTrue($resultat);
    }

    public function testFilterByCardsAndPlayers()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'nom' => 'Test Composition', 'nombre_joueurs' => 5]
        ]);

        $composition = new Composition($pdo);

        $cardIds = [1, 2];
        $nombre_joueurs = 5;

        $resultat = $composition->filterByCardsAndPlayers($cardIds, $nombre_joueurs);

        $this->assertCount(1, $resultat);
        $this->assertEquals('Test Composition', $resultat[0]['nom']);
    }

    public function testIsAuthor()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('fetch')->willReturn(['utilisateur_id' => 1]);

        $composition = new Composition($pdo);

        $userId = 1;
        $compositionId = 1;

        $resultat = $composition->isAuthor($userId, $compositionId);

        $this->assertTrue($resultat);
    }
    public function testDescriptionAvecMoinsDe5Caracteres()
{
    $this->expectException(Exception::class);
    $this->expectExceptionMessage("La description doit contenir au moins 5 caractères.");

    $pdo = $this->createMock(PDO::class);
    $composition = new Composition($pdo);

    $composition->createComposition("Nom valide", "abc", 5, [1, 2], 1);
}

public function testDescriptionAvec5Caracteres()
{
    $pdo = $this->createMock(PDO::class);
    $stmt = $this->createMock(PDOStatement::class);

    $pdo->method('prepare')->willReturn($stmt);
    $stmt->method('execute')->willReturn(true);
    $pdo->method('lastInsertId')->willReturn("1");

    $composition = new Composition($pdo);

    $resultat = $composition->createComposition("Nom valide", "abcde", 5, [1, 2], 1);

    $this->assertTrue($resultat);
}

}
