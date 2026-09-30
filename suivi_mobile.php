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

$sqlquery = 'SELECT Statut, Origine, COUNT(*) AS Articles, SUM(PrixArticle) AS Valorisation 
             FROM VenteSLAT 
             GROUP BY Statut, Origine 
             ORDER BY Statut DESC';
$result = $bdd->query($sqlquery);

// TABLEAU
echo '<table class="table table-hover table-sm table-responsive">';

echo '<thead>';
while ($field_info = mysqli_fetch_field($result)) {
    echo '<th scope="col">' . $field_info->name . '</th>';
}
echo '</thead>';

echo '<tbody>';

// variables pour sous-totaux
$current_statut = null;
$subtotal_articles = 0;
$subtotal_valorisation = 0;

// variables total général
$total_articles = 0;
$total_valorisation = 0;

// boucle d’affichage
while ($row = mysqli_fetch_assoc($result)) {

    // si changement de statut → afficher sous-total
    if ($current_statut !== null && $row['Statut'] !== $current_statut) {

        echo "<tr>";
        echo "<td><strong>Sous-total " . $current_statut . "</strong></td>";
        echo "<td></td>";
        echo "<td><strong>" . $subtotal_articles . "</strong></td>";
        echo "<td><strong>" . number_format($subtotal_valorisation, 2, ',', ' ') . "</strong></td>";
        echo "</tr>";

        $subtotal_articles = 0;
        $subtotal_valorisation = 0;
    }

    $current_statut = $row['Statut'];

    // ligne normale
    echo "<tr>";
    echo "<td>" . $row['Statut'] . "</td>";
    echo "<td>" . $row['Origine'] . "</td>";
    echo "<td>" . $row['Articles'] . "</td>";
    echo "<td>" . number_format($row['Valorisation'], 2, ',', ' ') . "</td>";
    echo "</tr>";

    // accumuler
    $subtotal_articles += $row['Articles'];
    $subtotal_valorisation += $row['Valorisation'];

    $total_articles += $row['Articles'];
    $total_valorisation += $row['Valorisation'];
}

// dernier sous-total
if ($current_statut !== null) {
    echo "<tr>";
    echo "<td><strong>Sous-total " . $current_statut . "</strong></td>";
    echo "<td></td>";
    echo "<td><strong>" . $subtotal_articles . "</strong></td>";
    echo "<td><strong>" . number_format($subtotal_valorisation, 2, ',', ' ') . "</strong></td>";
    echo "</tr>";
}

// total général
echo "<tr>";
echo "<td><strong>Total général</strong></td>";
echo "<td></td>";
echo "<td><strong>" . $total_articles . "</strong></td>";
echo "<td><strong>" . number_format($total_valorisation, 2, ',', ' ') . "</strong></td>";
echo "</tr>";

echo '</tbody>';
echo "</table>";
?>

</body>
</html>
