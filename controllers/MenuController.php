<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';class MenuController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addMenu($name, $image, $includeDishes) {
        $sql = "INSERT INTO menus (name, image, includeDishes) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $image, $includeDishes]);
        return true;
    }

    public function showMenu() {
        $sql = "SELECT * FROM menus";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $menus;
    }

    public function updateMenu($id, $name, $image, $includeDishes) {
        $sql = "UPDATE menus 
                SET name = ?, image = ?, includeDishes = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$name, $image, $includeDishes, $id]);
        return $result; 
    }

    public function deleteMenu($id) {
        $sql = "DELETE FROM menus WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
