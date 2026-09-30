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
  <script src="inc/tablefilter/tablefilter.js"></script>

  <style>
    /* Styles CSS inchangés pour des raisons de lisibilité */
  </style>  
</head>

<body>
  <header>
    
<?php
include('connection.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";

echo "<center><h1>Liste des articles DANS LA BASE `$table`</h1></center>";

$sqlquery = "SELECT Statut, Origine, COUNT( * ) AS Articles, SUM( PrixArticle ) AS Valorisation FROM `$table` GROUP BY Statut, Origine ORDER BY Statut DESC";
$result = $bdd->query($sqlquery) ;

// Récupération/affichage des colonnes et de leurs noms
echo '<table class="table table-hover table-sm table-responsive">';
echo '<thead>';
while ($field_info = mysqli_fetch_field($result)) 
	{
        echo '<th scope="col">'.$field_info->name.'</th>';
   }
echo '</thead>';

// Print the data
echo '<tbody>';
while($row = mysqli_fetch_row($result)) {
    echo "<tr>";
    foreach($row as $_column) {
        echo '<td>',"{$_column}","</td>";
    }
    echo "</tr>";
}
echo '</tbody>';
echo "</table>";
echo '<b><p>Pour télécharger les tables SQL au format Excel (articles et ventes):  <a href="generate_excel.php" download>Cliquer ICI</a></p></b><br>';



$sqlquery = "SELECT `Id`, `TypeArticle` AS Type, `MarqueArticle` AS Marque, `TailleArticle` AS Taille, `PrixArticle` AS Prix, `Statut`, `Origine`,  ModifiedTime FROM `$table` ORDER BY ModifiedTime DESC";

echo '</header>';


$result = $bdd->query($sqlquery);

echo '<table class="table" id="demo"><thead><tr>';
while ($field_info = mysqli_fetch_field($result)) {
    echo '<th>', "{$field_info->name}", '</th>';
}
echo '<th>Réception</th>';
echo '<th>Fiche article</th>';
echo '<th>Log</th>';
echo '</tr></thead>';
echo '<tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    $Origine = $row['Origine'];
    echo '<tr>';
    echo '<td>', "{$row['Id']}", '</td>'; //Id
    echo '<td>', "{$row['Type']}", '</td>'; //Type
    echo '<td>', "{$row['Marque']}", '</td>'; //Marque
    echo '<td>', "{$row['Taille']}", '</td>'; //Taille
    echo '<td>', "{$row['Prix']}", '</td>'; //Prix
    echo '<td>', "{$row['Statut']}", '</td>'; //Statut
    echo '<td>', "{$row['Origine']}", '</td>'; //Origine
    echo '<td>', "{$row['ModifiedTime']}", '</td>'; //Origine
	 $Id_crypte = f_crypt("!Slatparapente31!", $row['Id']);    
    $lien= 'fiche.php?Id='.$row['Id'];
    $lien2= 'QRCode.php?Id='.$row['Id'];
    $lien3= 'log_article.php?Id='.$row['Id'];
    echo '<td><a href=', "$lien2", ' target="_blank">', "Lien réception",'</a>'; //Origine
    echo '<td><a href=', "$lien", ' target="_blank">', "Lien fiche",'</a>'; //Origine
    echo '<td><a href=', "$lien3", ' target="_blank">', "Lien log",'</a>'; //Origine
    //echo '<h3><a href=', "$lien", ' target="_blank">', "Lien fiche",'</a></h3>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

mysqli_close($bdd);
?>

<script src="inc/tablefilter/tablefilter.js"></script>
<script data-config>
    var tfConfig  = {
        base_path: 'inc/tablefilter/',
        auto_filter: {
            delay: 1 //milliseconds
        },
        
	    // columns data types
	    col_types: [
	        'formatted-number', // defaults to '.' for decimal and ',' for thousands
	        'string',
	        'string',
	        'string',
	        'formatted-number', // defaults to '.' for decimal and ',' for thousands
	        'string',
	        'string',
	        { type: 'date', locale: 'fr' },
	        'string',
	        'string',
	    ],        
        
        col_1: 'select',
        col_5: 'select',
        col_6: 'select',
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
    };
    var tf = new TableFilter('demo', tfConfig);
    tf.init();
</script>
</body>
</html>
