<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';
class DishController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addDish($dishType, $name, $ingredients, $options) {
        $sql = "INSERT INTO dish (dishType, name, ingredients, options) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dishType, $name, $ingredients, $options]);
        return true;
    }

    public function showDish(): mixed {
        $sql = "SELECT * FROM dish";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $dish = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dish;
    }

    public function updateDish($id, $dishType, $name, $ingredients, $options) {
        $sql = "UPDATE dish
                SET dishType = ?, name = ?, ingredients = ?, options = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$dishType, $name, $ingredients,$options, $id]);
        return $result; 
    }

    public function deleteDish($id) {
        $sql = "DELETE FROM dish WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
