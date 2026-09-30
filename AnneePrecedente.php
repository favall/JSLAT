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
  <script src="inc/tablefilter/tablefilter.js"></script>

  
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
  
</head>

<body>
  <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

<h1>Liste des articles vendus <br>lors de la vente de l'année dernière</h1>	
<h3>Vous ne savez pas à quel prix mettre vos articles en vente ? Vous trouverez ci-dessous, <b>à titre indicatif</b>, la liste des articles qui ont été mis en vente l'an passé.</h3>
<h3>Le Slat ne pourra être tenu pour responsable si des articles venaient à être vendus (ou non) en dehors de toute logique par rapport à ce qui s'est fait lors de précédentes ventes.</h3>

<?php

include('connection.php');
require_once 'inc/functions.php';
$table = "VenteSLAT.2023";

//$sqlquery = "select Nom, Prenom AS 'Prénom', Matricule AS 'Matricule / Commande', emailPerso AS Email, phoneNumber as Portable, Origine, count(Id) AS Articles from `$table` GROUP BY Matricule ORDER BY Origine, Nom";
$sqlquery = "select `TypeArticle` as Type, `MarqueArticle` as Marque, `TailleArticle` as Taille, `PrixArticle` as Prix, `Statut`, Id from `$table`  WHERE `Statut` <> 'Non réceptionné' ORDER BY Id";
//$sqlquery = "select `Id`, `Nom`, `Prenom`, `emailPerso`, `phoneNumber`, `IBAN`, `TypeArticle`, `MarqueArticle`, `TailleArticle`, `PrixArticle`, `Statut`, `Origine` from `$table` WHERE `Origine` = 'Formulaire' ORDER BY Nom";
$result = $bdd->query($sqlquery);

echo '<table class="table" id="demo"><thead><tr>';
echo '<th>Type</th><th>Marque</th><th>Taille</th><th>Prix</th><th>Statut</th>';
echo '</thead>';
echo '<tbody>';
while($row = mysqli_fetch_row($result)) {
	
	
	$Statut = $row[4];
	if($Statut == 'Réceptionné') { $Statut = 'Non vendu'; 	}  
	
    echo "<tr>";
    echo '<td>',"{$row[0]}","</td>";//Type
	 echo '<td>',"{$row[1]}","</td>";//Marque
    echo '<td>',"{$row[2]}","</td>";//Taille
    echo '<td>',"{$row[3]}","€</td>";//Prix
    echo '<td>',"{$Statut}","</td>";//Statut
	 echo "</tr>";
}
echo '</tbody>';
echo "</table>";



mysqli_close ($bdd);



?>

</div>


</body>
</html>

<script src="inc/tablefilter/tablefilter.js"></script>
<script data-config>
    var tfConfig  = {
            base_path: 'inc/tablefilter/',
        auto_filter: {
            delay: 1 //milliseconds
        },
        col_0: 'select',
        col_4: 'select',
        filters_row_index: 1,
        state: false,
        alternate_rows: true,
        rows_counter: true,
		  btn_reset: {
		        text: 'Effacer les filtres'
		  },
        status_bar: false,
        msg_filter: 'Filtering...',

           extensions: [
                {
                    name: 'sort'
                }
            ]

        }
        ;
    var tf = new TableFilter('demo', tfConfig);
    tf.init();
</script>
