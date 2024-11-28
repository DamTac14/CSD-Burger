<?php

// Données fictives pour les performances
$insightData = [
    'totalSales' => 1250, 
    'totalOrders' => 320, 
    'totalRevenue' => 15600, 
    'averageBasket' => 48.75, 
    'topCategories' => [
        ['category' => 'Burgers', 'sales' => 500],
        ['category' => 'Salades', 'sales' => 300],
        ['category' => 'Boissons', 'sales' => 200],
        ['category' => 'Desserts', 'sales' => 250],
    ],
    'salesByPeriod' => [
        ['period' => 'Janvier', 'sales' => 1200],
        ['period' => 'Février', 'sales' => 1400],
        ['period' => 'Mars', 'sales' => 1800],
        ['period' => 'Avril', 'sales' => 2000],
        ['period' => 'Mai', 'sales' => 2300],
        ['period' => 'Juin', 'sales' => 2600],
    ],
    'performanceByDay' => [
        ['day' => 'Lundi', 'orders' => 50],
        ['day' => 'Mardi', 'orders' => 60],
        ['day' => 'Mercredi', 'orders' => 70],
        ['day' => 'Jeudi', 'orders' => 40],
        ['day' => 'Vendredi', 'orders' => 100],
        ['day' => 'Samedi', 'orders' => 80],
        ['day' => 'Dimanche', 'orders' => 50],
    ],
    'keyPerformanceIndicators' => [
        'conversionRate' => 3.5, 
        'returningCustomers' => 65, 
        'newCustomers' => 110,
    ]
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ressource/assets/header.css"> 
    <title>Insights des Performances</title>
</head>

<body>
<?php include('header.php'); ?>
    <h1>Insights des Performances</h1>

    <h2>Résumé Global</h2>
    <p>Total des ventes : <?= $insightData['totalSales']; ?></p>
    <p>Total des commandes : <?= $insightData['totalOrders']; ?></p>
    <p>Revenu total : <?= number_format($insightData['totalRevenue'], 2); ?> €</p>
    <p>Panier moyen : <?= number_format($insightData['averageBasket'], 2); ?> €</p>

    <h2>Meilleures Catégories</h2>
    <ul>
        <?php foreach ($insightData['topCategories'] as $category): ?>
            <li><?= $category['category']; ?> : <?= $category['sales']; ?> ventes</li>
        <?php endforeach; ?>
    </ul>

    <h2>Ventes par Période</h2>
    <ul>
        <?php foreach ($insightData['salesByPeriod'] as $period): ?>
            <li><?= $period['period']; ?> : <?= $period['sales']; ?> ventes</li>
        <?php endforeach; ?>
    </ul>

    <h2>Performances par Jour</h2>
    <ul>
        <?php foreach ($insightData['performanceByDay'] as $day): ?>
            <li><?= $day['day']; ?> : <?= $day['orders']; ?> commandes</li>
        <?php endforeach; ?>
    </ul>

    <h2>Indicateurs Clés</h2>
    <p>Taux de conversion : <?= $insightData['keyPerformanceIndicators']['conversionRate']; ?>%</p>
    <p>Clients récurrents : <?= $insightData['keyPerformanceIndicators']['returningCustomers']; ?></p>
    <p>Nouveaux clients : <?= $insightData['keyPerformanceIndicators']['newCustomers']; ?></p>
</body>

</html>

