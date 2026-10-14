<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vente d'occasion du Slat</title>
  <meta charset="utf-8">
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

require_once 'inc/functions.php';
include('connection.php');

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');

$Id_crypte = $_GET['Id'] ;
$Id = f_decrypt(APP_KEY, $Id_crypte);

print '<br>';

//On récupère les infos relatives à la facture dans la table dédiée
$table = "VenteSLAT_factu";
$sqlquery = "select * from `$table` where Id = '$Id'";
$result = $bdd->query($sqlquery);
$row = mysqli_fetch_row($result);

$Id = $row[0];
$emailPerso = $row[1];
$NbArticles = $row[2];
$ListeArticles = $row[3];
$MontantTotal = $row[4];
$MoyenPaiement = $row[5];
$LienFacture = $row[6];
$CreatedTime = $row[7];

$NbArticles = substr_count($ListeArticles, ';')+1;
$IdArticle = explode(";", $ListeArticles);


//Initialisation des variables
$x = 1;
$total = 0;
$table = "VenteSLAT";

print '<h2>Vente n°'.sprintf('%04d', $Id).' du '.$CreatedTime.'</h2>
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
		
	// fin de la boucle
   $x++;
}

print '

<h2>Montant total encaissé : '.$MontantTotal.'€</h2>
<br>
<h3>Moyen de paiement : '.$MoyenPaiement.'</h3>
<br><br>


';

?>

</form>

</div>
</div>

</body>
</html>
