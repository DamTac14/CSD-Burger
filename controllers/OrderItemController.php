<?php  

class OrderItemController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addOrderItem($name, $allergens, $price) {
        $sql = "INSERT INTO order_items (name) 
                VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name]);
        return true;
    }

    public function showOrderItem(): mixed {
        $sql = "SELECT * FROM order_items";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $menus;
    }

    public function updateIngredient($id, $name) {
        $sql = "UPDATE order_items
                SET name = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$name, $id]);
        return $result; 
    }

    public function deleteIngredient($id) {
        $sql = "DELETE FROM order_items WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
