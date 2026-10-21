<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vente d'occasion du Slat</title>
  <meta charset="utf-8"><meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
    
    function updateTailleOptions(articleNumber) {
        const typeArticle = document.getElementById('TypeArticle' + articleNumber).value;
        const tailleArticle = document.getElementById('TailleArticle' + articleNumber);

        // Supprimer toutes les options existantes
        tailleArticle.innerHTML = '';

        // Ajouter les options en fonction du type d'article
        switch (typeArticle) {
            case 'Chaussures Alpin':
            case 'Chaussures Snowboard':
            case 'Chaussures Rando':
               addOption(tailleArticle, '', '');
					addOption(tailleArticle, '27 (EU) / 17 (MP)', '27 (EU) / 17 (MP)');
					addOption(tailleArticle, '28 (EU) / 17,5 (MP)', '28 (EU) / 17,5 (MP)');
					addOption(tailleArticle, '29 (EU) / 18 (MP)', '29 (EU) / 18 (MP)');
					addOption(tailleArticle, '30 (EU) / 19 (MP)', '30 (EU) / 19 (MP)');
					addOption(tailleArticle, '31 (EU) / 19,5 (MP)', '31 (EU) / 19,5 (MP)');
					addOption(tailleArticle, '32 (EU) / 20 (MP)', '32 (EU) / 20 (MP)');
					addOption(tailleArticle, '33 (EU) / 21 (MP)', '33 (EU) / 21 (MP)');
					addOption(tailleArticle, '34 (EU) / 21,5 (MP)', '34 (EU) / 21,5 (MP)');
					addOption(tailleArticle, '35 (EU) / 22 (MP)', '35 (EU) / 22 (MP)');
					addOption(tailleArticle, '36 (EU) / 23 (MP)', '36 (EU) / 23 (MP)');
					addOption(tailleArticle, '37 (EU) / 23,5 (MP)', '37 (EU) / 23,5 (MP)');
					addOption(tailleArticle, '38 (EU) / 24 (MP)', '38 (EU) / 24 (MP)');
					addOption(tailleArticle, '39 (EU) / 25 (MP)', '39 (EU) / 25 (MP)');
					addOption(tailleArticle, '40 (EU) / 25,5 (MP)', '40 (EU) / 25,5 (MP)');
					addOption(tailleArticle, '41 (EU) / 26 (MP)', '41 (EU) / 26 (MP)');
					addOption(tailleArticle, '42 (EU) / 26,5 (MP)', '42 (EU) / 26,5 (MP)');
					addOption(tailleArticle, '42.5 (EU) / 27 (MP)', '42.5 (EU) / 27 (MP)');
					addOption(tailleArticle, '43 (EU) / 27,5 (MP)', '43 (EU) / 27,5 (MP)');
					addOption(tailleArticle, '44 (EU) / 28 (MP)', '44 (EU) / 28 (MP)');
					addOption(tailleArticle, '44.5 (EU) / 28,5 (MP)', '44.5 (EU) / 28,5 (MP)');
					addOption(tailleArticle, '45 (EU) / 29 (MP)', '45 (EU) / 29 (MP)');
					addOption(tailleArticle, '46 (EU) / 29,5 (MP)', '46 (EU) / 29,5 (MP)');
					addOption(tailleArticle, '47 (EU) / 30 (MP)', '47 (EU) / 30 (MP)');
					addOption(tailleArticle, '47.5 (EU) / 30,5 (MP)', '47.5 (EU) / 30,5 (MP)');
					addOption(tailleArticle, '48 (EU) / 31 (MP)', '48 (EU) / 31 (MP)');
                break;
            case 'Combinaison Ski enfant':
			case 'Ensemble Pantalon + Veste':
            case 'Veste Ski Enfant':
            case 'Pantalon Ski Enfant':
                addOption(tailleArticle, '', '');
                addOption(tailleArticle, '1 an', '1 an');
                addOption(tailleArticle, '2 ans', '2 ans');
                addOption(tailleArticle, '3 ans', '3 ans');
                addOption(tailleArticle, '4 ans', '4 ans');
                addOption(tailleArticle, '5 ans', '5 ans');
                addOption(tailleArticle, '6 ans', '6 ans');
                addOption(tailleArticle, '7 ans', '7 ans');
                addOption(tailleArticle, '8 ans', '8 ans');
                addOption(tailleArticle, '9 ans', '9 ans');
                addOption(tailleArticle, '10 ans', '10 ans');
                addOption(tailleArticle, '11 ans', '11 ans');
                addOption(tailleArticle, '12 ans', '12 ans');
                addOption(tailleArticle, '13 ans', '13 ans');
                addOption(tailleArticle, '14 ans', '14 ans');
                break;
            default:
                addOption(tailleArticle, '', '');
					 for (let i = 100; i <= 200; i++) {
					    addOption(tailleArticle, i + ' cm', i + ' cm');
					 }
                break;
        }
    }

    function addOption(selectElement, text, value) {
        const option = document.createElement('option');
        option.text = text;
        option.value = value;
        selectElement.add(option);
    }
  </script>

  
</head>

<body>
<div class="container">
  <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

<h2>Ajout d'un article</h2>	

<?php

include('connection.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";

// ****************************************************

$Id = $_GET['Id'] ;
$Matricule = f_decrypt(APP_KEY, $Id);

// ****************************************************

$table = "VenteSLAT";
$sqlquery="SELECT * FROM `$table` WHERE Matricule='$Matricule'";
$result = $bdd->query($sqlquery);
$NbArticles=mysqli_num_rows($result);


$sqlquery = "select Nom, Prenom, emailPerso, phoneNumber, IBAN, BIC, 
TypeArticle, MarqueArticle, TailleArticle, PrixArticle, Id, Origine from `$table` where Matricule = '$Matricule'";
$result = $bdd->query($sqlquery);

//while($row = mysqli_fetch_row($result)) {
$row = mysqli_fetch_row($result);
$Nom = $row[0];
$Prenom = $row[1];
$emailPerso = $row[2];
$phoneNumber = $row[3];
$IBAN = f_decrypt(APP_KEY, $row[4]);
$BIC = f_decrypt(APP_KEY, $row[5]);
$Origine = $row[11];

//}

print '


<form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post" action="ajout_inc.php">
  <h3>Informations perso</h3>
  <div class="form-group">
    <label class="control-label col-sm-2" for="Prenom">Prénom</label>
    <div class="col-sm-4">
    <input name="Prenom" type="text" class="form-control" id="Prenom" value="'.$Prenom.'" readonly>
    <input name="Prenom" type="hidden" class="form-control" id="Prenom" value="'.$Prenom.'">
    </div>

    <label class="control-label col-sm-2" for="Nom">Nom</label>
    <div class="col-sm-4">
    <input name="Nom" type="text" class="form-control" id="Nom" value="'.$Nom.'" readonly>
    <input name="Nom" type="hidden" class="form-control" id="Nom" value="'.$Nom.'">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="Matricule">Matricule:</label>
    <div class="col-sm-10">
      <input name="Matricule" type="text" class="form-control" id="Matricule" value="'.$Matricule.'" readonly>
      <input name="Matricule" type="hidden" class="form-control" id="Matricule" value="'.$Matricule.'">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="emailPerso">Email :</label>
    <div class="col-sm-10">
      <input name="emailPerso" type="email" class="form-control" id="emailPerso" value="'.$emailPerso.'" readonly>
      <input name="emailPerso" type="hidden" class="form-control" id="emailPerso" value="'.$emailPerso.'">
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="phoneNumber">Numéro de portable:</label>
    <div class="col-sm-10">
        <input name="phoneNumber" type="tel" class="form-control" id="phoneNumber" value="'.$phoneNumber.'" readonly>
        <input name="phoneNumber" type="hidden" class="form-control" id="phoneNumber" value="'.$phoneNumber.'">
    </div>
  </div>

 <br>
  <h3>RIB (Pour les articles vendus)</h3>
  <div class="form-group">
    <label class="control-label col-sm-2" for="IBAN">IBAN:</label>
    <div class="col-sm-10">
      <input name="IBAN" type="text" class="form-control" id="IBAN" value = "'.$IBAN.'" readonly>
      <input name="IBAN" type="hidden" class="form-control" id="IBAN" value = "'.$IBAN.'">
    </div>
  </div>

  <input id="NbArticles" name="NbArticles" type="hidden" value="'.$NbArticles.'">
  <input id="Origine" name="Origine" type="hidden" value="'.$Origine.'">

  <br>  

';

$x = $NbArticles + 1;


print '
<h3>Article '.$x.'</h3>
<p><b>(Tous les champs sont obligatoires)</b></p>
  <div class="form-group">
  <input id="IdArticle'.$x.'" name="IdArticle'.$x.'" type="hidden" value="'.$Id.'">
    <label class="control-label col-sm-2" for="TypeArticle'.$x.'">Type d\'article :</label>
    <div class="col-sm-10">
       <select class="form-control" name="TypeArticle'.$x.'" id="TypeArticle'.$x.'" required onchange="updateTailleOptions('.$x.')">
          <option value=""></option>
			 <optgroup label="Ski alpin">         
	          <option value="Skis Alpin">Skis Alpin</option>
	          <option value="Chaussures Alpin">Chaussures Alpin</option>
			 <optgroup label="Snowboard">         
	          <option value="Snowboard (avec fixation)">Snowboard (avec fixation)</option>
	          <option value="Snowboard (planche nue)">Snowboard (planche nue)</option>
	          <option value="Chaussures Snowboard">Chaussures Snowboard</option>
			 <optgroup label="Ski de rando">         
	          <option value="Skis rando">Skis rando</option>
	          <option value="Chaussures Rando">Chaussures Rando</option>
			 <optgroup label="Ski de fond">         
	          <option value="Skis de fond">Skis de fond</option>
	          <option value="Chaussures fond">Chaussures fond</option>
			 <optgroup label="Ski télémark">         
	          <option value="Skis télémark">Skis télémark</option>
	          <option value="Chaussures télémark">Chaussures télémark</option>
			 <optgroup label="Vêtements enfant">         
	          <option value="Combinaison Ski enfant">Combinaison Ski enfant</option>
	          <option value="Ensemble Pantalon + Veste">Ensemble Pantalon + Veste</option>
	          <option value="Veste Ski Enfant">Veste Ski Enfant</option>
	          <option value="Pantalon Ski Enfant">Pantalon Ski Enfant</option>
        </select>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="MarqueArticle'.$x.'">Marque l\'article :</label>
    <div class="col-sm-10">
      <input name="MarqueArticle'.$x.'" type="text" class="form-control" id="MarqueArticle'.$x.'" value="" pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="TailleArticle'.$x.'">Taille de l\'article:</label>
    <div class="col-sm-10">
      <select name="TailleArticle'.$x.'" class="form-control" id="TailleArticle'.$x.'" required>
        <!-- Options seront ajoutées dynamiquement ici -->
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="PrixArticle'.$x.'">Prix de l\'article:</label>
    <div class="col-sm-10">
      <input class="form-control" type="number" name="PrixArticle'.$x.'" min="0" pattern="[0-9]" step="1" title="(pas de chiffres après la virgule)" value="" placeholder="Le prix pourra éventuellement être ajusté au moment du dépôt des articles" required>
    </div>
  </div>



';


?>

  <br>  

  <div class="form-group">
      <div class="col-sm-offset-1 col-sm-10">
        <button type="submit" class="btn btn-primary">Ajout de l'article</button>
      </div>
  </div>

</form>

</div>


</body>
</html>
