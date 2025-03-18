<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Carte.php';

class CarteTest extends TestCase
{
    public function testCreateCarte()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $pdo->method('lastInsertId')->willReturn("1");

        $carte = new Carte($pdo);

        $nom = 'Nouvelle Carte';
        $description = 'Description test';
        $photo = 'photo.jpg';
        $categorie = 'catégorie';

        $resultat = $carte->createCarte($nom, $description, $photo, $categorie);

        $this->assertTrue($resultat);
    }

    public function testGetCarteById()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn([
            'id' => 1,
            'nom' => 'Carte Test',
            'description' => 'Une description',
            'photo' => 'photo.jpg',
            'categorie' => 'catégorie'
        ]);

        $carte = new Carte($pdo);
        $resultat = $carte->getCarteById(1);

        $this->assertIsArray($resultat);
        $this->assertEquals('Carte Test', $resultat['nom']);
    }

    public function testDescriptionAvecMoinsDe5Caracteres()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("La description doit contenir au moins 5 caractères.");

        $pdo = $this->createMock(PDO::class);
        $carte = new Carte($pdo);

        $carte->createCarte("Nom valide", "abc", "photo.jpg", "catégorie");
    }

    public function testDescriptionAvec5Caracteres()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $pdo->method('lastInsertId')->willReturn("1");

        $carte = new Carte($pdo);

        $resultat = $carte->createCarte("Nom valide", "abcde", "photo.jpg", "catégorie");

        $this->assertTrue($resultat);
    }
}
