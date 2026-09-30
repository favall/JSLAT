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
    <h3>Apportez ici les éventuelles corrections nécessaires</h3>
    <?php
    include('connection.php');
    require_once 'inc/functions.php';
    $table = "VenteSLAT";

    // ****************************************************
    $Id = $_GET['Id'];
    $Matricule = f_decrypt('!Slatparapente31!', $Id);
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
    $IBAN = f_decrypt('!Slatparapente31!', $row[4]);
    $BIC = f_decrypt('!Slatparapente31!', $row[5]);
    $Origine = $row[12];
    //}

    print '
    <form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post" action="modfich_inc.php" onsubmit="return validateForm()">
        <h3>Informations perso</h3>
        <div class="form-group">
            <label class="control-label col-sm-2" for="Prenom">Prénom</label>
            <div class="col-sm-4">
                <input name="Prenom" type="text" class="form-control" id="Prenom" value="' . $Prenom . '" required pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques.">
            </div>

            <label class="control-label col-sm-2" for="Nom">Nom</label>
            <div class="col-sm-4">
                <input name="Nom" type="text" class="form-control" id="Nom" value="'.$Nom . '" required pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques.">
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
                <input name="emailPerso" type="email" class="form-control" id="emailPerso" value="'.$emailPerso.'" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="phoneNumber">Numéro de portable:</label>
            <div class="col-sm-10">
                <input name="phoneNumber" type="tel" class="form-control" id="phoneNumber" value="'.$phoneNumber.'" required>
            </div>
        </div>

        <br>
        <h3>RIB (Pour les articles vendus)</h3>
        <div class="form-group">
            <label class="control-label col-sm-2" for="IBAN">IBAN:</label>
            <div class="col-sm-10">
                <input name="IBAN" type="text" class="form-control" id="IBAN" value="'.$IBAN.'" placeholder="Copier/coller possible, y compris incluant les espaces" required>
            </div>
        </div>

        <input id="NbArticles" name="NbArticles" type="hidden" value="'.$NbArticles.'">
        <input id="Origine" name="Origine" type="hidden" value="'.$Origine.'">

        <br>
        ';

        // Début de la boucle

        print '<h3><b><center><font color="red">Seuls les articles listés dans le menu déroulant sont acceptés.<br>
            Pour les vêtements enfants : 14 max</font></center></b></h3>';

        $sqlquery = "select Nom, Prenom, emailPerso, phoneNumber, IBAN, BIC, TypeArticle, MarqueArticle, TailleArticle, PrixArticle, Id, Statut from `$table` where Matricule = '$Matricule' ORDER BY Id";
        $result = $bdd->query($sqlquery);
        $x = 1;

        $chaine_crypte = f_crypt("!Slatparapente31!", $Matricule);
        $lien = "ajout.php?Id=".urlencode($chaine_crypte);
        print '<h3>Pour ajouter un article, cliquer <a href="'.$lien.'">ICI</a></h3>';

        while($row = mysqli_fetch_row($result)) {
            $TypeArticle = $row[6];
            $MarqueArticle = $row[7];
            $TailleArticle = $row[8];
            $PrixArticle = $row[9];
            $Id = $row[10];
            $StatutArticle = $row[11];

            $Id_crypte = f_crypt("!Slatparapente31!", $Id);
            $liensuppr = "suppr.php?Id=".urlencode($Id_crypte);

            //print '<h3>Article '.$x.' (Id '.$Id.')</h3>';
            $lien= 'QRCode.php?Id='.$Id;
            echo '<h3><a href=', "$lien", ' target="_blank">', "Article $Id",'</a></h3>';

                    $ReadonlyFlag = '';
                    print '
                        <div class="form-group">
                            <input id="IdArticle'.$x.'" name="IdArticle'.$x.'" type="hidden" value="'.$Id.'">
                            <label class="control-label col-sm-2" for="TypeArticle'.$x.'">Type d article :</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="TypeArticle'.$x.'" id="TypeArticle'.$x.'" required>
                                    <option value="'.$TypeArticle.'">'.$TypeArticle.'</option>
                                    <optgroup label="Ski alpin">
                                        <option value="Skis Alpin">Skis Alpin</option>
                                        <option value="Chaussures Alpin">Chaussures Alpin</option>
                                    </optgroup>
                                    <optgroup label="Snowboard">
                                        <option value="Snowboard (avec fixation)">Snowboard (avec fixation)</option>
                                        <option value="Snowboard (planche nue)">Snowboard (planche nue)</option>
                                        <option value="Chaussures Snowboard">Chaussures Snowboard</option>
                                    </optgroup>
                                    <optgroup label="Ski de rando">
                                        <option value="Skis rando">Skis rando</option>
                                        <option value="Chaussures Rando">Chaussures Rando</option>
                                    </optgroup>
                                    <optgroup label="Ski de fond">
                                        <option value="Skis de fond">Skis de fond</option>
                                        <option value="Chaussures fond">Chaussures fond</option>
                                    </optgroup>
                                    <optgroup label="Ski télémark">
                                        <option value="Skis télémark">Skis télémark</option>
                                        <option value="Chaussures télémark">Chaussures télémark</option>
                                    </optgroup>
                                    <optgroup label="Vêtements enfant">
                                        <option value="Combinaison Ski enfant">Combinaison Ski enfant</option>
                                        <option value="Ensemble Pantalon + Veste">Ensemble Pantalon + Veste</option>
                                        <option value="Veste Ski Enfant">Veste Ski Enfant</option>
                                        <option value="Pantalon Ski Enfant">Pantalon Ski Enfant</option>
                                    </optgroup>
                                </select>
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
					  	<input id="IdArticle'.$x.'" name="IdArticle'.$x.'" type="hidden" value="'.$Id.'">
						<label class="control-label col-sm-2" for="StatutArticle'.$x.'">Statut d article :</label>
					   <div class="col-sm-10">
					       <select class="form-control" name="StatutArticle'.$x.'" id="StatutArticle'.$x.'" required>
					          <option value="'.$StatutArticle.'">'.$StatutArticle.'</option>
					          <option value="Non réceptionné">Non réceptionné</option>
					          <option value="Réceptionné">Réceptionné</option>
					          <option value="Vendu">Vendu</option>
					          <option value="Non vendu">Non vendu</option>
					        </select>
					   </div>
					   </div>';
                if ($StatutArticle=="Non réceptionné") {print'<p><b>(Pour supprimer définitivement cet article, cliquer <a href="'.$liensuppr.'">ICI</a>)</p></b>';}
                print '<br>';
            // fin de la boucle
            $x++;
        }

        print '
        <br>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
                <button type="submit" class="btn btn-primary">Mettre à jour les données</button>
            </div>
        </div>
    </form>
</div>
    ';
    ?>

</body>
</html>