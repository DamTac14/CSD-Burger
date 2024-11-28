<?php  

namespace Controllers;


use PDO;
use Exception;
include_once __DIR__ . '/../database/database.php'; 
class MenuController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function uploadImage($image) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($image['name']);
    
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $validExtensions)) {
            throw new Exception("Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.");
        }
    
        if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
            throw new Exception("Failed to upload image.");
        }
    
        return $targetFile;
    }
    

    public function addMenu($name, $image, $dishIds) {
        try {
            $this->pdo->beginTransaction();
    
            $imagePath = $this->uploadImage($image);
    
            $stmt = $this->pdo->prepare("INSERT INTO menu (name, image) VALUES (:name, :image)");
            $stmt->execute([':name' => $name, ':image' => $imagePath]);
            $menuId = $this->pdo->lastInsertId();
    
            if (!is_array($dishIds) || empty($dishIds)) {
                throw new Exception("The list of dishes is empty or invalid.");
            }
    
            $linkStmt = $this->pdo->prepare("INSERT INTO menu_dish (id_menu, id_dish) VALUES (:id_menu, :id_dish)");
            foreach ($dishIds as $dishId) {
                if (!is_numeric($dishId) || $dishId <= 0) {
                    throw new Exception("Invalid dish ID: $dishId");
                }
                $linkStmt->execute([':id_menu' => $menuId, ':id_dish' => $dishId]);
            }
    
            $this->pdo->commit();
    
            return $menuId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error adding menu: " . $e->getMessage());
            throw $e;
        }
    }
    

    public function showMenu() {
        $sql = "SELECT * FROM menu";
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
