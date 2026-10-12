<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vente d'occasion du Slat</title>
  <meta charset="utf-8"><meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
<style>
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
body {
  background-image: url('background.png');
  background-attachment: fixed;
  background-size: 100% ;
}
h1 {
  text-align: center;
}
</style>  
  
</head>

<body>
<div class="container">

  <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

<h2>Confirmation de l'ajout de votre article</h2>	


<?php

require_once('inc/phpqrcode/qrlib.php');
include('connection.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
$maintenant = strftime("%Y-%m-%d %H:%M:%S"); 

$Prenom = mysqli_real_escape_string($bdd, $_POST['Prenom']) ;
$Nom = mysqli_real_escape_string($bdd, $_POST['Nom']) ;
$Matricule = mysqli_real_escape_string($bdd, $_POST['Matricule']) ;
$emailPerso = mysqli_real_escape_string($bdd, $_POST['emailPerso']) ;
$phoneNumber = mysqli_real_escape_string($bdd, $_POST['phoneNumber']) ;
$IBAN = mysqli_real_escape_string($bdd, $_POST['IBAN']) ;
$BIC = '';//mysqli_real_escape_string($bdd, $_POST['BIC']) ;
$BIC_crypte = f_crypt("!Slatparapente31!", $BIC);
$IBAN_crypte = f_crypt("!Slatparapente31!", $IBAN);
$Origine = mysqli_real_escape_string($bdd, $_POST['Origine']) ;

$chaine_crypte = f_crypt("!Slatparapente31!", $Matricule) ;

$lien = "modfich.php?Id=" . urlencode($chaine_crypte);
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ? 'https'
    : 'http';
$lienfull = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $lien;

$x = mysqli_real_escape_string($bdd, $_POST['NbArticles'])+1;

$NomTypeArticle = 'TypeArticle'.$x;
$TypeArticle = mysqli_real_escape_string($bdd, $_POST[$NomTypeArticle]);

$NomMarqueArticle = 'MarqueArticle'.$x;
$MarqueArticle = mysqli_real_escape_string($bdd, $_POST[$NomMarqueArticle]);

$NomTailleArticle = 'TailleArticle'.$x;
$TailleArticle = mysqli_real_escape_string($bdd, $_POST[$NomTailleArticle]);

$NomPrixArticle = 'PrixArticle'.$x;
$PrixArticle = mysqli_real_escape_string($bdd, $_POST[$NomPrixArticle]);

// On ajoute l'article en question dans la base de données

$table = "VenteSLAT";
$sqlquery="INSERT INTO `$table` 
(Matricule, Nom, Prenom, emailPerso, phoneNumber,
IBAN, BIC, 
TypeArticle, MarqueArticle, TailleArticle, PrixArticle, 
Lien, Statut, Origine, CreatedTime, ModifiedTime)
VALUES
(
'$Matricule','$Nom','$Prenom','$emailPerso','$phoneNumber',
'$IBAN_crypte','$BIC_crypte', 
'$TypeArticle', '$MarqueArticle', '$TailleArticle', '$PrixArticle',
'$lienfull', 'Non réceptionné', '$Origine', '$maintenant', '$maintenant')";

// Début de la transaction
$bdd->query("START TRANSACTION");

try {
    // Exécuter la requête d'insertion
    if ($bdd->query($sqlquery) === FALSE) {
        throw new Exception("Erreur lors de l'insertion : " . $bdd->error);
        
        // Enregistrement de la requête dans un fichier log
	     $logFile = 'log.txt';
	     $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	     file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    // Récupérer l'ID inséré
    $lastInsertedId = $bdd->insert_id;

    // Générer le lien du QR code
    $Id_crypte = $lastInsertedId;
    //$Id_crypte = f_crypt("!Slatparapente31!", $lastInsertedId);
    $LienQRCode = "http://journees.slat.asso.fr/QRCode.php?Id=".$Id_crypte;
    $fichierSortie = 'QRCodes/'.$lastInsertedId.'.png';
    QRcode::png($LienQRCode, $fichierSortie);

    // Générer le lien de modification
    $chaine_crypte = f_crypt("!Slatparapente31!", $Matricule);
    $lien = "modfich.php?Id=".urlencode($chaine_crypte);
    $lienfull = 'journees.slat.asso.fr/'.$lien;

    // Valider la transaction
    $bdd->query("COMMIT");

    echo 'Votre article a bien été ajouté.';
    echo 'Le lien reçu précédemment par mail, rappelé ci-dessous, reste valable.<br>';
    echo '<a href="'.$lien.'"><b>'.$lienfull.'</b></a>';
} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction
    $bdd->query("ROLLBACK");
    echo "Une erreur est survenue lors de l'insertion : " . $e->getMessage();
}

		// On ajoute l'article en question dans la base de données de log
	    $table_log = "VenteSLAT.log";
	    $maintenant = date("Y-m-d H:i:s");  // PHP prend le timezone défini dans php.ini ou via date_default_timezone_set
	    $sqlquery = "
			INSERT INTO `$table_log`
			  (Matricule, Nom, Prenom, emailPerso, phoneNumber,
			   IBAN, BIC, 
			   TypeArticle, MarqueArticle, TailleArticle, PrixArticle, 
			   Lien, Statut, Origine, IdArticle, CreatedTime, ModifiedTime)
			SELECT 
			   Matricule, Nom, Prenom, emailPerso, phoneNumber,
			   IBAN, BIC, 
			   TypeArticle, MarqueArticle, TailleArticle, PrixArticle,
			   Lien, Statut, Origine,
			   '$lastInsertedId' AS IdArticle,
			   '$maintenant' AS CreatedTime, '$maintenant' AS ModifiedTime
			FROM `$table`
			WHERE id = '$lastInsertedId'
			";
if ($bdd->query($sqlquery) === FALSE) {
   // Enregistrement de la requête dans un fichier log
   $logFile = 'log.txt';
   $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la modification de l'article ".$lastInsertedId." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
   file_put_contents($logFile, $logMessage, FILE_APPEND);
}

mysqli_close ($bdd);



?>

</div>


</body>
</html>
