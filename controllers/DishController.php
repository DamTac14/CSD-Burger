<?php  

namespace App\Controllers;

use PDO;
include_once '../database/database.php';

class DishController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addDish($dishType, $name, $ingredients, $options) {
        $sql = "INSERT INTO dishs (dishType, name, ingredients, options) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dishType, $name, $ingredients, $options]);
        return true;
    }

    public function showDish(): mixed {
        $sql = "SELECT * FROM dishs";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $dish = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dish;
    }

    public function updateDish($id, $dishType, $name, $ingredients, $options) {
        $sql = "UPDATE dishs
                SET dishType = ?, name = ?, ingredients = ?, options = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$dishType, $name, $ingredients,$options, $id]);
        return $result; 
    }

    public function deleteDish($id) {
        $sql = "DELETE FROM dishs WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
