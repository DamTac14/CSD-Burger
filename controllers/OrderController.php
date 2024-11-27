<?php  

namespace Controllers;

use PDO;

class OrderController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addOrder($number, $items, $status, $orderDate, $takeAway) {
        $sql = "INSERT INTO `order` (number, items, status, orderDate, takeAway) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$number, $items, $status, $orderDate, $takeAway]);
        return true;
    }

    public function showOrder() {
        $sql = "SELECT * FROM `order`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrder($id, $number, $items, $status, $orderDate, $takeAway) {
        $sql = "UPDATE `order`
                SET number = ?, items = ?, status = ?, orderDate = ?, takeAway = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$number, $items, $status, $orderDate, $takeAway, $id]);
    }

    public function deleteOrder($id) {
        $sql = "DELETE FROM `order` WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
