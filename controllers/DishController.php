<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';
class DishController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addDish($type, $name, $ingredients) {
        $sql = "INSERT INTO dish (type, name, ingredients) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$type, $name, $ingredients]);
        return true;
    }

    public function showDish(): mixed {
        $sql = "SELECT * FROM dish";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $dish = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dish;
    }

    public function updateDish($id, $type, $name, $ingredients) {
        $sql = "UPDATE dish 
                SET type = ?, name = ?, ingredients = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$type, $name, $ingredients, $id]);
        return $result; 
    }

    public function deleteDish($id) {
        $sql = "DELETE FROM dish WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}

