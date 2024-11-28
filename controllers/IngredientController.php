<?php  

namespace Controllers;

use PDO;
use Exception;

include_once __DIR__ . '/../database/database.php';

class IngredientController {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour créer un ingrédient
    public function create($data) {
        if (!isset($data['name'], $data['allergens'], $data['price'])) {
            throw new Exception("Missing parameters");
        }

        $sql = "INSERT INTO ingredients (name, allergens, price) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['name'], $data['allergens'], $data['price']]);
        return true;
    }

    // Méthode pour récupérer tous les ingrédients
    public function getAll() {
        $sql = "SELECT * FROM ingredients";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $ingredients;
    }

    // Méthode pour récupérer un ingrédient par ID
    public function getById($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        $sql = "SELECT * FROM ingredients WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ingredient) {
            return $ingredient;
        } else {
            throw new Exception("Ingredient not found");
        }
    }

    // Méthode pour mettre à jour un ingrédient
    public function update($data) {
        if (!isset($data['id'], $data['name'], $data['allergens'], $data['price'])) {
            throw new Exception("Missing parameters");
        }

        $sql = "UPDATE ingredients SET name = ?, allergens = ?, price = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['name'], $data['allergens'], $data['price'], $data['id']]);
    }

    // Méthode pour supprimer un ingrédient
    public function delete($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }

        $id = $data['id'];

        $sql = "DELETE FROM ingredients WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}