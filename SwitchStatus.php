
<?php

include('connection.php');
require_once 'inc/functions.php';

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
$maintenant = strftime("%Y-%m-%d %H:%M:%S"); 

$Id = mysqli_real_escape_string($bdd, $_POST['Id']) ;
$StatutFutur = mysqli_real_escape_string($bdd, $_POST['StatutFutur']) ;

$table = "VenteSLAT";
$sqlquery="UPDATE `$table` SET Statut = '$StatutFutur', ModifiedTime = '$maintenant' WHERE `Id` = '$Id' LIMIT 1;";
$bdd->query($sqlquery);

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
   Lien, '$StatutFutur' AS Statut, Origine,
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



mysqli_close ($bdd);

$lien = "QRCode.php?Id=".$Id;
retouraccueil($lien);

?>