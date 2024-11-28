<?php

use Controllers\OrderController;

include_once '../database/database.php';
include_once '../controllers/OrderController.php';

$orderController = new OrderController(getDB());
$orders = $orderController->showOrder();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <link rel="stylesheet" href="ressource/assets/orders.css"> 
</head>

<body>
    <header class="header">
        <img src="ressource/images/logo.png" alt="logo-burger">
    </header>
    <h1>Tableau des commandes</h1>
    
    <div class="content">        
    <div class="order-container">
    <h1>Liste des Commandes</h1>
    <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <div class="order-number">
                #<?= htmlspecialchars($order['order_number']) ?>
            </div>
            <div class="order-content">
                <h3>Menu <?= htmlspecialchars($order['order_item_name']) ?></h3>
                <ul>
                <ul>
                        <?php
                        $options = explode(',', htmlspecialchars($order['order_item_options']));
                        foreach ($options as $option)
                            echo "<li>" . trim($option) . "</li>";
                        ?>
                    </ul>
                </ul>
                <p>Temps de préparation : <?= htmlspecialchars($order['order_datetime']) ?> minutes</p>
            </div>
        </div>
        <a href="#" class="action-button">Valider</a> 
    <?php endforeach; ?>
</div>


    
        <div class="order-container-history">
            <h1>Historique des commandes</h1>
            <?php foreach ($orders as $order): ?>
                <div class="order-card-history">
                    <div class="order-number-history">
                        #<?= htmlspecialchars($order['order_number']) ?>
                    </div>
                    <div class="order-content-history">
                        <h3>Menu <?= htmlspecialchars($order['order_item_name']) ?></h3>
                        <ul>
                        <ul>
                            <?php
                            $options = explode(',', htmlspecialchars($order['order_item_options']));
                            foreach ($options as $option)
                                echo "<li>" . trim($option) . "</li>";
                            ?>
                        </ul>
                        </ul>
                        <p>Temps de préparation : <?= htmlspecialchars($order['order_datetime']) ?> minutes</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>