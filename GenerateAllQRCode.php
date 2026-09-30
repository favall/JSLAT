<?php

// Augmenter le temps d'exécution maximum à 60 secondes
set_time_limit(3600);


// On supprime tous les QRCodes contenus dans le répertoire QRCodes
// Chemin vers le répertoire contenant les fichiers .png à supprimer
$repertoire = 'QRCodes';

	// Vérifier si le répertoire existe et est accessible
	if (!is_dir($repertoire)) {
	    die("Le répertoire \"$repertoire\" n'existe pas ou n'est pas accessible.");
	}
	
	// Ouvrir le répertoire
	$gestionnaireRepertoire = opendir($repertoire);
	
	if (!$gestionnaireRepertoire) {
	    die("Impossible d'ouvrir le répertoire \"$repertoire\".");
	}
	
	// Parcourir les fichiers du répertoire
	while (($fichier = readdir($gestionnaireRepertoire)) !== false) {
	    // Ignorer les fichiers "." et ".." qui représentent le répertoire lui-même
	    if ($fichier != "." && $fichier != "..") {
	        // Vérifier si le fichier est un fichier .png
	        if (pathinfo($fichier, PATHINFO_EXTENSION) == "png") {
	            // Construire le chemin complet du fichier
	            $cheminFichier = $repertoire . DIRECTORY_SEPARATOR . $fichier;
	
	            // Supprimer le fichier
	            if (unlink($cheminFichier)) {
	                //echo "Le fichier \"$fichier\" a été supprimé avec succès.<br>";
	            } else {
	                echo "Impossible de supprimer le fichier \"$fichier\".<br>";
	            }
	        }
	    }
	}
	
	// Fermer le gestionnaire du répertoire
	closedir($gestionnaireRepertoire);



// Script qui sert à (re)générer tous les QRCodes pour tous les articles contenus en base de donnée.
require_once('inc/phpqrcode/qrlib.php');
include('connection.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";


$sqlquery="SELECT Id FROM `$table` WHERE `Origine` <> 'FrancoisDesAlpes' ORDER BY UPPER(NOM) ASC";
$result = $bdd->query($sqlquery);


while($row = mysqli_fetch_row($result)) {

$lastInsertedId = $row[0];

// On génère le QRCode qui sera affiché sur la fiche
$Id_crypte = $lastInsertedId;//$Id_crypte = f_crypt("!Slatparapente31!", $lastInsertedId) ;
$LienQRCode = "http://journees.slat.asso.fr/QRCode.php?Id=".$Id_crypte;
$fichierSortie = 'QRCodes/'.$lastInsertedId.'.png';
QRcode::png($LienQRCode, $fichierSortie);

}

print 'Tout les QRCodes ont correctement été générés';

?>

</body>
</html>
