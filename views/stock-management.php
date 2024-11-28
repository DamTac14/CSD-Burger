<?php
    # Importations
    require_once('../database/database.php');
    require_once('../controllers/IngredientController.php');
    require_once('../controllers/StockController.php');
    use Controllers\IngredientController;
    use Controllers\StockController;

    # 
    $db = getDB();
    $ingredients = new IngredientController($db);
    $ingredients_list = $ingredients->getAll();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="ressource/assets/header.css">
        <link rel="stylesheet" href="ressource/assets/employee.css">
        <title>Gestion des stocks</title>
        <script>
            function showCSVImport(){
                const import_csv_button = document.getElementById('import-button')
                const import_csv_form = document.getElementById('import-form')

                import_csv_button.setAttribute('hidden', true)
                import_csv_form.removeAttribute('hidden')
            }

            function cancelCSVImport(){
                const import_csv_button = document.getElementById('import-button')
                const import_csv_form = document.getElementById('import-form')

                import_csv_button.removeAttribute('hidden')
                import_csv_form.setAttribute('hidden', true)
            }
        </script>
    </head>
    <body>
        <?php include_once('./header.php'); ?>
        <main>
            <h1>Gestion des stocks</h1>
            <table id="ingredients-table" class="table">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 25px;">Désignation</th>
                        <th>Quantité</th>
                        <th style="border-top-right-radius: 25px;">Prix unitaire magasin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $ingredients_list = $ingredients->getAll();
                        $ingredients_count = count($ingredients_list);
                        for($i = 0; $i < $ingredients_count; $i++){
                            echo "<tr>";
                            echo
                                "<td".($i === $ingredients_count - 1 ? ' style="border-bottom-left-radius: 25px;"' : "").">".
                                $ingredients_list[$i]['name'].
                                "</td>"
                            ;
                            echo "<td>"." "."</td>";
                            echo
                                "<td".($i === $ingredients_count - 1 ? ' style="border-bottom-right-radius: 25px;"' : "").">".
                                $ingredients_list[$i]['price'].
                                "</td>"
                            ;
                            echo "</tr>";
                        };
                    ?>
                </tbody>
            </table>
            <button type="button" id="import-button" onclick="showCSVImport()">Import CSV</button>
            <form action="stock-management.php" method="POST" enctype="multipart/form-data" id="import-form" hidden>
                <label for="csvFile">Veuillez sélectionner le fichier CSV à importer. :</label>
                <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
                <button type="submit" name="import">Valider</button>
                <a href="#" onclick="cancelCSVImport()">Annuler</a>
            </form>
            <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])){
                    # Checking if the CSV file has been uploaded succesfully
                    if($_FILES['csvFile']['error'] === UPLOAD_ERR_OK){
                        $fileTmpPath = $_FILES['csvFile']['tmp_name'];
                        $fileName = $_FILES['csvFile']['name'];
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                        # Checking file extension
                        if(strtolower($fileExtension) === 'csv'){
                            # Reading the CSV file
                            if(($handle = fopen($fileTmpPath, 'r')) !== false){
                                # Reading each line of the file
                                while(($data = fgetcsv($handle, 5000, ';')) !== false){
                                    $csv_line = [];
                                    $i = 0;
                                    foreach($data as $cell){
                                        $csv_line[$i] = $cell;
                                        $i++;
                                    };
                                    if($csv_line[0] === "DESIG"){continue;} //Skips the first line of the CSV file.

                                    # Creating the Ingredient's Stock
                                    $stock = new StockController($db);
                                    $stock->create([
                                        'quantity' => $csv_line[1],
                                        'threshold' => $csv_line[3]
                                    ]);

                                    # Creating the Ingredient
                                    $ingredients->create([
                                        'name' => $csv_line[0],
                                        'allergens' => "{}",
                                        'price' => $csv_line[2]
                                    ]);
                                }
                                fclose($handle);
                                unset($_FILES['csvFile']);
                                header("Refresh:0");
                            }
                            else{
                                echo "Erreur : Impossible de lire le fichier.";
                            }
                        }
                        else{
                            echo "Erreur : Veuillez sélectionner un fichier CSV valide.";
                        }
                    }
                    else{
                        echo "Erreur : Problème lors du téléchargement du fichier.";
                    }
                }
                else{
                    echo "Erreur : Aucun fichier reçu.";
                };
            ?>
        </main>
    </body>
</html>