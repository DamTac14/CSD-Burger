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

// Méthode pour récupérer tous les plats avec calcul du prix basé sur les ingrédients
public function getAll() {
    // Récupérer tous les plats avec leur type
    $sql = "SELECT id, name, type FROM dish";  // Ajout du champ 'type'
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$dishes) {
        throw new Exception("No dishes found.");
    }

    $result = [];

    // Pour chaque plat, on calcule le prix basé sur ses ingrédients et on récupère la liste des ingrédients
    foreach ($dishes as $dish) {
        $dishId = $dish['id'];
        $totalCost = 0;

        // Récupérer les ingrédients et leurs prix pour ce plat
        $ingredientSql = "
            SELECT 
                i.name AS ingredient_name, i.price AS ingredient_price
            FROM 
                ingredient i
            LEFT JOIN 
                dish__ingredient di ON i.id = di.id_ingredient
            WHERE 
                di.id_dish = ?
        ";

        $ingredientStmt = $this->pdo->prepare($ingredientSql);
        $ingredientStmt->execute([$dishId]);
        $ingredients = $ingredientStmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculer le coût total des ingrédients pour ce plat
        foreach ($ingredients as $ingredient) {
            $totalCost += $ingredient['ingredient_price'];
        }

        // Appliquer la TVA et la marge
        $tva = 0.10;  // 10% de TVA
        $marge = 0.20;  // 20% de marge

        $priceWithTva = $totalCost * (1 + $tva);
        $finalPrice = $priceWithTva * (1 + $marge);

        // Ajouter le plat avec son prix calculé, son type et la liste des ingrédients
        $result[] = [
            'id' => $dish['id'],
            'name' => $dish['name'],
            'type' => $dish['type'],  // Ajout du type
            'dish_price' => round($finalPrice, 2),  // Prix du plat avec TVA et marge
            'ingredients' => $ingredients,  // Liste des ingrédients
        ];
    }

    return $result;
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
