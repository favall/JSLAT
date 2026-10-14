<!DOCTYPE html>
<html lang="en">

<head>
  <title>Vente d'occasion du Slat</title>
  <meta charset="utf-8">
  <meta name="robots" content="noindex, nofollow">
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
      background-size: 100%;
    }

    h1 {
      text-align: center;
    }
  </style>

</head>

<body>
  <div class="container">

    <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

    <h2>Confirmation de la mise à jour de vos articles en vente</h2>


    <?php

    include('connection.php');
    require_once 'inc/functions.php';

    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
    $maintenant = strftime("%Y-%m-%d %H:%M:%S");

    $Prenom = mysqli_real_escape_string($bdd, $_POST['Prenom']);
    $Nom = mysqli_real_escape_string($bdd, $_POST['Nom']);
    $Matricule = mysqli_real_escape_string($bdd, $_POST['Matricule']);
    $emailPerso = mysqli_real_escape_string($bdd, $_POST['emailPerso']);
    $phoneNumber = mysqli_real_escape_string($bdd, $_POST['phoneNumber']);
    $IBAN = mysqli_real_escape_string($bdd, $_POST['IBAN']);
    $BIC = '';//mysqli_real_escape_string($bdd, $_POST['BIC']);
    $BIC_crypte = '';//f_crypt(APP_KEY, $BIC);
    $IBAN_crypte = f_crypt(APP_KEY, $IBAN);

    // Début de la boucle
    $x = 1; $NbErreurs=0;

    while ($x <= mysqli_real_escape_string($bdd, $_POST['NbArticles'])) {

      $NomIdArticle = 'IdArticle' . $x;
      $IdArticle = mysqli_real_escape_string($bdd, $_POST[$NomIdArticle]);

      $NomTypeArticle = 'TypeArticle' . $x;
      $TypeArticle = mysqli_real_escape_string($bdd, $_POST[$NomTypeArticle]);

      $NomMarqueArticle = 'MarqueArticle' . $x;
      $MarqueArticle = mysqli_real_escape_string($bdd, $_POST[$NomMarqueArticle]);

      $NomTailleArticle = 'TailleArticle' . $x;
      $TailleArticle = mysqli_real_escape_string($bdd, $_POST[$NomTailleArticle]);

      $NomPrixArticle = 'PrixArticle' . $x;
      $PrixArticle = mysqli_real_escape_string($bdd, $_POST[$NomPrixArticle]);

      $NomStatutArticle = 'StatutArticle' . $x;
      $StatutArticle = mysqli_real_escape_string($bdd, $_POST[$NomStatutArticle]);

      $BIC_crypte = f_crypt(APP_KEY, $BIC);
      $IBAN_crypte = f_crypt(APP_KEY, $IBAN);

      // On ajoute l'article en question dans la base de données
      $table = "VenteSLAT";
      $sqlquery = "UPDATE `$table` SET
        Matricule = '$Matricule', 
        Nom = '$Nom', 
        Prenom = '$Prenom', 
        emailPerso = '$emailPerso',
        phoneNumber = '$phoneNumber',
        IBAN = '$IBAN_crypte', 
        BIC = '$BIC_crypte', 
        TypeArticle = '$TypeArticle', 
        MarqueArticle = '$MarqueArticle', 
        TailleArticle = '$TailleArticle', 
        PrixArticle = '$PrixArticle', 
        Statut = '$StatutArticle',
        ModifiedTime = '$maintenant' 
        WHERE `Id` = '$IdArticle' LIMIT 1;";

      // Exécuter la requête
      if ($bdd->query($sqlquery) === FALSE) {
        echo "Erreur lors de la modification de l'article " . $x . " : " . $bdd->error;

	     // Enregistrement de la requête dans un fichier log
	     $logFile = 'log.txt';
	     $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la modification de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	     file_put_contents($logFile, $logMessage, FILE_APPEND);

        $NbErreurs = $NbErreurs + 1;
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
			   Lien, Statut, Origine,
			   '$IdArticle' AS IdArticle,
			   CreatedTime, '$maintenant' AS ModifiedTime
			FROM `$table`
			WHERE id = '$IdArticle'
			";

      // Exécuter la requête
      if ($bdd->query($sqlquery) === FALSE) {
        echo "Erreur lors de la modification de l'article " . $x . " : " . $bdd->error;

	     // Enregistrement de la requête dans un fichier log
	     $logFile = 'log.txt';
	     $logMessage = date('Y-m-d H:i:s') . " - Erreur lors de la modification de l'article ".$x." : " . $bdd->error . " - Requête : " . $sqlquery . PHP_EOL;
	     file_put_contents($logFile, $logMessage, FILE_APPEND);

        $NbErreurs = $NbErreurs + 1;
      }


      // fin de la boucle  
      $x++;
    }

    $chaine_crypte = f_crypt(APP_KEY, $Matricule);
    $lien = "modfich.php?Id=" . urlencode($chaine_crypte);
    $lienfull = 'https://lesjournees.slat.info/' . $lien;

    // On calcule le pourcentage d'erreurs
    $RatioErreur = $NbErreurs / mysqli_real_escape_string($bdd, $_POST['NbArticles']);
    switch ($RatioErreur) {

      case 1: // Il n'y a que des erreurs
        echo "La modification de vos articles a totalement échoué (Cf. les messages d'erreur ci-dessus). Merci et réessayer et/ou de contacter le Slat si vous avez besoin d'aide.<br>";
        break;

      case 0: // Il n'y a eu aucune erreur
        echo 'Vos articles ont bien actualisés.';
        echo 'Le lien reçu par mail, rappelé ci-dessous, reste valable.<br>';
        echo '<a href="' . $lien . '"><b>' . $lienfull . '</b></a>';
        break;

      default:
        echo "Certains de vos articles ont bien été actualisés, d'autre(s)) non (Cf. les messages d'erreur ci-dessus). Merci et réessayer et/ou de contacter le Slat si vous avez besoin d'aide.";
    }

    mysqli_close($bdd);

    ?>

  </div>
  
  

</body>

</html>
