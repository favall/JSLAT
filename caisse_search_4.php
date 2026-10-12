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


<?php

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
//require '../PHPMailer/src/Exception.php';
//require '../PHPMailer/src/PHPMailer.php';
//require '../PHPMailer/src/SMTP.php';


require_once 'inc/functions.php';
include('connection.php');

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
$maintenant = strftime("%Y-%m-%d %H:%M:%S"); 

$ListeArticles = $_POST['ListeArticles'];
$MontantTotal = $_POST['MontantTotal'];
$MoyenPaiement = $_POST['MoyenPaiement'];
$emailPerso = '';
$NbArticles = substr_count($ListeArticles, ';')+1;
$IdArticle = explode(";", $ListeArticles);

print '<br>';

//On écrit les infos relatives à la facture dans la table dédiée
$table = "VenteSLAT_factu";
$sqlquery="INSERT INTO `$table` 
(emailPerso, NbArticles, ListeArticles, MontantTotal, MoyenPaiement, CreatedTime)
VALUES ('$emailPerso', '$NbArticles', '$ListeArticles', '$MontantTotal', '$MoyenPaiement', '$maintenant')";
$result = $bdd->query($sqlquery);

//On récupère l'ID
$sqlquery = "select Id, CreatedTime from `$table` where ListeArticles = '$ListeArticles'";
$result = $bdd->query($sqlquery);
$row = mysqli_fetch_row($result);
$Id = $row[0];

//On écrit dans la table le lien vers la facture
$Id_crypte = f_crypt("!Slatparapente31!", $Id);
$lien = "facture.php?Id=".urlencode($Id_crypte);
$lienfull = 'https://lesjournees.slat.info/'.$lien;
$sqlquery="UPDATE `$table` SET LienFacture = '$lienfull' where ListeArticles = '$ListeArticles'";
$result = $bdd->query($sqlquery);

//Initialisation des variables
$x = 1;
$total = 0;
$table = "VenteSLAT";

print '<h2><b><i><u>Facture :</u></i></b></h2>';
print '<h2>Vente n°'.sprintf('%04d', $Id).' du '.$maintenant.'</h2>
<form class="form-horizontal" name="form1" id="form1" method="post" action="caisse.php">';

// Début de la boucle

while ($x <= $NbArticles) {
	
	$Id = $IdArticle[$x-1];

	$sqlquery = "select TypeArticle, MarqueArticle, TailleArticle, PrixArticle, Statut, Id from `$table` where Id = '$Id'";	
	$result = $bdd->query($sqlquery);
	$row = mysqli_fetch_assoc($result) ;
	$TypeArticle = $row["TypeArticle"];
	$MarqueArticle = $row["MarqueArticle"];
	$TailleArticle = $row["TailleArticle"];
	$PrixArticle = $row["PrixArticle"];
	$Statut = $row["Statut"];
	
	
	$ChaineArticle = $IdArticle[$x-1] .': '.$TypeArticle .' '. $MarqueArticle .' '. $TailleArticle .' ('. $PrixArticle.' euros)';
	$total = $total + $PrixArticle ;
	
	print '
	<div class="form-group">
   	<label class="control-label col-sm-2" for="Article'.$x.'">Article '.$x.': </label>
   		<div class="col-sm-10">
   		<input name="Article'.$x.'" type="text" class="form-control" id="Article'.$x.'" value="'.$ChaineArticle.'"disabled>
    	</div>
   </div>
';	

	// On précise change le statut de l'article Réceptionné -> Vendu
	$sqlquery="UPDATE `$table` SET Statut = 'Vendu', ModifiedTime = '$maintenant'  where Id = '$Id'";
   if ($bdd->query($sqlquery) === FALSE) {
	   // Enregistrement de la requête dans un fichier log
	   $logFile = 'log.txt';
	   $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	   file_put_contents($logFile, $logMessage, FILE_APPEND);
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
	   Lien, 'Vendu' AS Statut, Origine,
	   '$Id' AS IdArticle,
	   CreatedTime, '$maintenant' AS ModifiedTime
	FROM `$table`
	WHERE id = '$Id'
	";
   if ($bdd->query($sqlquery) === FALSE) {
	   // Enregistrement de la requête dans un fichier log
	   $logFile = 'log.txt';
	   $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la modification de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	   file_put_contents($logFile, $logMessage, FILE_APPEND);
   }
	
	
	// fin de la boucle
   $x++;
}

print '

<h2>Montant total encaissé : '.$total.'€</h2>
<br>
<h3>Moyen de paiement : '.$_POST['MoyenPaiement'].'</h3>


';


if($emailPerso <> '') {
	
echo '<h3>Facture envoyée à l adresse email suivante : '.$_POST['emailPerso'].'</h3>
<br><br>
';	
}

print '
  <div class="form-group">
      <div class="col-sm-offset-2 col-sm-9">
        <button type="submit" class="btn btn-primary">Revenir à l accueil de la vente</button>
      </div>
  </div>
';
?>

</form>

<br><h3>Numéro de Mickaël : 06 21 76 76 07</h3>

</div>
</div>
<meta http-equiv='Refresh' content='15;URL=caisse.php'>
</body>
</html>
