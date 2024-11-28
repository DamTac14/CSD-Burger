<?php  

namespace Controllers;

use PDO;
include_once __DIR__ . '/../database/database.php'; 

class OrderController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode de création qui prend un tableau de données
    public function create($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['number'], $data['items'], $data['status'], $data['orderDate'], $data['takeAway'])) {
            return ['error' => 'Missing parameters'];
        }

        $number = $data['number'];
        $items = $data['items'];
        $status = $data['status'];
        $orderDate = $data['orderDate'];
        $takeAway = $data['takeAway'];

        // Insertion dans la base de données
        $sql = "INSERT INTO `order` (number, items, status, orderDate, takeAway) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$number, $items, $status, $orderDate, $takeAway]);
        return ['success' => true];
    }

    // Méthode getAll pour récupérer tous les éléments
    public function getAll() {
        $sql = "
            SELECT 
                o.id AS order_id,
                o.number AS order_number,
                o.takeaway,
                o.status AS order_status,
                o.order_datetime,
                oi.id AS order_item_id,
                oi.name AS order_item_name,
                oi.options AS order_item_options,
                oi.id_menu AS menu_id
            FROM `order` o
            LEFT JOIN `order__item` oi ON o.id = oi.id_order
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode getById pour récupérer un élément par son ID
    public function getById($data) {
        // Vérification que l'ID est bien fourni dans les données
        if (!isset($data['id'])) {
            return ['error' => 'Missing ID'];
        }

        $id = $data['id'];

        // Requête pour récupérer l'élément avec l'ID spécifié
        $sql = "
            SELECT 
                o.id AS order_id,
                o.number AS order_number,
                o.takeaway,
                o.status AS order_status,
                o.order_datetime,
                oi.id AS order_item_id,
                oi.name AS order_item_name,
                oi.options AS order_item_options,
                oi.id_menu AS menu_id
            FROM `order` o
            LEFT JOIN `order__item` oi ON o.id = oi.id_order
            WHERE o.id = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        // Récupération et renvoi de l'élément trouvé, ou une erreur si non trouvé
        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($order) {
            return $order;
        } else {
            return ['error' => 'Order not found'];
        }
    }

    // Méthode de mise à jour qui prend un tableau de données
    public function update($data) {
        // Vérification des paramètres nécessaires
        if (!isset($data['id'], $data['number'], $data['items'], $data['status'], $data['orderDate'], $data['takeAway'])) {
            return ['error' => 'Missing parameters'];
        }

        $id = $data['id'];
        $number = $data['number'];
        $items = $data['items'];
        $status = $data['status'];
        $orderDate = $data['orderDate'];
        $takeAway = $data['takeAway'];

        // Mise à jour dans la base de données
        $sql = "UPDATE `order`
                SET number = ?, items = ?, status = ?, orderDate = ?, takeAway = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$number, $items, $status, $orderDate, $takeAway, $id]);
    }

    // Méthode de suppression qui prend un tableau de données
    public function delete($data) {
        // Vérification que l'ID est bien fourni dans les données
        if (!isset($data['id'])) {
            return ['error' => 'Missing ID'];
        }

        $id = $data['id'];

        // Suppression dans la base de données
        $sql = "DELETE FROM `order` WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
