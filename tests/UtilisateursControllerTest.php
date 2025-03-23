<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../controllers/utilisateursController.php';

class UtilisateursControllerTest extends TestCase
{
    public function testLoginCallsModel()
    {
        $mockModel = $this->createMock(Utilisateur::class);
        $mockModel->expects($this->once())
                  ->method('login')
                  ->with('test@example.com', 'password');

        $controller = new UtilisateursController();

 
        $reflection = new ReflectionClass($controller);
        $property = $reflection->getProperty('utilisateurModel');
        $property->setAccessible(true);
        $property->setValue($controller, $mockModel);

      
        $controller->login([
            'identifiant' => 'test@example.com',
            'password' => 'password'
        ]);

      
    }
}
