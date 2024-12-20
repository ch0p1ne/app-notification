<?php

@session_start();

// ### Debugage
if(!isset($_SESSION["userid"]) || !($_SESSION["userid"] != ""))
    header('Location: ./src/html/login/login.php', true, 301);
var_dump($_SESSION['sess_userrole']);
var_dump($_SESSION['sess_name']);
if(isset($_SESSION['provider_name'])) 
    var_dump($_SESSION['provider_name']);
    var_dump($_SESSION['order_queue']);
// ### FIn du debugage
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/basic.css">
    <script src="./public/js/jquery-2.1.1.min.js" defer></script>
    <script src="./public/js/get-notification.js" defer></script>
    
    <script type="text/javascript">
        // Définir les variables globales avec les données PHP
        var order_queue = <?php echo json_encode($_SESSION['order_queue']); ?>;
        var provider_name = <?php echo json_encode($_SESSION['provider_name']); ?>;
        var user_position = <?php echo json_encode($_SESSION['sess_userrole']); ?>;
    </script>
    
    <title>Accueil</title>
</head>

<body>
    <div class="main-container">
        <div class="header">
            <div>
                <h1> Liste des commandes faite</h1>
                <p>Une breve description du projet</p>
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <td>Numéro de commande</td>
                        <td>Detail de commande</td>
                        <td>Date de parrution</td>
                        <td>ETAT</td>
                    </tr>
                </thead>
                <tbody class="command-container">
                    <tr>
                        
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <div></div>
                </tfoot>
            </table>

        </div>

    </div>
</body>

</html>