<?php

include_once '../database/database.php';
include_once '../controllers/MenuController.php';

$pdo = getDB();
$menuController = new Controllers\MenuController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['id']) || !is_numeric($input['id'])) {
            throw new Exception("ID de menu invalide ou manquant.");
        }

        $menuController->delete(['id' => $input['id']]);

        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

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
    <link rel="stylesheet" href="ressource/assets/menu-list.css">
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
                <button class="btn btn-danger" onclick="deleteMenu(<?php echo $menu['id']; ?>)">Supprimer</button>
                <button class="btn btn-warning" onclick="location.href='edit-menu.php?id=<?php echo $menu['id']; ?>'">Modifier</button>

            </li>
        <?php endforeach; ?>
    </ul>

    <script>
        function deleteMenu(menuId) {
            if (confirm("Voulez-vous vraiment supprimer ce menu ?")) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: menuId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Menu supprimé avec succès !');
                        location.reload(); 
                    } else {
                        alert('Erreur : ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la suppression.');
                });
            }
        }
    </script>
</body>

</html>
