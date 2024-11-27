<?php 

include_once '../database/database.php';

class EmployeeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addEmployee($first_name, $last_name, $user_name, $role, $password, $is_active, $hire_date, $departure_date) {
        $sql = "INSERT INTO employees (first_name, last_name, user_name, role, password, is_active, hire_date, departure_date) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$first_name, $last_name, $user_name, $role, $hashedPassword, $is_active, $hire_date, $departure_date]);
        return true;
    }

    public function showEmployee() {
        $sql = "SELECT * FROM employees";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $employees;
    }

    public function updateEmployee($id, $last_name, $first_name, $user_name, $role, $password, $is_active, $hire_date, $departure_date) {
        $sql = "UPDATE employees 
                SET  first_name = ?, last_name = ?, user_name = ?, role = ?, password = ?, is_active = ?, hire_date = ?, departure_date = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $stmt->execute([$first_name, $last_name, $user_name, $role, $hashedPassword, $is_active, $hire_date, $departure_date, $id]);
        return $result; 
    }

    public function deleteEmployee($id) {
        $sql = "DELETE FROM employees WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
