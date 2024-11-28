<?php
    # Importations
    require_once('../database/database.php');
    require_once('../controllers/EmployeeController.php');
    use Controllers\EmployeeController;

    # 
    $db = getDB();
    $employees = new EmployeeController($db);

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="ressource/assets/header.css">
        <style>
            table{
                border: 2px solid green !important;
                border-radius: 25px !important;
                width: 100%;
                text-align: center;
                border-spacing: 0;
                font-size: larger;
            }
            thead{
                background-color: #546C98;
            }
            tbody{
                color: #222222;
                background-color: #F5F5F5;
            }
            tr:nth-child(even){
                background-color: #ADC7F3;
                opacity: 40%;
            }
            th, td{
                height: 50px;
            }
        </style>
        <title>Gestion des équipiers</title>
    </head>
    <body>
        <?php include_once('./header.php'); ?>
        <main>
            <h1>Gestion des équipiers</h1>
            <table>
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 25px;">Prénom NOM</th>
                        <th>Poste actuel</th>
                        <th>Statut</th>
                        <th style="border-top-right-radius: 25px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $employees_list = $employees->showEmployee();
                        $employees_count = count($employees_list);
                        for($i = 0; $i < $employees_count; $i++){
                            if(!$employees_list[$i]['is_active']){continue;}; //Skips inactive employees.
                            echo "<tr>";
                            echo
                                "<td".($i === $employees_count - 1 ? ' style="border-bottom-left-radius: 25px;"' : "").">".
                                $employees_list[$i]['firstname']." ".$employees_list[$i]['lastname'].
                                "</td>"
                            ;
                            echo "<td>"." "."</td>";
                            echo
                                "<td>".
                                ($employees_list[$i]['is_active'] ? "Actif" : "Inactif").
                                "</td>"
                            ;
                            echo
                                "<td".($i === $employees_count - 1 ? ' style="border-bottom-right-radius: 25px;"' : "").">".
                                '<a href="./employee.php?id='.$employees_list[$i]['id'].'" type="button" name="manage-employee-button">Gérer</a>'.
                                "</td>"
                            ;
                            echo "</tr>";
                        };
                    ?>
                </tbody>
            </table>
            <a href="./employee.php" type="button" id="add-employee-button">+</a>
        </main>
    </body>
</html>