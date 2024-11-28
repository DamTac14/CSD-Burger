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

            $linkStmt = $this->pdo->prepare("INSERT INTO menu__dish (id_menu, id_dish) VALUES (:id_menu, :id_dish)");
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
    public function getAll() {
        // Récupérer tous les menus
        $sql = "SELECT id, name, image FROM menu";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if (!$menus) {
            throw new Exception("No menus found.");
        }
    
        $result = [];
    
        // Pour chaque menu, on calcule le prix total et on récupère les allergènes
        foreach ($menus as $menu) {
            // Calculer le prix du menu basé sur ses plats
            $menuId = $menu['id'];
            $totalCost = 0;
            $allergens = [];
    
            // Requête pour récupérer les plats associés au menu
            $dishSql = "
                SELECT 
                    d.id AS dish_id,
                    d.name AS dish_name
                FROM 
                    dish d
                LEFT JOIN 
                    menu__dish md ON d.id = md.id_dish
                WHERE 
                    md.id_menu = ?
            ";
    
            $dishStmt = $this->pdo->prepare($dishSql);
            $dishStmt->execute([$menuId]);
            $dishes = $dishStmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($dishes as $dish) {
                $dishId = $dish['dish_id'];
    
                // Récupérer les ingrédients et leurs allergènes pour ce plat
                $ingredientSql = "
                    SELECT 
                        i.price AS ingredient_price,
                        i.allergens AS ingredient_allergens
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
                $dishCost = 0;
                foreach ($ingredients as $ingredient) {
                    $dishCost += $ingredient['ingredient_price'];
    
                    // Ajouter les allergènes à la liste
                    if ($ingredient['ingredient_allergens']) {
                        $ingredientAllergens = json_decode($ingredient['ingredient_allergens'], true); // Si les allergènes sont stockés sous forme de JSON
                        foreach ($ingredientAllergens as $allergen) {
                            if (!in_array($allergen, $allergens)) {
                                $allergens[] = $allergen;  // Ajouter l'allergène si non présent
                            }
                        }
                    }
                }
    
                // Ajouter le coût du plat au total du menu
                $totalCost += $dishCost;
            }
    
            // Appliquer la TVA et la marge
            $tva = 0.10;  // 10% de TVA
            $marge = 0.20;  // 20% de marge
    
            $priceWithTva = $totalCost * (1 + $tva);
            $finalPrice = $priceWithTva * (1 + $marge);
    
            // Ajouter le menu avec son prix calculé et ses allergènes
            $result[] = [
                'id' => $menu['id'],
                'name' => $menu['name'],
                'image' => $menu['image'],
                'menu_price' => round($finalPrice, 2),  // Prix du menu avec TVA et marge
                'allergens' => $allergens,  // Liste des allergènes pour ce menu
            ];
        }
    
        return $result;
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
                menu__dish md ON m.id = md.id_menu
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
        $linkStmt = $this->pdo->prepare("DELETE FROM menu__dish WHERE id_menu = ?");
        $linkStmt->execute([$id]);

        foreach ($dishIds as $dishId) {
            $linkStmt = $this->pdo->prepare("INSERT INTO menu__dish (id_menu, id_dish) VALUES (?, ?)");
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

    public function getMenuWithCost($data) {
        if (!isset($data['id'])) {
            throw new Exception("Missing ID parameter");
        }
    
        $id = $data['id'];
    
        // Requête pour récupérer le menu avec les plats associés
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
                menu__dish md ON m.id = md.id_menu
            LEFT JOIN 
                dish d ON md.id_dish = d.id
            WHERE 
                m.id = ?
        ";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $menuWithDishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if (!$menuWithDishes) {
            throw new Exception("Menu not found or has no dishes.");
        }
    
        // Calculer le coût total des ingrédients du menu
        $totalCost = 0;
        foreach ($menuWithDishes as $dish) {
            $dishId = $dish['dish_id'];
    
            // Récupérer les ingrédients du plat
            $ingredientSql = "
                SELECT 
                    i.id AS ingredient_id, 
                    i.name AS ingredient_name, 
                    i.price AS ingredient_price
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
            $dishCost = 0;
            foreach ($ingredients as $ingredient) {
                $dishCost += $ingredient['ingredient_price'];
            }
    
            // Ajouter le coût du plat au total du menu
            $totalCost += $dishCost;
        }
    
        // Appliquer la TVA de 10% et une marge légère de 20% (par exemple)
        $tva = 0.10;
        $marge = 0.20;
        $priceWithTva = $totalCost * (1 + $tva);
        $finalPrice = $priceWithTva * (1 + $marge);
    
        return [
            'menu_id' => $id,
            'menu_name' => $menuWithDishes[0]['menu_name'],
            'menu_image' => $menuWithDishes[0]['menu_image'],
            'total_cost' => $totalCost,
            'price_with_tva' => $priceWithTva,
            'final_price' => $finalPrice
        ];
    }
    
}
