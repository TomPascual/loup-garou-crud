<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Carte.php';
require_once __DIR__ . '/../controllers/cartesController.php';

class CartesControllerTest extends TestCase
{
    public function testGetCarteByIdCallsModel()
    {
        $mockModel = $this->createMock(Carte::class);
        $mockModel->expects($this->once())
                  ->method('getCarteById')
                  ->with(1)
                  ->willReturn(['id' => 1, 'nom' => 'Loup']);

        $controller = new CartesController();


        $reflection = new ReflectionClass($controller);
        $property = $reflection->getProperty('carteModel');
        $property->setAccessible(true);
        $property->setValue($controller, $mockModel);

        $result = $controller->getCarteById(1);

        $this->assertIsArray($result);
        $this->assertEquals('Loup', $result['nom']);
    }
}
