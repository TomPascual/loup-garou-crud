<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Carte.php';

class CarteTest extends TestCase
{
    public function testGetAllCartes()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('query')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'nom' => 'Loup', 'categorie' => 'créature']
        ]);

        $carte = new Carte($pdo);
        $result = $carte->getAllCartes();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Loup', $result[0]['nom']);
    }

    public function testGetCarteById()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn(['id' => 2, 'nom' => 'Chasseur']);

        $carte = new Carte($pdo);
        $result = $carte->getCarteById(2);

        $this->assertIsArray($result);
        $this->assertEquals('Chasseur', $result['nom']);
    }

    public function testCreateCarte()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);

        $carte = new Carte($pdo);
        $result = $carte->createCarte('Voyante', 'Lit les rôles', 'voyante.jpg', 'spécial');

        $this->assertTrue($result);
    }

    public function testUpdateCarte()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);

        $carte = new Carte($pdo);
        $result = $carte->updateCarte(1, 'Loup-Garou', 'Aggressif', 'loup.jpg', 'ennemi');

        $this->assertTrue($result);
    }

    public function testDeleteCarte()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);

        $carte = new Carte($pdo);
        $result = $carte->deleteCarte(1);

        $this->assertTrue($result);
    }
}
