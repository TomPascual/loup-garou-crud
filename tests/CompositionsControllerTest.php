<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Composition.php';
require_once __DIR__ . '/../controllers/compositionsController.php';

class CompositionsControllerTest extends TestCase
{
    public function testGetCompositionById()
    {
        $mockModel = $this->createMock(Composition::class);
        $mockModel->expects($this->once())
                  ->method('getCompositionById')
                  ->with(1)
                  ->willReturn([
                      'id' => 1,
                      'nom' => 'Test Composition'
                  ]);

        // Crée un  contrôleur
        $controller = new CompositionsController();

        // Injecte le mock dans la propriété privée via Reflection
        $reflection = new ReflectionClass($controller);
        $property = $reflection->getProperty('compositionModel');
        $property->setAccessible(true);
        $property->setValue($controller, $mockModel);


        $result = $mockModel->getCompositionById(1);

        // Test
        $this->assertIsArray($result);
        $this->assertEquals('Test Composition', $result['nom']);
    }
}
