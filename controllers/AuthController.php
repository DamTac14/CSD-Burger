<?php  

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($userName, $password) {
        $sql = "SELECT * FROM employees WHERE user_name = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userName]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee && password_verify($password, $employee['password'])) {
            return $employee; 
        }
        return false;
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['id']);
        return true;
    }

    public function isLoggedIn() {
        return isset($_SESSION['id']);
    }
}

