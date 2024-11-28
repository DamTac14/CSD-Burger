<?php  

namespace Controllers;

use PDO;
use Exception;
include_once __DIR__ . '/../database/database.php';

class DishController {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour créer un plat
    public function create($data) {
        if (!isset($data['type'], $data['name'], $data['ingredients'])) {
            throw new Exception("Missing parameters");
        }

        $sql = "INSERT INTO dish (type, name, ingredients) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['type'], $data['name'], $data['ingredients']]);
        return true;
    }

    // Méthode pour récupérer tous les plats
    public function getAll() {
        $sql = "SELECT * FROM dish";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dishes;
    }

    // Méthode pour récupérer un plat par ID
    public function getById($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        $sql = "SELECT * FROM dish WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $dish = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dish) {
            return $dish;
        } else {
            throw new Exception("Dish not found");
        }
    }

    // Méthode pour mettre à jour un plat
    public function update($data) {
        if (!isset($data['id'], $data['type'], $data['name'], $data['ingredients'])) {
            throw new Exception("Missing parameters");
        }

        $sql = "UPDATE dish 
                SET type = ?, name = ?, ingredients = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['type'], $data['name'], $data['ingredients'], $data['id']]);
    }

    // Méthode pour supprimer un plat
    public function delete($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        $sql = "DELETE FROM dish WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
