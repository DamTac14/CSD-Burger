<?php  

namespace App\Controllers;

use PDO;
include_once '../database/database.php';

class IngredientController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addIngredient($name, $allergens, $price) {
        $sql = "INSERT INTO ingredients (name, allergens, price) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $allergens, $price]);
        return true;
    }

    public function showIngredient(): mixed {
        $sql = "SELECT * FROM ingredient";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $ingredient = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $ingredient;
    }

    public function updateIngredient($id, $name, $allergens, $price) {
        $sql = "UPDATE dishs
                SET name = ?, allergens = ?, price = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$name, $allergens, $price, $id]);
        return $result; 
    }

    public function deleteIngredient($id) {
        $sql = "DELETE FROM ingredients WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
