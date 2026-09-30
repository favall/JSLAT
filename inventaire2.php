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

require_once 'inc/functions.php';
include('connection.php');

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
$maintenant = strftime("%Y-%m-%d %H:%M:%S"); 

//$ListeArticles = $_POST['ListeArticles'];
//$MontantTotal = $_POST['MontantTotal'];
//$MoyenPaiement = $_POST['MoyenPaiement'];
//$emailPerso = '';
$NbArticles = 1;
$IdArticle = explode(":", $_POST['Article1']);

print '<br>';

//Initialisation des variables
$x = 1;
$total = 0;
$table = "VenteSLAT";


print '<form class="form-horizontal" name="form1" id="form1" method="post" action="inventaire.php">';

// Début de la boucle

while ($x <= $NbArticles) {
		
	$Id = $IdArticle[$x-1];


	// On précise change le statut de l'article Réceptionné -> Non vendu
	$sqlquery="UPDATE `$table` SET Statut = 'Non vendu', ModifiedTime = '$maintenant'  where Id = '$Id'";
	
   if ($bdd->query($sqlquery) === FALSE) {
	   // Enregistrement de la requête dans un fichier log
	   $logFile = 'log.txt';
	   $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la modification de l'article ".$Id." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
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
	   Lien, 'Non vendu' AS Statut, Origine,
	   '$Id' AS IdArticle,
	   CreatedTime, '$maintenant' AS ModifiedTime
	FROM `$table`
	WHERE id = '$Id'
	";
   if ($bdd->query($sqlquery) === FALSE) {
	   // Enregistrement de la requête dans un fichier log
	   $logFile = 'log.txt';
	   $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la modification de l'article ".$Id." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	   file_put_contents($logFile, $logMessage, FILE_APPEND);
   }
	
	print '<h2>Article '.$Id.' correctement inventorié</h2>';
	
	// fin de la boucle
   $x++;
}

print '
  <div class="form-group">
      <div class="col-sm-offset-2 col-sm-9">
        <button type="submit" class="btn btn-primary">Revenir à l inventaire</button>
      </div>
  </div>
';
?>

</form>

</div>
</div>
<meta http-equiv='Refresh' content='10;URL=inventaire.php'>
</body>
</html>
