<?php  

class IngredientController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addDish($name, $allergens, $price) {
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
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $menus;
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
