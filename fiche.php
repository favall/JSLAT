<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8"><meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Vente d'occasion du SLAT</title>
  <link rel="stylesheet" href="./A4.css">
</head>
<style>

body {
font-family : Arial, sans-serif;
font-size : 12px;
}

h1 {
  text-align: center;
}

h2 {
  text-align: center;
}

h11 {
  position: absolute;
  left: 100px;
  top: 150px;
}

h12 {
  position: absolute;
  left: 416px;
  top: 150px;
}

h13 {
  position: absolute;
  left: 416px;
  top: 500px;
}

mark {
  background-color:orange;
  color: black;
}

table {
  width: 100%;
}


</style>  
  
</head>

<body style="--bleeding: 0.5cm;--margin: 0cm;">


<?php

include('connection.php');
include('variables.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";

// ****************************************************
if (isset($_GET['Id'])) {
    $Id = $_GET['Id'];
} elseif (isset($Id)) {
    // Si l'ID n'est pas dans le GET, utilisez la variable déjà définie dans la boucle
} else {
    // Gérez le cas où l'ID n'est ni dans le GET ni dans la variable définie
    echo "ID non défini.";
}
// ****************************************************

$sqlquery="SELECT * FROM `$table` WHERE Id='$Id'";
$result = $bdd->query($sqlquery);

$row = mysqli_fetch_row($result);

$Id = $row[0];
$Matricule = $row[1];
$Nom = $row[2];
$Prenom = $row[3];
$emailPerso = $row[4];
$phoneNumber = $row[5];

$IBAN = $row[6];
$BIC = $row[7];

$TypeArticle = $row[8];
$MarqueArticle = $row[9];
$TailleArticle = $row[10];
$PrixArticle = $row[11];


$Lien = $row[12];
$Statut = $row[13];
$Origine = $row[14];

$chaine_crypte = f_crypt(APP_KEY, $Matricule) ;
$lien = "modfich.php?Id=".urlencode($chaine_crypte);
$lienfull = 'https://lesjournees.slat.info/'.$lien;

if($Origine == 'Kalidea') {
$Ref = "Kalidea " . $Matricule;
$IBAN = $row[6];
$BIC = $row[7];

}else {
    $Ref = $Matricule ;
    $IBAN = f_decrypt(APP_KEY, $row[6]);
    $BIC = f_decrypt(APP_KEY, $row[7]);

};

		




print '
<div class="page">
		<h1>'.$Nom.' '.$Prenom.' ('.$Ref.')</h1>
		<h11>Id : '.$Id.'<br>Type : '.$TypeArticle.'<br>Marque : '.$MarqueArticle.'<br>Taille : '.$TailleArticle.'<br>Prix : '.$PrixArticle.'€</h11>
		<h12>Id : '.$Id.'<br>Type : '.$TypeArticle.'<br>Marque : '.$MarqueArticle.'<br>Taille : '.$TailleArticle.'<br>Prix : '.$PrixArticle.'€</h12>
		
		
		<br><br><br><br><br><br><br><br><br><br><br><br>
		<p>Mobile : '.$phoneNumber.'  -  Mail : '.$emailPerso.'</p>


		<h2>Désignation de l&rsquo;article '.$Id.'</h2>
		<img src="QRCodes/'.$Id.'.png" class="img-thumbnail" alt="Responsive image"  width="100" height="100" align="right">

		<p>Type : '.$TypeArticle.'  -  Taille : '.$TailleArticle.'</p>
		<p>Marque : '.$MarqueArticle.'  -  Prix : '.$PrixArticle.'€</p>

		<br>
		Signature déposant :<br><br>
		<br>
		<b>Ne pas oublier de percevoir les 1€ dus</b>';
		
		print '
		<hr><br>
		<img src="logo.jpg" class="img-thumbnail" alt="Responsive image"  width="100" height="100" align="right">
   	<h1><mark>Fiche Déposant</mark></h1> 
		<p>Nom : '.$Nom.' '.$Prenom.' ('.$Ref.')<br>
		Mobile : '.$phoneNumber.'  -  Mail : '.$emailPerso.'</p>

		<h2>Désignation de l&rsquo;article '.$Id.'</h2>

		<p>Type : '.$TypeArticle.'  -  Taille : '.$TailleArticle.'</p>
		<p>Marque : '.$MarqueArticle.'  -  Prix : '.$PrixArticle.'€</p>
		<br>		
		<b>Tout dépôt d&rsquo;article doit être accompagné d\'1€.</b><br><br>
		<u><b>Nouveauté :</u></b> suivez en live si votre article est vendu ou non via ce lien (le même que celui reçu au moment de la saisie): '.$lienfull.'<br>				
		Si votre article n’a pas été vendu, un mail vous sera envoyé pour vous en informer.<br><br>
		L’article vendu sera réglé uniquement par virement bancaire sur le compte '.$IBAN.' au plus tard 15 jours après les journées SLAT.<br>
		<u>N.B.:</u> le SLAT prélève 3% sur les ventes de matériel<br>
		<br>
		<b><u>Dates du retour des invendus :</u></b><br>'.$date_retour.' à l’Espace CSE.<br>
		</div>';		
		
//}



?>

</body>
</html>
