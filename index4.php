<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vente d'occasion du Slat</title>
  <meta charset="utf-8"><meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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

	 .form-group {
	   margin-bottom: 25px;
	 }
  </style>

</head>

<body>
<div class="container">

  <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

<h2>Confirmation de la saisie de vos articles</h2>	


<?php

require_once 'inc/functions.php';
include('variables.php');

$chaine_crypte = $_GET['Id'];

$lien = "modfich.php?Id=".urlencode($chaine_crypte);


    echo "Vos articles ont bien été saisis. Vous n'avez plus qu'à venir les déposer le ".$date_depot." au complexe sportif du CSE, salle de tennis couvert (22 chemin Garric Toulouse). <br>1 euro  par article déposé vous sera demandé.<br>";
    echo '<br>Vous allez aussi recevoir un mail à l\'adresse fournie, avec un lien pour, si nécessaire, modifier les informations saisies.<br>';
    echo '(Si vous ne le recevez pas, merci de regarder dans le dossier Spam de votre messagerie)<br>';
    echo '<br>';
    echo 'Sinon vous pouvez aussi cliquer<a href="'.$lien.'"><b> ICI.</b></a>';
	// ********************************************************************************************************
	// Décommenter la ligne ci-dessous une fois que les fiches ont été générées en pdf
	//
	if (new DateTime() > $date_impression) {
		 echo '<br><center><font color="red"><h3>Les fiches papier ont déjà été imprimées.<br>Au moment du dépôt, il faudra les demander pour qu\'elles soient imprimées.</h3></font></center>';
	}
	//
	// ********************************************************************************************************

?>

</div>

</body>
</html>
