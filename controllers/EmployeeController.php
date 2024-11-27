<?php 

namespace Controllers;

use PDO;
include_once '../database/database.php';


class EmployeeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addEmployee($firstName, $lastName, $userName, $role, $password, $isActive, $hireDate, $departureDate) {
        $sql = "INSERT INTO employees (firstName, lastName, userName, role, password, isActive, hireDate, departureDate) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$firstName, $lastName, $userName, $role, $hashedPassword, $isActive, $hireDate, $departureDate]);
        return true;
    }

    public function showEmployee() {
        $sql = "SELECT * FROM employees";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $employee = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $employee;
    }

    public function updateEmployee($id, $lastName, $firstName, $userName, $role, $password, $isActive, $hireDate, $departureDate) {
        $sql = "UPDATE employees 
                SET  firstName = ?, lastName = ?, userName = ?, role = ?, password = ?, isActive = ?, hireDate = ?, departureDate = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $stmt->execute([$firstName, $lastName, $userName, $role, $hashedPassword, $isActive, $hireDate, $departureDate, $id]);
        return $result; 
    }

    public function deleteEmployee($id) {
        $sql = "DELETE FROM employees WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
