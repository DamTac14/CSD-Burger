<?php
    # Importations
    require_once('../database/database.php');
    require_once('../controllers/EmployeeController.php');
    use Controllers\EmployeeController;

    # Configurations
    setlocale(LC_TIME, 'fr_FR.UTF-8');

    # Getting informations in database
    $db = getDB();
    $employees = new EmployeeController($db);
    if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
        header('Location: ./employee-management.php');
    };
    $employee = $employees->getById($_GET);
    $hire_date = date('d/m/Y', strtotime($employee['hire_date']));
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="ressource/assets/header.css">
        <link rel="stylesheet" href="ressource/assets/employee.css">
        <title>Gestion des équipiers</title>
    </head>
    <body>
        <?php include_once('./header.php'); ?>
        <main>
            <h1>Gestion des équipiers</h1>
            <form action="employee.php" method="post">
                <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                <table id="employee-form" style="margin-left: 200px;">
                    <tr>
                        <td>
                            <label for="firstname">Prénom</label><br>
                            <input type="text" name="firstname" id="firstname" value="<?= $employee['firstname'] ?>">
                        </td>
                        <td>
                            <label for="lastname">Nom</label><br>
                            <input type="text" name="lastname" id="lastname" value="<?= $employee['lastname'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="hire-date">Date d'embauche</label><br>
                            <input type="text" name="hire-date" id="hire-date" value="<?= $hire_date ?>" readonly>
                        </td>
                        <td>
                            <label for="info">Info...</label><br>
                            <input type="text" name="info" id="info" value="">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label for="job">Poste par défaut</label><br>
                            <select name="job" id="job">
                                <option value="">-- Sélectionner un poste --</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
            <a id="back-button" href="./employee-management.php">Retour</a>
        </main>
    </body>
</html>