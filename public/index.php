<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/CartesController.php';
require_once __DIR__ . '/../controllers/CompositionsController.php';
require_once __DIR__ . '/../controllers/UtilisateursController.php';

session_start();

$cartesController = new CartesController();
$compositionsController = new CompositionsController();
$utilisateursController = new UtilisateursController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'cartes':
        $cartes = $cartesController->index();
        include '../views/cartes/index.php';
        break;

    case 'create_carte':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartesController->create($_POST, $_FILES);
        } else {
            include '../views/cartes/create.php';
        }
        break;

    case 'edit_carte':
        if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartesController->edit($_GET['id'], $_POST, $_FILES);
        } else {
            $carte = $cartesController->getCarteById($_GET['id']);
            include '../views/cartes/edit.php';
        }
        break;

    case 'delete_carte':
        if (isset($_GET['id'])) {
            $cartesController->delete($_GET['id']);
        }
        break;

    case 'create_composition':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $compositionsController->create($_POST);
        } else {
            include '../views/compositions/create.php';
        }
        break;

    case 'edit_composition':
        if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $compositionsController->edit($_GET['id'], $_POST);
        } else {
            $composition = $compositionsController->getCompositionById($_GET['id']);
            include '../views/compositions/edit.php';
        }
        break;

    case 'delete_composition':
        if (isset($_GET['id'])) {
            $compositionsController->delete($_GET['id']);
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $utilisateursController->login($_POST);
        } else {
            include '../views/utilisateurs/login.php';
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $utilisateursController->register($_POST);
        } else {
            include '../views/utilisateurs/register.php';
        }
        break;

    case 'logout':
        $utilisateursController->logout();
        break;

    default:
        $compositions = $compositionsController->index();
        include '../views/compositions/index.php';
        break;
}
?>
