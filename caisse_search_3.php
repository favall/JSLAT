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

<form class="form-horizontal" name="form1" id="form1" method="post" action="caisse_search_4.php">


<h2>Vérification des articles</h2>


<?php

require_once 'inc/functions.php';
include('connection.php');

$table = "VenteSLAT";
$NbArticles = $_POST['NbArticles'];


$x = 1;
$total = 0;
$erreurs = 0;
$ListeArticles = "";

// Début de la boucle
while ($x <= $_POST['NbArticles']) {

	$NomArticle = 'Article'.$x;
	$Article = $_POST[$NomArticle];
	
	$pieces = explode(":", $Article);
	$Id = $pieces[0];
	$ListeArticles = $ListeArticles . $Id . ";";
	
	$sqlquery="SELECT * FROM `$table` WHERE Id='$Id'";
	$result = $bdd->query($sqlquery);	
	$row = mysqli_fetch_assoc($result) ;
	$PrixArticle = $row["PrixArticle"];
	$chaine = $row["Id"].': '.$row["TypeArticle"].' de marque '.$row["MarqueArticle"].' taille '.$row["TailleArticle"].' ('.$row["PrixArticle"].' euros)';
	
	if ($Article != $chaine) {
  		$erreurs = $erreurs + 1 ;
  		$Article = "Saisie non conforme";
	}	
	
	$total = $total + $PrixArticle ;
	
	print '
	
	  <div class="form-group">
	    <label class="control-label col-sm-2" for="Article'.$x.'">Article '.$x.': </label>
	    <div class="col-sm-10">
	      <input name="Article'.$x.'" type="text" class="form-control" id="Article'.$x.'" value="'.$Article.'" readonly>
	    </div>
	  </div>
	  <br>
	
	';
	
	// fin de la boucle  
$x++;
}

if ($erreurs == 0) {
print '

<h2>Montant total à encaisser : '.$total.'€</h2>

  <div class="form-group">
  <label class="control-label col-sm-2" for="MoyenPaiement">Moyen de paiement :</label>
  <div class="col-sm-10">
       <select class="form-control" name="MoyenPaiement" id="MoyenPaiement" required>
          <option value="Carte Bleue">Carte Bleue</option>
          <option value=""></option>
          <option value="Espèces">Espèces</option>
          <option value="Chèque">Chèque</option>
        </select>

  </div>
  </div>
  
  <div class="form-group">
      <div class="col-sm-offset-2 col-sm-9">
        <button type="submit" class="btn btn-primary">Article(s) <b><u>vérifié(s)</b></u> 🗹<br>Montant <b><u>encaissé</b></u> 🗹<br> ➡ Cliquer <b><u>ICI</b></u> pour finaliser la vente</button>
      </div>
  </div>
';
}
$ListeArticles = substr($ListeArticles, 0, -1);
print '<input id="ListeArticles" name="ListeArticles" type="hidden" value="'.$ListeArticles.'">';
print '<input id="MontantTotal" name="MontantTotal" type="hidden" value="'.$total.'">';

?>

</form>

<br><h3>Numéro de Mickaël : 06 21 76 76 07</h3>

</div>
</div>

</body>
</html>
