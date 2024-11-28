<?php

require_once 'controllers/DishController.php';
require_once 'controllers/IngredientController.php';
require_once 'controllers/MenuController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/OrderItemController.php';
require_once 'controllers/StockController.php';

use Controllers\DishController;
use Controllers\IngredientController;
use Controllers\MenuController;
use Controllers\OrderController;
use Controllers\OrderItemController;
use Controllers\StockController;

// Database connection
require_once 'database/database.php';
$pdo = getDB();

// Parse the request
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = str_replace('/CSDBurger/CSD-Burger', '', $requestUri); // Ajustez en fonction du chemin local
$method = $_SERVER['REQUEST_METHOD'];


// Match routes
if (preg_match('/^\/api\/dishes(\/\d+)?$/', $requestUri, $matches)) {
    $controller = new DishController($pdo);
    $id = $matches[1] ?? null;
    handleRequest($controller, $method, $id);
} elseif (preg_match('/^\/api\/ingredients(\/\d+)?$/', $requestUri, $matches)) {
    $controller = new IngredientController($pdo);
    $id = $matches[1] ?? null;
    handleRequest($controller, $method, $id);
} elseif (preg_match('/^\/api\/menus(\/\d+)?$/', $requestUri, $matches)) {
    $controller = new MenuController($pdo);
    $id = $matches[1] ?? null;
    handleRequest($controller, $method, $id);
} elseif (preg_match('/^\/api\/orders(\/\d+)?$/', $requestUri, $matches)) {
    $controller = new OrderController($pdo);
    $id = $matches[1] ?? null;
    handleRequest($controller, $method, $id);
} elseif (preg_match('/^\/api\/order-items(\/\d+)?$/', $requestUri, $matches)) {
    $controller = new OrderItemController($pdo);
    $id = $matches[1] ?? null;
    handleRequest($controller, $method, $id);
} elseif (preg_match('/^\/api\/stocks(\/\d+)?$/', $requestUri, $matches)) {
    $controller = new StockController($pdo);
    $id = $matches[1] ?? null;
    handleRequest($controller, $method, $id);
} else {
    // Route not found
    http_response_code(404);
    echo json_encode(['error' => 'Route non trouvée']);
}

/**
 * Handle the request by invoking the appropriate controller methods.
 */
function handleRequest($controller, $method, $id)
{
    switch ($method) {
        case 'GET':
            $data = $id ? $controller->getById($id) : $controller->getAll();
            echo json_encode($data);
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $controller->create($data);
            http_response_code(201);
            echo json_encode($result);
            break;
        case 'PUT':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'ID requis pour la mise à jour']);
                return;
            }
<<<<<<< HEAD
=======
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour la mise à jour']);
        }
        break;

    case 'DELETE':
        // Supprimer un ingrédient
        if ($id) {
            $result = $ingredientController->deleteIngredient($id);
            if ($result) {
                http_response_code(204); // Suppression réussie
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ingrédient non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer un ingrédient']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}

 

$pdo = getDB();
$menuController = new MenuController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/menus\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        // Liste tous les menus
        $menus = $menuController->showMenu();
        echo json_encode($menus);
        break;

    case 'POST':
        // Ajouter un menu
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $menuController->addMenu(
            $data['name'],
            $data['image'],
            $data['dishIds'] // Liste des plats à inclure dans le menu
        );
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Menu ajouté avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'ajout du menu']);
        }
        break;

    case 'PUT':
        // Mettre à jour un menu
        if ($id) {
>>>>>>> 646e677fa076c0abbadb14a6651939791758ea72
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $controller->update($id, $data);
            echo json_encode($result);
            break;
        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'ID requis pour la suppression']);
                return;
            }
            $result = $controller->delete($id);
            http_response_code(204);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non supportée']);
    }
}
