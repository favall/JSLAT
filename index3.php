<!DOCTYPE html>
<html lang="fr">
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
// =========================================================================
// INCLUSIONS ET CONFIGURATION INITIALE
// =========================================================================

// Appel du fichier de configuration global (contenant les secrets et constantes)
require_once __DIR__ . '/config.php';

require_once('inc/phpqrcode/qrlib.php');
include('variables.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Connexion à la base de données via config.php
include('connection.php');
require_once 'inc/functions.php';

// Paramétrage des dates et heures
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
$maintenant = strftime("%Y-%m-%d %H:%M:%S"); 

// =========================================================================
// RÉCUPÉRATION ET SÉCURISATION DES DONNÉES DU FORMULAIRE
// =========================================================================

// Utilisation de mysqli_real_escape_string pour éviter les injections SQL basiques
$Prenom = mysqli_real_escape_string($bdd,$_POST['Prenom']) ;
$Nom = mysqli_real_escape_string($bdd, $_POST['Nom']) ;$Matricule = mysqli_real_escape_string($bdd,$_POST['Matricule']) ;

$emailPerso = mysqli_real_escape_string($bdd, $_POST['emailPerso']) ;$phoneNumber = mysqli_real_escape_string($bdd,$_POST['phoneNumber']) ;

// Récupération des données bancaires et chiffrement avec APP_KEY (au lieu de la clé en clair)
$IBAN = mysqli_real_escape_string($bdd,$_POST['IBAN']) ;
$IBAN_crypte = f_crypt(APP_KEY,$IBAN);

$BIC = mysqli_real_escape_string($bdd,$_POST['BIC']) ;
$BIC_crypte = f_crypt(APP_KEY,$BIC);

// Création du lien d'édition chiffré
$chaine_crypte = f_crypt(APP_KEY,$Matricule) ;
$lien = "modfich.php?Id=".urlencode($chaine_crypte);
$lienfull = 'journees.slat.asso.fr/'.$lien;


// =========================================================================
// DÉBUT DE LA TRANSACTION SQL (Garantit que tout ou rien s'insère)
// =========================================================================
$bdd->query("START TRANSACTION");

$x = 1; 
$NbErreurs = 0;

// Boucle pour traiter chaque article saisi dans le formulaire précédent
while ($x <= mysqli_real_escape_string($bdd,$_POST['NbArticles'])) {

    // Récupération sécurisée des données de l'article courant
    $NomTypeArticle = 'TypeArticle'.$x;
    $TypeArticle = mysqli_real_escape_string($bdd, $_POST[$NomTypeArticle]);

    $NomMarqueArticle = 'MarqueArticle'.$x;
    $MarqueArticle = mysqli_real_escape_string($bdd, $_POST[$NomMarqueArticle]);

    $NomTailleArticle = 'TailleArticle'.$x;
    $TailleArticle = mysqli_real_escape_string($bdd, $_POST[$NomTailleArticle]);

    $NomPrixArticle = 'PrixArticle'.$x;
    $PrixArticle = mysqli_real_escape_string($bdd, $_POST[$NomPrixArticle]);

    // 1. Insertion dans la table principale VenteSLAT
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

        // Log de l'erreur dans un fichier texte
        $logFile = 'log.txt';$logMessage = date('Y-m-d H:i:s') . " - Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
        file_put_contents($logFile,$logMessage, FILE_APPEND);
     
        $NbErreurs =$NbErreurs + 1;
    } else {
        $lastInsertedId =$bdd->insert_id;
        
        // Création du QR Code pour la fiche article
        $Id_crypte =$lastInsertedId;
        // Optionnel : si l'ID dans le QR Code doit être crypté, décommenter la ligne suivante et utiliser APP_KEY
        // $Id_crypte = f_crypt(APP_KEY,$lastInsertedId);
        
        $LienQRCode = "http://journees.slat.asso.fr/QRCode.php?Id=".$Id_crypte;
        $fichierSortie = 'QRCodes/'.$lastInsertedId.'.png';
        QRcode::png($LienQRCode,$fichierSortie);
    }

    // 2. Historisation dans la table VenteSLAT.log
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

    if ($bdd->query($sqlquery) === FALSE) {
        echo "Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . "<br>";

        $logFile = 'log.txt';$logMessage = date('Y-m-d H:i:s') . " - Erreur lors de l'insertion de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
        file_put_contents($logFile,$logMessage, FILE_APPEND);
     
        $NbErreurs =$NbErreurs + 1;
    }

    // Passage à l'article suivant
    $x++;
}

// =========================================================================
// GESTION DU RÉSULTAT ET ENVOI DE L'EMAIL
// =========================================================================

// S'il n'y a eu aucune erreur lors des requêtes INSERT
if ($NbErreurs === 0) {
    
    // On valide définitivement la transaction SQL
    $bdd->query("COMMIT");
    
    // Contenu de base du mail
    $objet = 'Journées SLAT - Vente matériel occasion'; 
    
    // ********************************************************************************************************
    // Décommenter la ligne ci-dessous une fois que les fiches ont été générées en pdf
    // Le texte a été légèrement adapté pour indiquer que le lien reste important
    // ********************************************************************************************************
    $message = "Bonjour<br><br>
    Vos articles ont bien été saisis. Vous n'avez plus qu'à venir les déposer le ".$date_depot." au complexe sportif du CSE, salle de tennis couvert (22 chemin Garric Toulouse).<br>1 euro par article déposé vous sera demandé.<br><br>
    Merci de bien conserver cet email : en particulier le lien ci-dessous.<br>journees.slat.asso.fr/modfich.php?Id=".urlencode($chaine_crypte)."<br><br>Vous trouverez également en pièce jointe à ce mail les Conditions Générales du Dépôt Vente des journées SLAT. Nous vous invitons à les consulter.<br><br>L'équipe SLAT";

    // Gestion de la pièce jointe (Conditions Générales)
    $scriptDirectory = __DIR__;
    $fileName = 'CGDV.pdf';$filePath = $scriptDirectory . '/' .$fileName;
    
    // ********************************************************
    // Configuration de PHPMailer avec les constantes sécurisées
    // ********************************************************
    $mail = new PHPMailer();
    $mail->IsSMTP();$mail->Host = SMTP_HOST;               // Récupéré depuis config.php
    $mail->Port = SMTP_PORT;               // Récupéré depuis config.php
    $mail->SMTPAuth = true;                
    $mail->CharSet = 'UTF-8';
    
    if($mail->SMTPAuth){
       $mail->SMTPSecure = 'tls';$mail->Username   = SMTP_USER;      // Récupéré depuis config.php
       $mail->Password   = SMTP_PASS;      // Récupéré depuis config.php
    }
    
    $mail->From       = SMTP_FROM;         // Récupéré depuis config.php
    $mail->FromName   = SMTP_FROM_NAME;    // Récupéré depuis config.php
    
    $mail->AddAddress($emailPerso);
    $mail->Subject    =$objet;
    $mail->addAttachment($filePath, $fileName);$mail->WordWrap   = 50; 
    $mail->Body       =$message;
    $mail->AltBody    = strip_tags(str_replace('<br>', "\n", $message)); // Fournir une version texte brut lisible$mail->IsHTML(true); // Modifié à 'true' car le message contient des balises HTML (<br>)
    
    if (!$mail->send()) {
          // Affiche l'erreur si l'envoi échoue (pratique pour le débug)
          echo "Erreur d'envoi de l'email : " . $mail->ErrorInfo;
    }
    // ********************************************************
    
    // On bascule vers la page 4, pour éviter que les articles ne soient ajoutés à chaque refresh de la page   
    $lien = "index4.php?Id=".urlencode($chaine_crypte); 
    retouraccueil($lien);
    
} else  {
    // Si au moins une erreur a eu lieu, on annule (ROLLBACK) toutes les insertions de la boucle
    $bdd->query("ROLLBACK");
    echo "L'enregistrement de vos articles a échoué (Cf. les messages d'erreur ci-dessus). Merci de réessayer et/ou de contacter le Slat si vous avez besoin d'aide pour la saisie.<br>";
}

// Fermeture de la connexion à la base de données
mysqli_close($bdd);

?>

</div>
</body>
</html>