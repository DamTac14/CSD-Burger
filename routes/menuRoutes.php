<?php
include_once '../controllers/MenuController.php';
include_once '../database/database.php';

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
