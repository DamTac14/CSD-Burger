<?php
include_once '../controllers/DishController.php';
include_once '../database/database.php';

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
