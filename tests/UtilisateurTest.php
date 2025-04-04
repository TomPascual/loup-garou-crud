<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

require_once __DIR__ . '/../models/Utilisateur.php';
class UtilisateurTest extends TestCase
{
    // Test de la méthode registerUser (inscription d'un utilisateur)
    public function testRegisterUser()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);
        
        // Mocker PDOStatement et lier au mock PDO
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);
        
        // Simuler que la méthode execute retourne true (insertion réussie)
        $stmt->method('execute')->willReturn(true);
        
        // Créer une instance de la classe Utilisateur avec le mock PDO
        $utilisateur = new Utilisateur($pdo);
        
        // Définir les valeurs de test
        $pseudo = 'TestUser';
        $email = 'testuser@example.com';
        $password = 'password';
        
        // Appeler la méthode registerUser et vérifier le résultat
        $resultat = $utilisateur->registerUser($pseudo, $email, $password);
        
        // Vérifier que le résultat est vrai
        $this->assertTrue($resultat);
    }

    // Test de la méthode login (connexion de l'utilisateur)
    public function testLoginSuccess()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);
        
        // Mocker PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);
        
        // Simuler les résultats de l'utilisateur trouvé
        $stmt->method('fetch')->willReturn([
            'id' => 1,
            'pseudo' => 'TestUser',
            'email' => 'testuser@example.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),  // Simuler un mot de passe haché
            'role' => 'user'
        ]);

        // Créer une instance de la classe Utilisateur avec le mock PDO
        $utilisateur = new Utilisateur($pdo);

        // Appeler la méthode login avec un mot de passe correct
        $identifiant = 'testuser@example.com';
        $password = 'password';
        $resultat = $utilisateur->login($identifiant, $password);

        // Vérifier que le résultat contient les informations de l'utilisateur
        $this->assertIsArray($resultat);
        $this->assertEquals('TestUser', $resultat['pseudo']);
    }

    // Test de la méthode login (échec de la connexion avec mauvais mot de passe)
    public function testLoginFailure()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);
        
        // Mocker PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);
        
        // Simuler les résultats de l'utilisateur trouvé mais avec un mauvais mot de passe
        $stmt->method('fetch')->willReturn([
            'id' => 1,
            'pseudo' => 'TestUser',
            'email' => 'testuser@example.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),  // Mot de passe haché dans la base
            'role' => 'user'
        ]);

        // Créer une instance de la classe Utilisateur avec le mock PDO
        $utilisateur = new Utilisateur($pdo);

        // Appeler la méthode login avec un mauvais mot de passe
        $identifiant = 'testuser@example.com';
        $password = 'wrongpassword';  // Mauvais mot de passe
        $resultat = $utilisateur->login($identifiant, $password);

        // Vérifier que le résultat est faux (échec de la connexion)
        $this->assertFalse($resultat);
    }

    // Test de la méthode isAdmin (vérifier si l'utilisateur est admin)
    public function testIsAdmin()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);

        // Mocker PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);

        // Simuler que l'utilisateur est admin
        $stmt->method('fetch')->willReturn([
            'role' => 'admin'
        ]);

        // Créer une instance de la classe Utilisateur avec le mock PDO
        $utilisateur = new Utilisateur($pdo);

        // Appeler la méthode isAdmin avec un ID utilisateur
        $userId = 1;
        $resultat = $utilisateur->isAdmin($userId);

        // Vérifier que le résultat est vrai (l'utilisateur est admin)
        $this->assertTrue($resultat);
    }

    // Test pour vérifier qu'un utilisateur n'est pas admin
    public function testIsNotAdmin()
    {
        // Mocker PDO
        $pdo = $this->createMock(PDO::class);

        // Mocker PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);

        // Simuler que l'utilisateur n'est pas admin
        $stmt->method('fetch')->willReturn([
            'role' => 'user'
        ]);

        // Créer une instance de la classe Utilisateur avec le mock PDO
        $utilisateur = new Utilisateur($pdo);

        // Appeler la méthode isAdmin avec un ID utilisateur
        $userId = 1;
        $resultat = $utilisateur->isAdmin($userId);

        // Vérifier que le résultat est faux (l'utilisateur n'est pas admin)
        $this->assertFalse($resultat);
    }

    // Test de la méthode getUtilisateurByPseudoOrEmail
public function testGetUtilisateurByPseudoOrEmail()
{
    $pdo = $this->createMock(PDO::class);
    $stmt = $this->createMock(PDOStatement::class);

    $pdo->method('prepare')->willReturn($stmt);
    $stmt->method('execute')->willReturn(true);
    $stmt->method('fetch')->willReturn([
        'id' => 1,
        'pseudo' => 'TestUser',
        'email' => 'test@example.com',
    ]);

    $utilisateur = new Utilisateur($pdo);
    $result = $utilisateur->getUtilisateurByPseudoOrEmail('test@example.com');

    $this->assertIsArray($result);
    $this->assertEquals('TestUser', $result['pseudo']);
}

// Test de la méthode login
public function testLogin()
{
    $pdo = $this->createMock(PDO::class);
    $utilisateurMock = $this->getMockBuilder(Utilisateur::class)
                            ->setConstructorArgs([$pdo])
                            ->onlyMethods(['getUtilisateurByPseudoOrEmail'])
                            ->getMock();

    $hashedPassword = password_hash('secret', PASSWORD_DEFAULT);

    $utilisateurMock->method('getUtilisateurByPseudoOrEmail')
                    ->willReturn([
                        'id' => 2,
                        'pseudo' => 'LoginTest',
                        'email' => 'login@example.com',
                        'password' => $hashedPassword
                    ]);

    $result = $utilisateurMock->login('login@example.com', 'secret');

    $this->assertIsArray($result);
    $this->assertEquals('LoginTest', $result['pseudo']);
}

// Test de la méthode isAdmin
public function testIsAdminReturnsTrue()
{
    $pdo = $this->createMock(PDO::class);
    $stmt = $this->createMock(PDOStatement::class);

    $pdo->method('prepare')->willReturn($stmt);
    $stmt->method('execute')->willReturn(true);
    $stmt->method('fetch')->willReturn(['role' => 'admin']);

    $utilisateur = new Utilisateur($pdo);
    $this->assertTrue($utilisateur->isAdmin(1));
}

// Test de la méthode emailExists
public function testEmailExists()
{
    $pdo = $this->createMock(PDO::class);
    $stmt = $this->createMock(PDOStatement::class);

    $pdo->method('prepare')->willReturn($stmt);
    $stmt->method('execute')->willReturn(true);
    $stmt->method('fetchColumn')->willReturn(1);

    $utilisateur = new Utilisateur($pdo);
    $this->assertTrue($utilisateur->emailExists('test@example.com'));
}

}
