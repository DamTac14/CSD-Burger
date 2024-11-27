<?php

use Controllers\OrderController;
include_once '../database/database.php';
include_once '../controllers/OrderController.php';


$orderController = new OrderController(getDB());

$orders = $orderController->showOrder();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des Commandes</title>
</head>
<body>
    <h1>Liste des Commandes</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>À emporter</th>
                <th>Statut</th>
                <th>Date de commande</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['number']) ?></td>
                    <td><?= htmlspecialchars($order['takeaway'] == 1 ? 'Oui' : 'Non') ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= htmlspecialchars($order['order_datetime']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

