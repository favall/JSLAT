<?php
require_once 'inc/functions.php';
// ********************************************************************************************************
// Décommenter la ligne ci-dessous une fois que les réceptions de matériel sont fermées.
   retouraccueil('finsaisie.php');    
//
// ********************************************************************************************************
?>

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

<h2>Vente de matériel d'occasion du Slat</h2>	
<p><b>(Tous les champs sont obligatoires)</b></p>
<form data-toggle="validator" role="form" class="form-horizontal" name="form1" id="form1" method="post" action="index2.php" onsubmit="return validateForm()">
  <h3>Informations personnelles</h3>
  <div class="form-group">
    <label class="control-label col-sm-2" for="Prenom">Prénom</label>
    <div class="col-sm-4">
      <input name="Prenom" type="text" class="form-control" id="Prenom" value="" pattern="[^'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères spéciaux, type ', &, ...." required autocomplete="given-name">
    </div>

    <label class="control-label col-sm-2" for="Nom">Nom</label>
    <div class="col-sm-4">
      <input name="Nom" type="text" class="form-control" id="Nom" value="" pattern="[^'&quot;&amp;&lt;&gt;]+" title="Veuillez entrer un texte valide sans caractères spéciaux, type ', &, ...." required autocomplete="family-name">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="Matricule">Matricule ou n° Slat:</label>
    <div class="col-sm-10">
      <input name="Matricule" type="text" class="form-control" id="Matricule" value="" placeholder="Mettre ici votre matricule Airbus, ou votre numéro de carte Slat" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="emailPerso">Email :</label>
    <div class="col-sm-10">
      <input name="emailPerso" type="email" class="form-control" id="emailPerso" value="" required autocomplete="email">
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="phoneNumber">Numéro de portable:</label>
    <div class="col-sm-10">
        <input name="phoneNumber" type="tel" class="form-control" id="phoneNumber" value="" required autocomplete="tel">
    </div>
  </div>

  <h3>RIB (Pour le paiement des articles vendus)</h3>
  <div class="form-group">
    <label class="control-label col-sm-2" for="IBAN">IBAN:</label>
    <div class="col-sm-10">
      <input name="IBAN" type="text" class="form-control" id="IBAN" value="" placeholder="Copier/coller possible, y compris incluant les espaces" required autocomplete="off">
    </div>
  </div>

  <h3>Nombre d'articles que vous souhaitez mettre en vente</h3>
  <div class="form-group">
    <label class="control-label col-sm-2" for="NbArticles">Nombre d'article(s):</label>
    <div class="col-sm-10">
       <select class="form-control" name="NbArticles" id="NbArticles" required>
          <option value=""></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
        </select>
    </div>
  </div>
 
  <div class="form-group">
      <div class="col-sm-offset-1 col-sm-9">
        <button type="submit" class="btn btn-primary">Passer à l'étape suivante</button>
      </div>
  </div>
</form>
</div>
</body>
</html>
