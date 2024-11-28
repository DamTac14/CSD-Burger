<?php  

namespace Controllers;

use PDO;
use Exception;
include_once __DIR__ . '/../database/database.php'; 

class MenuController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode privée pour gérer l'upload d'image
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

    // Méthode create qui prend un tableau de données
    public function create($data) {
        try {
            if (!isset($data['name'], $data['image'], $data['dishIds'])) {
                throw new Exception("Missing parameters");
            }

            $this->pdo->beginTransaction();

            // Upload de l'image
            $imagePath = $this->uploadImage($data['image']);

            // Insertion du menu
            $stmt = $this->pdo->prepare("INSERT INTO menu (name, image) VALUES (:name, :image)");
            $stmt->execute([':name' => $data['name'], ':image' => $imagePath]);
            $menuId = $this->pdo->lastInsertId();

            // Vérification et insertion des plats liés au menu
            if (!is_array($data['dishIds']) || empty($data['dishIds'])) {
                throw new Exception("The list of dishes is empty or invalid.");
            }

            $linkStmt = $this->pdo->prepare("INSERT INTO menu_dish (id_menu, id_dish) VALUES (:id_menu, :id_dish)");
            foreach ($data['dishIds'] as $dishId) {
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

    // Méthode pour récupérer tous les menus
    public function getAll() {
        $sql = "SELECT * FROM menu";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $menus;
    }

    public function getMenuWithDishes($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }
    
        $id = $data['id'];
    
        $sql = "
            SELECT 
                m.id AS menu_id, 
                m.name AS menu_name, 
                m.image AS menu_image, 
                d.id AS dish_id, 
                d.name AS dish_name, 
                d.ingredients
            FROM 
                menu m
            LEFT JOIN 
                menu_dish md ON m.id = md.id_menu
            LEFT JOIN 
                dish d ON md.id_dish = d.id
            WHERE 
                m.id = ?
        ";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $menuWithDishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($menuWithDishes) {
            return $menuWithDishes;
        } else {
            throw new Exception("Menu or dishes not found");
        }
    }
    

    // Méthode pour récupérer un menu par ID
    public function getById($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        $sql = "SELECT * FROM menu WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($menu) {
            return $menu;
        } else {
            throw new Exception("Menu not found");
        }
    }

    // Méthode de mise à jour du menu
    public function update($data) {
        if (!isset($data['id'], $data['name'], $data['image'], $data['dishIds'])) {
            throw new Exception("Missing parameters");
        }

        $id = $data['id'];
        $name = $data['name'];
        $image = $data['image'];
        $dishIds = $data['dishIds'];

        // Upload de la nouvelle image si elle est présente
        $imagePath = $this->uploadImage($image);

        // Mise à jour du menu
        $sql = "UPDATE menu SET name = ?, image = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $imagePath, $id]);

        // Mise à jour des plats associés
        $linkStmt = $this->pdo->prepare("DELETE FROM menu_dish WHERE id_menu = ?");
        $linkStmt->execute([$id]);

        foreach ($dishIds as $dishId) {
            $linkStmt = $this->pdo->prepare("INSERT INTO menu_dish (id_menu, id_dish) VALUES (?, ?)");
            $linkStmt->execute([$id, $dishId]);
        }

        return true;
    }

    // Méthode de suppression du menu
    public function delete($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        // Suppression du menu et des plats associés
        $sql = "DELETE FROM menu WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return true;
    }
}
