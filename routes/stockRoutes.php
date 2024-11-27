<?php
include_once '../controllers/StockController.php';
include_once '../database/database.php';

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
