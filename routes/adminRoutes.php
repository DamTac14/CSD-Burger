<?php
include_once '../controllers/AdminController.php';
include_once '../database/database.php';

$pdo = getDB();
$adminController = new AdminController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/admins\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        $admins = $adminController->listerAdmins();
        echo json_encode($admins);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $adminController->ajouterAdmin($data);
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Admin ajouté avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => $result]);
        }
        break;

    case 'DELETE':
        if ($id) {
            $result = $adminController->supprimerAdmin($id);
            if ($result) {
                http_response_code(204);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Admin non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer un admin']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}
