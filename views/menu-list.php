<?php

include_once '../database/database.php';
include_once '../controllers/MenuController.php';

$pdo = getDB();
$menuController = new Controllers\MenuController($pdo);

try {
    $menus = $menuController->getAll();
} catch (Exception $e) {
    die("Erreur lors de la récupération des menus : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ressource/assets/header.css">
    <title>Listes des formules</title>
</head>

<body>
    <?php include('header.php'); ?>
    <h1>Liste des formules</h1>

    <?php if (isset($_GET['success'])): ?>
        <p>Menu ajouté avec succès !</p>
    <?php endif; ?>

    <ul>
        <?php foreach ($menus as $menu): ?>
            <li>
                <h2><?php echo htmlspecialchars($menu['name']); ?></h2>
                <img src="<?php echo htmlspecialchars($menu['image']); ?>" alt="<?php echo htmlspecialchars($menu['name']); ?>" width="150">
                <p>Prix du menu : <?php echo htmlspecialchars($menu['menu_price']); ?> €</p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>
