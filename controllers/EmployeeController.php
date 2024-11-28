<?php 

namespace Controllers;

use PDO;
use Exception;
include_once __DIR__ . '/../database/database.php';

class EmployeeController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour créer un employé
    public function create($data) {
        if (!isset($data['firstName'], $data['lastName'], $data['userName'], $data['role'], $data['password'], $data['isActive'], $data['hireDate'], $data['departureDate'])) {
            throw new Exception("Missing parameters");
        }

        $sql = "INSERT INTO employees (firstName, lastName, userName, role, password, isActive, hireDate, departureDate) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->execute([$data['firstName'], $data['lastName'], $data['userName'], $data['role'], $hashedPassword, $data['isActive'], $data['hireDate'], $data['departureDate']]);
        return true;
    }

    // Méthode pour récupérer tous les employés
    public function getAll() {
        $sql = "SELECT * FROM employees";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $employees;
    }

    // Méthode pour récupérer un employé par ID
    public function getById($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        $sql = "SELECT * FROM employees WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($employee) {
            return $employee;
        } else {
            throw new Exception("Employee not found");
        }
    }

    // Méthode pour mettre à jour un employé
    public function update($data) {
        if (!isset($data['id'], $data['firstName'], $data['lastName'], $data['userName'], $data['role'], $data['password'], $data['isActive'], $data['hireDate'], $data['departureDate'])) {
            throw new Exception("Missing parameters");
        }

        $sql = "UPDATE employees 
                SET firstName = ?, lastName = ?, userName = ?, role = ?, password = ?, isActive = ?, hireDate = ?, departureDate = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([$data['firstName'], $data['lastName'], $data['userName'], $data['role'], $hashedPassword, $data['isActive'], $data['hireDate'], $data['departureDate'], $data['id']]);
    }

    // Méthode pour supprimer un employé
    public function delete($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        $sql = "DELETE FROM employees WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
