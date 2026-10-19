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

<body><script src="sortiedemo-bg.js"></script>
<div class="container">

<h1>Rappel de tous les liens utiles <br>(à destination des <b>Administrateurs</b> de la vente)</h1>	

<br>
<h2>A la remise en route de la vente</h2>	
<ol>
	<li>Mettre à jour le fichier variable.php avec les nouvelles dates</li>
	<li><a href="liste_ventes.php" target="_blank">Réinitialiser les 3 tables</a> (VenteSLAT, VenteSLAT.log et VenteSLAT_factu)</li>
</ol>

<h2>A tout moment</h2>	

Cette page : <a href="LiensAdmin.php" target="_blank">LiensAdmin.php</a><br>
Chemin complet pour l'ensemble des liens : https://lesjournees.slat.info/...<br>
<br>
Page pour les bénévoles de la vente : <a href="LiensUtiles.php" target="_blank">LiensUtiles.php</a><br>
<br>

Lien vers la liste des vendeurs : <a href="liste_vendeurs.php" target="_blank">liste_vendeurs.php</a><br>
Elle permet d'accéder aux fiches de chaque vendeur pour les corriger, en tant qu'admin, et de les imprimer, si nécessaire.<br>
<br>
Liste avec l'intégralité des articles : <a href="liste_articles_new.php" target="_blank">liste_articles_new.php</a><br>
Permet d'afficher tout un tas de chose, notamment des reports pour Laurent.<br>
<br>
Tableau de synthèse, orienté mobile : <a href="suivi_mobile.php" target="_blank">suivi_mobile.php</a><br>
<br>
Lien vers le fichier de log si problème écriture SQL (vite = tout est bon) :
<a href="log.txt" target="_blank">log.txt</a><br>


<h2>Avant le jour de la vente</h2>	
Lien vers le formulaire : <a href="index.php" target="_blank">index.php</a><br>
<br>

Lien pour regénérer tous les QRCodes : <a href="GenerateAllQRCode.php" target="_blank">GenerateAllQRCode.php</a><br>
<br>

Lien qui permet de générer l'ensemble des fiches, pour impression : <a href="ficheAll.php" target="_blank">ficheAll.php</a><br>
Génère les fiches, prêtes à être imprimées pour l'intégralité des articles, sauf ceux de François des Alpes.<br>
<br>

<h2>Le jour de la vente</h2>
Lien vers la caisse : <a href="caisse.php" target="_blank">caisse.php</a><br>
Lien vers l'inventaire : <a href="inventaire.php" target="_blank">inventaire.php</a><br>
QRCode pour accéder à ce lien : <a href="qr-code_inventaire.png" target="_blank">qr-code_inventaire.png</a><br>
<br>
Liste avec les articles de François des Alpes : <a href="FrancoisDesAlpes.php" target="_blank">FrancoisDesAlpes.php</a><br>
<br>

<h2>Après la vente</h2>	
Liste avec l'intégralité des vendeurs Slat : <a href="liste_ventes.php" target="_blank">liste_ventes.php</a><br>
Permet d'afficher tout un tas de chose, en particlier pour Katia (notamment le montant à verser aux vendeurs après la vente)<br>
<br>




</div>


</body>
</html>
