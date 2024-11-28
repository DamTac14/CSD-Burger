<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php';

class StockController {
    private $pdo;

    // Le constructeur reçoit l'objet PDO via injection de dépendances
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode create qui prend un tableau associatif avec les données
    public function create($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['quantity']) || !isset($data['threshold'])) {
            return ['error' => 'Missing parameters'];
        }

        $quantity = $data['quantity'];
        $threshold = $data['threshold'];

        // Insertion dans la base de données
        $sql = "INSERT INTO stock (quantity, threshold) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$quantity, $threshold]);

        return ['success' => true];
    }

    // Méthode getAll pour récupérer tous les éléments
    public function getAll() {
        $sql = "SELECT * FROM stock";
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

        $sql = "SELECT * FROM stock WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode update pour mettre à jour un élément
    public function update($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['id']) || !isset($data['quantity']) || !isset($data['threshold'])) {
            return ['error' => 'Missing parameters'];
        }

        $sql = "UPDATE stock SET quantity = ?, threshold = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['quantity'], $data['threshold'], $data['id']]);

        return ['success' => true];
    }

    // Méthode delete pour supprimer un élément
    public function delete($data) {
        // Vérification que l'ID est fourni
        if (!isset($data['id'])) {
            return ['error' => 'Missing ID'];
        }

        $sql = "DELETE FROM stock WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['id']]);

        return ['success' => true];
    }
}
