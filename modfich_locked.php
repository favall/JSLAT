<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vente d'occasion du Slat</title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
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
  <script src="https://cdn.jsdelivr.net/npm/iban@0.0.11/iban.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/libphonenumber-js/bundle/libphonenumber-js.min.js"></script>
  <script>
    function validateIBAN() {
        const ibanInput = document.getElementById('IBAN');
        const ibanValue = ibanInput.value.replace(/\s+/g, ''); // Remove spaces
        
        if (!IBAN.isValid(ibanValue)) {
            alert('IBAN invalide ! Veuillez entrer un IBAN conforme.');
            return false;
        }
        
        return true;
    }

    function validatePhoneNumber() {
        const phoneNumberInput = document.getElementById('phoneNumber');
        const phoneNumberValue = phoneNumberInput.value;
        
        const parsedPhoneNumber = new libphonenumber.parsePhoneNumberFromString(phoneNumberValue, 'FR'); // Change 'FR' to the desired country code
        
        if (!parsedPhoneNumber || !parsedPhoneNumber.isValid()) {
            alert('Numéro de téléphone invalide ! Veuillez entrer un numéro valide.');
            return false;
        }
        
        return true;
    }

    function validateForm() {
        return validateIBAN() && validatePhoneNumber();
    }
  </script>
</head>
<body>
<div class="container">
    <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">
    <h2>Information saisies et matériel que vous avez mis en vente</h2>
    <br>
    <center><font color="red">
    <h3>Les fiches papier ayant été imprimées, il n'est plus possible de les modifier en ligne.</h3>
    <h3>Si vous avez des modifications / corrections à faire, merci de le signaler à l'accueil, le jour du dépôt.</h3>
    <h3>Vous pouvez tout de même continuer à consulter le statut de l'ensemble de vos articles, notamment le jour de la vente.</h3>
	 </font></center>    
    <br><br>
    <?php
    include('connection.php');
    include('variables.php');
    require_once 'inc/functions.php';
    $table = "VenteSLAT";
    
    // ****************************************************
    $Id = $_GET['Id'];
    $Matricule = f_decrypt(APP_KEY, $Id);
    // ****************************************************

    $table = "VenteSLAT";
    $sqlquery = "SELECT * FROM `$table` WHERE Matricule='$Matricule'";
    $result = $bdd->query($sqlquery);
    $NbArticles = mysqli_num_rows($result);

    $sqlquery = "select Nom, Prenom, emailPerso, phoneNumber, IBAN, BIC, TypeArticle, MarqueArticle, TailleArticle, PrixArticle, Id, Statut, Origine from `$table` where Matricule = '$Matricule'";
    $result = $bdd->query($sqlquery);

    //while($row = mysqli_fetch_row($result)) {
    $row = mysqli_fetch_row($result);
    $Nom = $row[0];
    $Prenom = $row[1];
    $emailPerso = $row[2];
    $phoneNumber = $row[3];
    $IBAN = f_decrypt(APP_KEY, $row[4]);
    $BIC = f_decrypt(APP_KEY, $row[5]);
    $Origine = $row[12];
    //}

	 //
	 //$sqlquery = "SELECT COUNT(concat(`Prenom`,' ',`Nom`)) FROM (SELECT `Prenom`, `Nom` FROM `AnimationAdherent` UNION SELECT `Prenom`, `Nom` FROM `AnimationExt`) AS temp_table WHERE concat(`Prenom`,' ',`Nom`) LIKE '".$Participant."'";
	 //$result = $bdd->query($sqlquery);
	 //$row = mysqli_fetch_row($result);
	 //$checknom = $row[0];



    print '
    <form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post" action="modfich_inc.php" onsubmit="return validateForm()">
        <h3>Informations perso</h3>
        <div class="form-group">
            <label class="control-label col-sm-2" for="Prenom">Prénom</label>
            <div class="col-sm-4">
                <input name="Prenom" type="text" class="form-control" id="Prenom" value="' . $Prenom . '" required pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" readonly >
            </div>

            <label class="control-label col-sm-2" for="Nom">Nom</label>
            <div class="col-sm-4">
                <input name="Nom" type="text" class="form-control" id="Nom" value="'.$Nom . '" required pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" readonly >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="Matricule">Matricule ou n° Slat:</label>
            <div class="col-sm-10">
                <input name="Matricule" type="text" class="form-control" id="Matricule" value="'.$Matricule.'" readonly required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="emailPerso">Email :</label>
            <div class="col-sm-10">
                <input name="emailPerso" type="email" class="form-control" id="emailPerso" value="'.$emailPerso.'" readonly required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="phoneNumber">Numéro de portable:</label>
            <div class="col-sm-10">
                <input name="phoneNumber" type="tel" class="form-control" id="phoneNumber" value="'.$phoneNumber.'" readonly required>
            </div>
        </div>

        <br>
        <h3>RIB (Pour les articles vendus)</h3>
        <div class="form-group">
            <label class="control-label col-sm-2" for="IBAN">IBAN:</label>
            <div class="col-sm-10">
                <input name="IBAN" type="text" class="form-control" id="IBAN" value="'.$IBAN.'" placeholder="Copier/coller possible, y compris incluant les espaces" readonly required>
            </div>
        </div>

        <input id="NbArticles" name="NbArticles" type="hidden" value="'.$NbArticles.'">
        <input id="Origine" name="Origine" type="hidden" value="'.$Origine.'">

        <br>
        ';

        // Début de la boucle

        //print '<h3><b><center><font color="red">Seuls les articles listés dans le menu déroulant sont acceptés.<br>Pour les vêtements enfants :  max</font></center></b></h3>';

        $sqlquery = "select Nom, Prenom, emailPerso, phoneNumber, IBAN, BIC, TypeArticle, MarqueArticle, TailleArticle, PrixArticle, Id, Statut from `$table` where Matricule = '$Matricule' ORDER BY Id";
        $result = $bdd->query($sqlquery);
        $x = 1;

        $chaine_crypte = f_crypt(APP_KEY, $Matricule);
        $lien = "ajout.php?Id=".urlencode($chaine_crypte);

        while($row = mysqli_fetch_row($result)) {
            $TypeArticle = $row[6];
            $MarqueArticle = $row[7];
            $TailleArticle = $row[8];
            $PrixArticle = $row[9];
            $Id = $row[10];
            $StatutArticle = $row[11];
            

            
				//*************************************************************************            
				//
            // Gestion des préventes (vendu => réceptionné tant que les ventes ne sont pas ouvertes)
            //
            if ($StatutArticle == "Vendu" && date('Y-m-d H:i:s') < $date_fin_prevente) {$StatutArticle = "Réceptionné";}
				//				
				//*************************************************************************            
            
            $Id_crypte = f_crypt(APP_KEY, $Id);
            $liensuppr = "suppr.php?Id=".urlencode($Id_crypte);

            print '<h3>Article '.$x.'</h3>';

                    $ReadonlyFlag = 'readonly';
                    print '
                        <div class="form-group">
                        	 <input id="IdArticle'.$x.'" name="IdArticle'.$x.'" type="hidden" value="'.$Id.'">
                            <label class="control-label col-sm-2" for="TypeArticle'.$x.'">Type de l\'article:</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="TypeArticle'.$x.'" value="'.$TypeArticle.'" readonly>
                            </div>
                        </div>';

            print '
                <div class="form-group">
                    <label class="control-label col-sm-2" for="MarqueArticle'.$x.'">Marque l\'article :</label>
                    <div class="col-sm-10">
                        <input name="MarqueArticle'.$x.'" type="text" class="form-control" id="MarqueArticle'.$x.'" value="'.$MarqueArticle.'" required pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" '.$ReadonlyFlag.'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="TailleArticle'.$x.'">Taille de l\'article:</label>
                    <div class="col-sm-10">
                        <input name="TailleArticle'.$x.'" type="text" class="form-control" id="TailleArticle'.$x.'" value="'.$TailleArticle.'" required pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" '.$ReadonlyFlag.'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="PrixArticle'.$x.'">Prix de l\'article:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" name="PrixArticle'.$x.'" pattern="[0-9]" step="1" title="(pas de chiffres après la virgule)" value="'.$PrixArticle.'" required '.$ReadonlyFlag.'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="StatutArticle'.$x.'">Statut de l\'article:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="StatutArticle'.$x.'" value="'.$StatutArticle.'" readonly>
                    </div>
                </div>';
            // fin de la boucle
            $x++;
        }

        print '
    </form>
</div>
    ';
    ?>
</body>
</html>
