<?php  

namespace Controllers;
use PDO;
include_once __DIR__ . '/../database/database.php';
class OrderItemController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addOrderItem($name) {
        $sql = "INSERT INTO order__item (name) 
                VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name]);
        return true;
    }

    public function showOrderItem(): mixed {
        $sql = "SELECT * FROM order__item";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $orderItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orderItem;
    }

    public function updateIngredient($id, $name) {
        $sql = "UPDATE order__item
                SET name = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$name, $id]);
        return $result; 
    }

    public function deleteIngredient($id) {
        $sql = "DELETE FROM order__item WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
