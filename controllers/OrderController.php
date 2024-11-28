<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php'; 

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
        $sql = "
            SELECT 
                o.id AS order_id,
                o.number AS order_number,
                o.takeaway,
                o.status AS order_status,
                o.order_datetime,
                oi.id AS order_item_id,
                oi.name AS order_item_name,
                oi.image AS order_item_image,
                oi.options AS order_item_options,
                oi.id_menu AS menu_id
            FROM `order` o
            LEFT JOIN `order__item` oi ON o.id = oi.id_order
        ";
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
