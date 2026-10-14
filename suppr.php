<?php

include('connection.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";

// ****************************************************

$Id = $_GET['Id'] ;
$Id = f_decrypt(APP_KEY, $Id);
$IdArticle = $Id;

// ****************************************************


// On recupère le matricule qui correspond à l'Id
$table = "VenteSLAT";

$sqlquery="SELECT Matricule FROM `$table` WHERE Id='$Id'";
$result = $bdd->query($sqlquery);
$Matricule=mysqli_fetch_row($result);
$Matricule = $Matricule[0];


// On ajoute l'article en question dans la base de données de log (avant la suppression sinon ça ne fonctionne pas)
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
	   Lien, 'Supprimé' AS Statut, Origine,
	   '$Id' AS IdArticle,
	   CreatedTime, '$maintenant' AS ModifiedTime
	FROM `$table`
	WHERE id = '$Id'";
   if ($bdd->query($sqlquery) === FALSE) {
	   // Enregistrement de la requête dans un fichier log
	   $logFile = 'log.txt';
	   $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la suppression de l'article ".$Id." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	   file_put_contents($logFile, $logMessage, FILE_APPEND);
   }


	// On supprime la ligne tel que demandé
	$sqlquery="DELETE FROM `$table` WHERE Id='$Id'";
   if ($bdd->query($sqlquery) === FALSE) {
	   // Enregistrement de la requête dans un fichier log
	   $logFile = 'log.txt';
	   $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la suppression de l'article ".$Id." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	   file_put_contents($logFile, $logMessage, FILE_APPEND);
   }

mysqli_close ($bdd);

// On retourne à la page modfich
$chaine_crypte = f_crypt(APP_KEY, $Matricule) ;
$lien = "modfich.php?Id=".urlencode($chaine_crypte);
retouraccueil($lien);

?>