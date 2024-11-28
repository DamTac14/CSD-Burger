<?php

require_once 'controllers/DishController.php';
require_once 'controllers/IngredientController.php';
require_once 'controllers/MenuController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/OrderItemController.php';
require_once 'controllers/StockController.php';

use Controllers\DishController;
use Controllers\IngredientController;
use Controllers\OrderItemController;
use Controllers\MenuController;
use Controllers\OrderController;
use Controllers\StockController;

 

require_once 'database/database.php';

$pdo = getDB();
$dishController = new DishController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/dishes\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        // Liste tous les plats
        $dishes = $dishController->showDish();
        echo json_encode($dishes);
        break;

    case 'POST':
        // Ajouter un plat
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $dishController->addDish(
            $data['dish_type'],
            $data['name'],
            $data['ingredients'],
            $data['options']
        );
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Plat ajouté avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'ajout']);
        }
        break;

    case 'PUT':
        // Mettre à jour un plat
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $dishController->updateDish(
                $id,
                $data['dish_type'],
                $data['name'],
                $data['ingredients'],
                $data['options']
            );
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Plat mis à jour']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la mise à jour']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour la mise à jour']);
        }
        break;

    case 'DELETE':
        // Supprimer un plat
        if ($id) {
            $result = $dishController->deleteDish($id);
            if ($result) {
                http_response_code(204);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Plat non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer un plat']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}


 
 


$pdo = getDB();
$ingredientController = new IngredientController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/ingredients\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        // Liste tous les ingrédients
        $ingredients = $ingredientController->showIngredient();
        echo json_encode($ingredients);
        break;

    case 'POST':
        // Ajouter un ingrédient
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $ingredientController->addIngredient(
            $data['name'],
            $data['allergens'],
            $data['price']
        );
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Ingrédient ajouté avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'ingrédient']);
        }
        break;

    case 'PUT':
        // Mettre à jour un ingrédient
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $ingredientController->updateIngredient(
                $id,
                $data['name'],
                $data['allergens'],
                $data['price']
            );
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Ingrédient mis à jour']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la mise à jour']);
            }
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
            $data['includeDishes'] // Liste des plats à inclure dans le menu
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
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $menuController->updateMenu(
                $id,
                $data['name'],
                $data['image'],
                $data['includeDishes']
            );
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Menu mis à jour']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la mise à jour']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour la mise à jour']);
        }
        break;

    case 'DELETE':
        // Supprimer un menu
        if ($id) {
            $result = $menuController->deleteMenu($id);
            if ($result) {
                http_response_code(204); // Suppression réussie
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Menu non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer un menu']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}

  

$pdo = getDB();
$orderItemController = new OrderItemController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/order-items\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        // Liste tous les éléments de commande
        $orderItems = $orderItemController->showOrderItem();
        echo json_encode($orderItems);
        break;

    case 'POST':
        // Ajouter un élément de commande
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $orderItemController->addOrderItem(
            $data['name']
        );
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Élément de commande ajouté avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'élément de commande']);
        }
        break;

    case 'PUT':
        // Mettre à jour un élément de commande
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $orderItemController->updateIngredient(
                $id,
                $data['name']
            );
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Élément de commande mis à jour']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la mise à jour']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour la mise à jour']);
        }
        break;

    case 'DELETE':
        // Supprimer un élément de commande
        if ($id) {
            $result = $orderItemController->deleteIngredient($id);
            if ($result) {
                http_response_code(204); // Suppression réussie
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Élément de commande non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer un élément de commande']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}

  

$pdo = getDB();
$orderController = new OrderController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/orders\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        // Liste toutes les commandes
        $orders = $orderController->showOrder();
        echo json_encode($orders);
        break;

    case 'POST':
        // Ajouter une commande
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $orderController->addOrder(
            $data['number'],
            $data['items'],
            $data['status'],
            $data['orderDate'],
            $data['takeAway']
        );
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Commande ajoutée avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'ajout de la commande']);
        }
        break;

    case 'PUT':
        // Mettre à jour une commande
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $orderController->updateOrder(
                $id,
                $data['number'],
                $data['items'],
                $data['status'],
                $data['orderDate'],
                $data['takeAway']
            );
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Commande mise à jour']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la mise à jour']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour la mise à jour']);
        }
        break;

    case 'DELETE':
        // Supprimer une commande
        if ($id) {
            $result = $orderController->deleteOrder($id);
            if ($result) {
                http_response_code(204); // Suppression réussie
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Commande non trouvée']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer une commande']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}


$pdo = getDB();
$stockController = new StockController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/stocks\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        // Liste tous les stocks
        $stocks = $stockController->showStock();
        echo json_encode($stocks);
        break;

    case 'POST':
        // Ajouter un stock
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $stockController->addStock(
            $data['quantity'],
            $data['threshold']
        );
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Stock ajouté avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'ajout du stock']);
        }
        break;

    case 'PUT':
        // Mettre à jour un stock
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $stockController->updateStock(
                $id,
                $data['quantity'],
                $data['threshold']
            );
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Stock mis à jour']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la mise à jour']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour la mise à jour']);
        }
        break;

    case 'DELETE':
        // Supprimer un stock
        if ($id) {
            $result = $stockController->deleteStock($id);
            if ($result) {
                http_response_code(204); // Suppression réussie
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Stock non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer un stock']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}
