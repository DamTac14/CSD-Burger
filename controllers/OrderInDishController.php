<?php  

class OrderInDishControllerDishController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addOrderInDish($ingredients, $quantity, $status, $additional) {
        $sql = "INSERT INTO order_in_dish (ingredients, quantity, status, additional) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ingredients, $quantity, $status, $additional]);
        return true;
    }

    public function showOrderInDish(): mixed {
        $sql = "SELECT * FROM order_in_dish";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $dish = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dish;
    }

    public function updateDish($id, $ingredients, $quantity, $status, $additional) {
        $sql = "UPDATE order_in_dish
                SET order_in_dish = ?, quantity = ?, status = ?, additional = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$ingredients, $quantity, $status,$additional, $id]);
        return $result; 
    }

    public function deleteDish($id) {
        $sql = "DELETE FROM order_in_dish WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
