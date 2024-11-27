<?php
include_once '../controllers/EmployeeController.php';
include_once '../database/database.php';

$pdo = getDB();
$employeeController = new EmployeeController($pdo);

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$id = null;
if (preg_match('/\/api\/employees\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
}

switch ($method) {
    case 'GET':
        // Liste tous les employés
        $employees = $employeeController->showEmployee();
        echo json_encode($employees);
        break;

    case 'POST':
        // Ajouter un employé
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $employeeController->addEmployee(
            $data['firstName'],
            $data['lastName'],
            $data['userName'],
            $data['role'],
            $data['password'],
            $data['isActive'],
            $data['hireDate'],
            $data['departureDate'] ?? null // Gestion de la date de départ optionnelle
        );
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['message' => 'Employé ajouté avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'ajout']);
        }
        break;

    case 'PUT':
        // Mettre à jour un employé
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $employeeController->updateEmployee(
                $id,
                $data['lastName'],
                $data['firstName'],
                $data['userName'],
                $data['role'],
                $data['password'],
                $data['isActive'],
                $data['hireDate'],
                $data['departureDate'] ?? null
            );
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Employé mis à jour']);
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
        // Supprimer un employé
        if ($id) {
            $result = $employeeController->deleteEmployee($id);
            if ($result) {
                http_response_code(204);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Employé non trouvé']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requis pour supprimer un employé']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non supportée']);
}
