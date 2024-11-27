<?php
include_once '../controllers/OrderController.php';
include_once '../database/database.php';

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
