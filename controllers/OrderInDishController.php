<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';

class OrderInDishController {
    private $pdo;
    
    // Injection de la dépendance PDO via le constructeur
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode de création qui prend un tableau de données
    public function create($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['ingredients']) || !isset($data['quantity']) || !isset($data['status']) || !isset($data['additional'])) {
            return ['error' => 'Missing parameters'];
        }

        $ingredients = $data['ingredients'];
        $quantity = $data['quantity'];
        $status = $data['status'];
        $additional = $data['additional'];

        // Insertion dans la base de données
        $sql = "INSERT INTO order_in_dish (ingredients, quantity, status, additional) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ingredients, $quantity, $status, $additional]);

        return ['success' => true];
    }

    // Méthode getAll pour récupérer tous les éléments
    public function getAll() {
        $sql = "SELECT * FROM order_in_dish";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

// Méthode getById pour récupérer un élément par son id
public function getById($data) {
    // Vérification que l'ID est bien fourni dans les données
    if (!isset($data['id'])) {
        return ['error' => 'Missing ID'];
    }

    $id = $data['id'];

    // Requête pour récupérer l'élément avec l'ID spécifié
    $sql = "SELECT * FROM order_in_dish WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    
    // Récupération et renvoi de l'élément trouvé, ou une erreur si non trouvé
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($dish) {
        return $dish;
    } else {
        return ['error' => 'Item not found'];
    }
}


    // Méthode update qui prend un tableau de données
    public function update($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['id']) || !isset($data['ingredients']) || !isset($data['quantity']) || !isset($data['status']) || !isset($data['additional'])) {
            return ['error' => 'Missing parameters'];
        }

        $id = $data['id'];
        $ingredients = $data['ingredients'];
        $quantity = $data['quantity'];
        $status = $data['status'];
        $additional = $data['additional'];

        // Mise à jour dans la base de données
        $sql = "UPDATE order_in_dish SET ingredients = ?, quantity = ?, status = ?, additional = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ingredients, $quantity, $status, $additional, $id]);

        return ['success' => true];
    }

    // Méthode delete qui prend un tableau de données
    public function delete($data) {
        // Vérification que l'ID est fourni
        if (!isset($data['id'])) {
            return ['error' => 'Missing ID'];
        }

        // Suppression dans la base de données
        $sql = "DELETE FROM order_in_dish WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['id']]);

        return ['success' => true];
    }
}
