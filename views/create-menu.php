<?php include('header.php'); ?>

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
    <h1>Création des formules</h1>
    <form id="menu-form">
        <div class="mb-3">
            <label for="nom" class="form-label">Intitulé</label>
            <input type="text" class="form-control" id="nom" required>
        </div>
        <div class="mb-3">
    <label for="description" class="form-label">Plat à inclure</label>
    <select id="description" class="form-control" multiple required>
        <option value="Plat 1">Plat 1</option>
        <option value="Plat 2">Plat 2</option>
        <option value="Plat 3">Plat 3</option>
        <option value="Plat 4">Plat 4</option>
        <option value="Plat 5">Plat 5</option>
    </select>
</div>

        <button type="submit" class="btn-primary">Ajouter</button>
    </form>
</body>
</html>
