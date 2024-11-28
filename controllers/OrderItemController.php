<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';

class OrderItemController {
    private $pdo;

    // Injection de la dépendance PDO dans le constructeur
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode de création qui prend un tableau associatif avec les données
    public function create($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['name'])) {
            return ['error' => 'Missing name'];
        }

        $name = $data['name'];

        // Insertion dans la base de données
        $sql = "INSERT INTO order__item (name) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name]);

        return ['success' => true];
    }

    // Méthode getAll pour récupérer tous les éléments
    public function getAll() {
        $sql = "SELECT * FROM order__item";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode getById pour récupérer un élément par son id
    public function getById($data) {
        // Vérification que l'ID est fourni dans les données
        if (!isset($data['id'])) {
            return ['error' => 'Missing ID'];
        }

        $sql = "SELECT * FROM order__item WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode update pour mettre à jour un élément
    public function update($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['id']) || !isset($data['name'])) {
            return ['error' => 'Missing parameters'];
        }

        $sql = "UPDATE order__item SET name = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['name'], $data['id']]);

        return ['success' => true];
    }

    // Méthode delete pour supprimer un élément
    public function delete($data) {
        // Vérification que l'ID est fourni
        if (!isset($data['id'])) {
            return ['error' => 'Missing ID'];
        }

        $sql = "DELETE FROM order__item WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['id']]);

        return ['success' => true];
    }
}
