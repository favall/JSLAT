<?php
// =========================================================================
// INCLUSIONS ET CONFIGURATION INITIALE
// =========================================================================

// On inclut le fichier de configuration sécurisé (qui contient les mots de passe)
require_once __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Connexion à la base de données via config.php
include('connection.php');
require_once 'inc/functions.php';

// Inclusion des variables globales pour récupérer $date_depot
include('variables.php'); 

// Paramétrage des dates et heures
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
$maintenant = strftime("%Y-%m-%d %H:%M:%S"); 

$table = "VenteSLAT";

        // Requête pour récupérer les utilisateurs ciblés
        // $sqlquery = "SELECT Matricule, emailPerso FROM `$table` WHERE `Origine` <> 'FrancoisDesAlpes' GROUP BY Matricule, emailPerso";
        $sqlquery = "SELECT Matricule, emailPerso FROM `$table` WHERE `Nom` LIKE '%devi%' GROUP BY Matricule, emailPerso";
        
        $result = $bdd->query($sqlquery);

        while ($row = mysqli_fetch_assoc($result)) {
            // Utilisation de la constante APP_KEY définie dans config.php pour le chiffrement
            $chaine_crypte = f_crypt(APP_KEY,$row['Matricule']);
            $emailPerso =$row['emailPerso'];
            
            // Contenu du mail
            $objet = 'Journées SLAT - Vente matériel occasion'; 
            
            // Mise à jour du message pour utiliser la variable $date_depot au lieu d'une date en dur$message = "Bonjour<br><br>
            Vos articles ont bien été saisis. Vous n'avez plus qu'à venir les déposer le ".$date_depot." au complexe sportif du CSE, salle de tennis couvert (22 chemin Garric Toulouse).<br>1 euro par article déposé vous sera demandé.<br><br>
            Merci de bien conserver cet email : il permet, via le lien ci-dessous, d'apporter des modifications aux informations que vous venez de saisir.<br>journees.slat.asso.fr/modfich.php?Id=".urlencode($chaine_crypte)."<br><br>Vous trouverez également en pièce jointe à ce mail les Conditions Générales du Dépôt Vente des journées SLAT. Nous vous invitons à les consulter.<br><br>L'équipe SLAT";
            
            // Fichier joint
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
            
            $mail->Body =$message; 
            // Création d'une version texte brut pour les clients mail qui ne lisent pas le HTML
            $mail->AltBody = strip_tags(str_replace('<br>', "\n", $message)); 
            
            // Modifié à 'true' car le message contient des balises HTML (<br>)
            $mail->IsHTML(true);                                  
            
            if (!$mail->send()) {
                  echo "Erreur pour $emailPerso : " . $mail->ErrorInfo . "<br>";
            } else{
                  echo "Message bien envoyé à $emailPerso<br>";
            }
            // ********************************************************
            
        }

// Fermeture de la connexion à la base de données
mysqli_close($bdd);

?>