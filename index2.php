<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vente d'occasion du Slat</title>
  <meta charset="utf-8">
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
					addOption(tailleArticle, '25 (EU) / 16 (MP)', '25 (EU) / 16 (MP)');
					addOption(tailleArticle, '26 (EU) / 16,5 (MP)', '26 (EU) / 16,5 (MP)');		
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
					 for (let i = 60; i <= 200; i++) {
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

<h2>Saisie du matériel que vous souhaitez vendre</h2>  
<p><b>(Tous les champs sont obligatoires)</b></p>


<?php

require_once 'inc/functions.php';

$Prenom = $_POST['Prenom'] ;
$Nom = $_POST['Nom'] ;
$Matricule = $_POST['Matricule'] ;
$emailPerso = $_POST['emailPerso'] ;
$phoneNumber = $_POST['phoneNumber'] ;
$IBAN = $_POST['IBAN'] ;
$BIC = $_POST['BIC'] ;

//On détecte s'il y a déjà des articles du même matricule dans la base
include('connection.php');
$table = "VenteSLAT";
$sqlquery="SELECT * FROM `$table` WHERE Matricule='$Matricule'";
$result = $bdd->query($sqlquery);
$NbArticles=mysqli_num_rows($result);
mysqli_close ($bdd);
if ($NbArticles > 0) {retouraccueil("erreur.php");} 


print '


<form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post" action="index3.php" onsubmit="return validateForm()">
  <h3>Informations perso</h3>
  <div class="form-group">
    <label class="control-label col-sm-2" for="Prenom">Prénom</label>
    <div class="col-sm-4">
    <input name="Prenom" type="text" class="form-control" id="Prenom" value="'.$Prenom.'" pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" required>
    </div>

    <label class="control-label col-sm-2" for="Nom">Nom</label>
    <div class="col-sm-4">
    <input name="Nom" type="text" class="form-control" id="Nom" value="'.$Nom.'" pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="Matricule">Matricule:</label>
    <div class="col-sm-10">
      <input name="Matricule" type="text" class="form-control" id="Matricule" value="'.$Matricule.'" readonly>
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
      <input name="IBAN" type="text" class="form-control" id="IBAN" value = "'.$IBAN.'" required>
    </div>
  </div>

  <input id="NbArticles" name="NbArticles" type="hidden" value="'.$_POST['NbArticles'].'">

  <br>  

';

// Début de la boucle

print '<h3><b><center><font color="red">Seuls les articles listés dans le menu déroulant sont acceptés.<br>
Pour les vêtements enfants : 14 ans max</font></center></b></h3>';

$x = 1;
while ($x <= $_POST['NbArticles']) {

print '
<h3>Article '.$x.'</h3>

  <div class="form-group">
    <label class="control-label col-sm-2" for="TypeArticle'.$x.'">Type d\'article :</label>
    <div class="col-sm-10">
       <select class="form-control" name="TypeArticle'.$x.'" id="TypeArticle'.$x.'" required onchange="updateTailleOptions('.$x.')">
          <option value=""></option>
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
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="MarqueArticle'.$x.'">Marque l\'article :</label>
    <div class="col-sm-10">
      <input name="MarqueArticle'.$x.'" type="text" class="form-control" id="MarqueArticle'.$x.'" pattern="[^\'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères problématiques." maxlength="20" required>
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
      <input class="form-control" type="number" name="PrixArticle'.$x.'" min="0" pattern="[0-9]" step="1" title="(pas de chiffres après la virgule)" placeholder="Le prix pourra éventuellement être ajusté au moment du dépôt des articles" required>
    </div>
  </div>


';

// fin de la boucle  
$x++;
}

?>

  <br>  

  <div class="form-group">
      <div class="col-sm-offset-1 col-sm-10">
        <button type="submit" class="btn btn-primary">Finaliser la saisie</button>
      </div>
  </div>

</form>


</div>


</body>
</html>
