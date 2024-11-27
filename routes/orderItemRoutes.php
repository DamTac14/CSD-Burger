<?php
include_once '../controllers/OrderItemController.php';
include_once '../database/database.php';

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
