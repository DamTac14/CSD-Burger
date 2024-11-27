<?php  

class DishController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addDish($dish_type, $name, $ingredients, $options) {
        $sql = "INSERT INTO dishs (dish_type, name, ingredients, options) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dish_type, $name, $ingredients, $options]);
        return true;
    }

    public function showDish(): mixed {
        $sql = "SELECT * FROM dishs";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $menus;
    }

    public function updateDish($id, $dish_type, $name, $ingredients, $options) {
        $sql = "UPDATE dishs
                SET dish_type = ?, name = ?, ingredients = ?, options = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$dish_type, $name, $ingredients,$options, $id]);
        return $result; 
    }

    public function deleteDish($id) {
        $sql = "DELETE FROM dishs WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
