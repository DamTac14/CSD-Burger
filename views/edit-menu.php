<?php

use Controllers\MenuController;
use Controllers\DishController;

include_once '../database/database.php';
include_once '../controllers/MenuController.php';
include_once '../controllers/DishController.php';

$pdo = getDB();
$menuController = new MenuController($pdo);
$dishController = new DishController($pdo);

$message = null;

$menuId = $_GET['id'] ?? null;

if (!$menuId || !is_numeric($menuId)) {
    die("ID de menu invalide.");
}

try {
    $menu = $menuController->getById($menuId); 
    $dishes = $dishController->getAll(); 
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $menuController->update([
            'id' => $menuId,
            'name' => $_POST['name'] ?? null,
            'image' => $_FILES['image'] ?? null,
            'dishIds' => $_POST['dishIds'] ?? []
        ]);

        header("Location: menu-list.php?success=2");
        exit;
    } catch (Exception $e) {
        $message = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ressource/assets/header.css">
    <link rel="stylesheet" href="ressource/assets/menu-create.css">
    <title>Modification d'une formule</title>
</head>

<body>
    <?php include('header.php'); ?>
    <h1>Modifier la formule</h1>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form id="menu-form" action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Intitulé</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($menu['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image du menu</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <p>Image actuelle : <img src="<?php echo htmlspecialchars($menu['image']); ?>" alt="<?php echo htmlspecialchars($menu['name']); ?>" width="150"></p>
        </div>
        <fieldset class="mb-3">
            <legend class="form-label">Sélectionnez les plats :</legend>
            <div>
                <?php if (!empty($dishes)): ?>
                    <?php foreach ($dishes as $dish): ?>
                        <div>
                            <input 
                                type="checkbox" 
                                id="dish_<?php echo $dish['id']; ?>" 
                                name="dishIds[]" 
                                value="<?php echo $dish['id']; ?>" 
                                <?php echo in_array($dish['id'], $menu['dishIds']) ? 'checked' : ''; ?>>
                            <label for="dish_<?php echo $dish['id']; ?>"><?php echo htmlspecialchars($dish['name']); ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun plat disponible.</p>
                <?php endif; ?>
            </div>
        </fieldset>
        <button type="submit" class="btn-primary">Mettre à jour</button>
    </form>
</body>

</html>
