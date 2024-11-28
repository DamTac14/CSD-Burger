<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';
class IngredientController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addIngredient($name, $allergens, $price) {
        $sql = "INSERT INTO Ingredient (name, allergens, price) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $allergens, $price]);
        return true;
    }

    public function showIngredient(): mixed {
        $sql = "SELECT * FROM Ingredient";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $ingredient = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $ingredient;
    }

    public function updateIngredient($id, $name, $allergens, $price) {
        $sql = "UPDATE Dish
                SET name = ?, allergens = ?, price = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$name, $allergens, $price, $id]);
        return $result; 
    }

    public function deleteIngredient($id) {
        $sql = "DELETE FROM Ingredient WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
