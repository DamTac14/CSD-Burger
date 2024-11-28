<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';
class StockController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addStock($quantity, $threshold) {
        $sql = "INSERT INTO Stock (quantity, threshold) 
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$quantity, $threshold]);
        return true;
    }

    public function showStock() {
        $sql = "SELECT * FROM Stock";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $stock;
    }

    public function updateStock($id, $quantity, $threshold) {
        $sql = "UPDATE Stock
                SET quantity = ?, threshold = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$quantity, $threshold, $id]);
        return $result; 
    }

    public function deleteStock($id) {
        $sql = "DELETE FROM Stock WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
