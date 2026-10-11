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

require_once('inc/phpqrcode/qrlib.php');
include('variables.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'inc/PHPMailer/src/Exception.php';
require 'inc/PHPMailer/src/PHPMailer.php';
require 'inc/PHPMailer/src/SMTP.php';

include('connection.php');
require_once 'inc/functions.php';

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
$maintenant = strftime("%Y-%m-%d %H:%M:%S"); 


$Prenom = mysqli_real_escape_string($bdd, $_POST['Prenom']) ;
$Nom = mysqli_real_escape_string($bdd, $_POST['Nom']) ;
$Matricule = mysqli_real_escape_string($bdd, $_POST['Matricule']) ;

$emailPerso = mysqli_real_escape_string($bdd, $_POST['emailPerso']) ;
$phoneNumber = mysqli_real_escape_string($bdd, $_POST['phoneNumber']) ;
$IBAN = mysqli_real_escape_string($bdd, $_POST['IBAN']) ;
$IBAN_crypte = f_crypt("!Slatparapente31!", $IBAN);
$BIC = mysqli_real_escape_string($bdd, $_POST['BIC']) ;
$BIC_crypte = f_crypt("!Slatparapente31!", $BIC);
$chaine_crypte = f_crypt("!Slatparapente31!", $Matricule) ;
$lien = "modfich.php?Id=".urlencode($chaine_crypte);
$lienfull = 'journees.slat.asso.fr/'.$lien;

// Début de la transaction
$bdd->query("START TRANSACTION");

// Début de la boucle
$x = 1; $NbErreurs = 0;

while ($x <= mysqli_real_escape_string($bdd, $_POST['NbArticles'])) {

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
    '$lienfull','Non réceptionné', 'Formulaire', '$maintenant', '$maintenant')";

    if ($bdd->query($sqlquery) === FALSE) {
        echo "Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . "<br>";

	    // Enregistrement de la requête dans un fichier log
	    $logFile = 'log.txt';
	    $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	    file_put_contents($logFile, $logMessage, FILE_APPEND);
     
        $NbErreurs = $NbErreurs + 1;
    } else {
        $lastInsertedId = $bdd->insert_id;
        // On génère le QRCode qui sera affiché sur la fiche
        $Id_crypte = $lastInsertedId;
        //$Id_crypte = f_crypt("!Slatparapente31!", $lastInsertedId);
        $LienQRCode = "http://journees.slat.asso.fr/QRCode.php?Id=".$Id_crypte;
        $fichierSortie = 'QRCodes/'.$lastInsertedId.'.png';
        QRcode::png($LienQRCode, $fichierSortie);
    }

    // On ajoute l'article en question aussi dans la base de log
    $table = "VenteSLAT.log";
    $sqlquery="INSERT INTO `$table` 
    (Matricule, Nom, Prenom, emailPerso, phoneNumber,
    IBAN, BIC, 
    TypeArticle, MarqueArticle, TailleArticle, PrixArticle, 
    Lien, Statut, Origine, IdArticle, CreatedTime, ModifiedTime)
    VALUES
    (
    '$Matricule','$Nom','$Prenom','$emailPerso','$phoneNumber',
    '$IBAN_crypte','$BIC_crypte', 
    '$TypeArticle', '$MarqueArticle', '$TailleArticle', '$PrixArticle',
    '$lienfull','Non réceptionné', 'Formulaire', '$lastInsertedId', '$maintenant', '$maintenant')";

    // Exécuter la requête d'insertion
    if ($bdd->query($sqlquery) === FALSE) {
        echo "Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . "<br>";

	    // Enregistrement de la requête dans un fichier log
	    $logFile = 'log.txt';
	    $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	    file_put_contents($logFile, $logMessage, FILE_APPEND);
     
        $NbErreurs = $NbErreurs + 1;
    }

    // fin de la boucle  
    $x++;
}

// Vérifier si des erreurs ont eu lieu pendant les insertions
if ($NbErreurs === 0) {
    // Valider la transaction si aucune erreur
    $bdd->query("COMMIT");
    
    // Contenu du mail
    $objet = 'Journées SLAT - Vente matériel occasion'; // Objet du message
    $message = "Bonjour<br><br>
    Vos articles ont bien été saisis. Vous n'avez plus qu'à venir les déposer le ".$date_depot." au complexe sportif du CSE, salle de tennis couvert (22 chemin Garric Toulouse).<br>1 euro  par article déposé vous sera demandé.<br><br>
    Merci de bien conserver cet email : il permet, via le lien ci-dessous, d'apporter des modifications aux informations que vous venez de saisir.<br>journees.slat.asso.fr/modfich.php?Id=".urlencode($chaine_crypte)."<br><br>Vous trouverez également en pièce jointe à ce mail les Conditions Générales du Dépôt Vente des journées SLAT. Nous vous invitons à les consulter.<br><br>L'équipe SLAT";
 
	// Modif du message si fiches déjà imprimées
	if (new DateTime() > $date_impression) {
	    $message = "Bonjour<br><br>
	    Vos articles ont bien été saisis. Vous n'avez plus qu'à venir les déposer le ".$date_depot." au complexe sportif du CSE, salle de tennis couvert (22 chemin Garric Toulouse).<br>1 euro par article déposé vous sera demandé.<br><br>
	    Merci de bien conserver cet email : en particulier le lien ci-dessous.<br>journees.slat.asso.fr/modfich.php?Id=".urlencode($chaine_crypte)."<br><br>
	    Vous trouverez également en pièce jointe à ce mail les Conditions Générales du Dépôt Vente des journées SLAT. Nous vous invitons à les consulter.<br><br>
	    L'équipe SLAT";
	} 
    
	 $scriptDirectory = __DIR__;
    $fileName = 'CGDV.pdf';
    $filePath = $scriptDirectory . '/' . $fileName;
    
    // ********************************************************
    //Management de l'envoi du mail
    $mail = new PHPMailer();
		    $mail->IsSMTP();
		    $mail->Host = 'smtp.postmarkapp.com';               //Adresse IP ou DNS du serveur SMTP
		    $mail->Port = 587;                          //Port TCP du serveur SMTP
		    $mail->SMTPAuth = true;                        //Utiliser l'identification
		    $mail->CharSet = 'UTF-8';
		   
		    if($mail->SMTPAuth){
		       $mail->SMTPSecure = 'tls';               //Protocole de sécurisation des échanges avec le SMTP
		       $mail->Username   =  'd3b9ee5d-3a25-4bad-bd87-c2173b60f51a';
		       $mail->Password   =  'd3b9ee5d-3a25-4bad-bd87-c2173b60f51a';
		    }
		   
		    $mail->From       = 'slat.nepasrepondre@slat.asso.fr';                //L'email à afficher pour l'envoi
		    $mail->FromName   = 'SLAT ne pas Répondre';          //L'alias de l'email de l'emetteur		    
    
    $mail->AddAddress($emailPerso);
    //$mail->AddBCC('mickael.deviese@gmail.com');
    $mail->Subject    =  $objet;                      //Le sujet du mail
    $mail->addAttachment($filePath, $fileName);
    $mail->WordWrap   = 50; 			       //Nombre de caracteres pour le retour a la ligne automatique
    $mail->Body = "$message"; 	       //Texte brut
    $mail->AltBody = "$message"; 	       //Texte brut
    $mail->IsHTML(false);                                  //Préciser qu'il faut utiliser le texte brut
    
    if (!$mail->send()) {
          echo $mail->ErrorInfo;
    } else{
          //echo 'Message bien envoyé';
    }
    //Fin management de l'envoi du mail
    // ********************************************************
    
	 // On bascule vers la page 4, pour éviter que les articles ne soient ajoutés à chaque refresh de la page   
	 $lien = "index4.php?Id=".urlencode($chaine_crypte); retouraccueil($lien);
    
} else  {
    // Il n'y a que des erreurs
    $bdd->query("ROLLBACK");
    echo "L'enregistrement de vos articles a échoué (Cf. les messages d'erreur ci-dessus). Merci et réessayer et/ou de contacter le Slat si vous avez besoin d'aide pour la saisie.<br>";
}

mysqli_close ($bdd);

?>

</div>

</body>
</html>
