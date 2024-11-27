<?php  


class OrderController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addOrder($number, $items, $status, $orderDate, $takeAway) {
        $sql = "INSERT INTO orders (number, items, status, orderDate, takeAway) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$number, $items, $status, $orderDate, $takeAway]);
        return true;
    }

    public function showOrder() {
        $sql = "SELECT * FROM orders";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }

    public function updateOrder($id, $number, $items, $status, $orderDate, $takeAway) {
        $sql = "UPDATE orders
                SET number = ?, items = ?, status = ?, orderDate = ?, takeAway = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$number, $items, $orderDate, $takeAway, $id]);
        return $result; 
    }

    public function deleteOrder($id) {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}

