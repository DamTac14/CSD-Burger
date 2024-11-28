<?php
include_once '../database/database.php';
include_once '../controllers/MenuController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getDB();
        $menuController = new Controllers\MenuController($pdo);

        $name = $_POST['nom'] ?? '';
        $image = $_FILES['image'] ?? null;
        $dishIds = $_POST['dishes'] ?? [];

        if (empty($name)) {
            throw new Exception("Le nom du menu est obligatoire.");
        }

        if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Une image valide est requise.");
        }

        if (empty($dishIds)) {
            throw new Exception("Vous devez sélectionner au moins un plat.");
        }

        $menuId = $menuController->addMenu($name, $image, $dishIds);

        header("Location: menu-list.php?success=1&menu_id=$menuId");
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
