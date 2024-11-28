<?php 

use Controllers\DishController;

include_once '../database/database.php';
include_once '../controllers/DishController.php';
$dishController = new DishController(getDB());
$dishes = $dishController->showDish();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ressource/assets/header.css"> 
    <link rel="stylesheet" href="ressource/assets/menu-create.css"> 
    <title>Création des formules</title>
</head>
<body>
<?php include('header.php'); ?>
    <h1>Création des formules</h1>
    <form id="menu-form" action="../controllers/addMenu.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom" class="form-label">Intitulé</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image du menu</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <fieldset class="mb-3">
            <legend class="form-label">Sélectionnez les plats :</legend>
            <div>
                <?php if (!empty($dishes)) {
                    foreach ($dishes as $dish) {
                        echo "<div>
                                <input type='checkbox' id='dish_{$dish['id']}' name='dishes[]' value='{$dish['id']}'>
                                <label for='dish_{$dish['id']}'>{$dish['name']}</label>
                              </div>";
                    }
                } else {
                    echo "<p>Aucun plat disponible.</p>";
                } ?>
            </div>
        </fieldset>
        <button type="submit" class="btn-primary">Ajouter</button>
    </form>
</body>
</html>
