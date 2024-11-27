<?php
include_once '../controllers/IngredientController.php';
include_once '../database/database.php';

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
        $result = $ingredientController->addDish(
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
