<?php
include('connection.php');
require_once 'inc/functions.php';

$table = "VenteSLAT";
$Id_crypte = $_GET['Id'];
$Id = $Id_crypte; //$Id = f_decrypt(APP_KEY, $Id_crypte);

// On récupère le lien de la fiche Vendeur, de l'article
//$sql = "SELECT Lien FROM $table WHERE id = $Id LIMIT 1";
$sql = "SELECT * FROM $table WHERE id = $Id LIMIT 1";
$result = $bdd->query($sql);
$row = $result->fetch_assoc();
$Matricule = $row["Matricule"];

$chaine_crypte = f_crypt(APP_KEY, $Matricule) ;
$lien = "modfich_admin.php?Id=".urlencode($chaine_crypte);

switch ($row["Statut"]) {
    case "Non réceptionné":
        $StatutActuel = "Non réceptionné";
        $StatutFutur = "Réceptionné";
        $Button = '<button type="submit" class="btn btn-danger"> ' . $StatutActuel . ' ->  ' . $StatutFutur . '</button>';
        $readonly = "readonly";
        break;
    case "Réceptionné":
        $StatutActuel = "Réceptionné";
        $StatutFutur = "Non réceptionné";
        $Button = '<button type="submit" class="btn btn-success"> ' . $StatutActuel . ' ->  ' . $StatutFutur . '</button>';
        $readonly = "readonly";
        break;
    case "Vendu":
        $StatutActuel = "Vendu";
        $StatutFutur = "Vendu";
        $Button = '<button type="submit" class="btn btn-info"> ' . $StatutActuel . ' ->  ' . $StatutFutur . '</button>';
        $readonly = "readonly";
        break;
    case "Non vendu":
        $StatutActuel = "Non vendu";
        $StatutFutur = "Non vendu";
        $Button = '<button type="submit" class="btn btn-info"> ' . $StatutActuel . ' ->  ' . $StatutFutur . '</button>';
        $readonly = "readonly";
        break;
}

$bdd->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vente d'occasion du Slat</title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <style>
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        body {
            background-image: url('background.png');
            background-attachment: fixed;
            background-size: 100%;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Article <?php echo $Id; ?></h2>
    <h3><i><?php echo $StatutActuel; ?></i> sur le site</h3>
    <br>
    Cliquer sur le bouton ci-dessous pour changer le statut de l'article
    <br>
    <form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post" action="SwitchStatus.php">
        <input type="hidden" id="Id" name="Id" value="<?php echo $Id; ?>">
        <input type="hidden" id="StatutFutur" name="StatutFutur" value="<?php echo $StatutFutur; ?>">
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-1">
                <center><?php echo $Button; ?></center>
            </div>
        </div>
    </form>

<!--
    <br>
    <h3>Prix actuel de l'article : <i><?php echo $row["PrixArticle"]; ?>€</i></h3>
    <form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post" action="SwitchPrix.php">
        <input type="hidden" id="Id" name="Id" value="<?php echo $Id; ?>">
        <div class="form-group">
            <label class="control-label col-sm-2" for="PrixArticle'.$x.'">Nouveau prix:</label>
            <div class="col-sm-1">
                <input class="form-control" type="number" name="PrixArticle" pattern="[0-9]" step="1" title="(pas de chiffres après la virgule)" value="<?php echo $row["PrixArticle"]; ?>" <?php echo $readonly; ?> required>
            </div>
            <center><font color="red"><b>/!\ Tout changement de prix /!\<br>/!\ doit être répercuté sur la fiche papier /!\</b></font></center>
            <div class="col-sm-1">
                <center><button type="submit" class="btn btn-primary">Actualiser</button></center>
            </div>
        </div>
    </form>


    <br>

    Cliquer sur le bouton ci-dessous pour accéder directement à la fiche vendeur pour la modifier
    <form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post"
          action="<?php echo htmlspecialchars($lien); ?>">
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-1">
                <center><button type="submit" class="btn btn-link">Lien vers la fiche <b><u>Admin</u></b> du vendeur</button></center>
            </div>
        </div>
    </form>
-->    
    
</div>

<script type="text/javascript">
    // Fonction pour fermer l'onglet après 10 secondes
    function fermerOnglet() {
        window.close();
    }

    // Appeler la fonction après 10 secondes
    setTimeout(fermerOnglet,10000); // 10 000 millisecondes équivalent à 60 secondes
</script>

</body>
</html>
