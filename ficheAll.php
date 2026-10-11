<?php
include('connection.php');
include('variables.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";

$date_impression_sql = $date_impression->format('Y-m-d H:i:s');
$date_impression_sql = $date_impression->format('Y-m-d H:i:s');

if (new DateTime() < $date_impression) {
    $sqlquery = "SELECT * FROM `$table` WHERE `Origine` <> 'FrancoisDesAlpes' ORDER BY UPPER(NOM) ASC";
} else {
    $sqlquery = "SELECT * FROM `$table` WHERE `Origine` <> 'FrancoisDesAlpes' AND `ModifiedTime` > '".$date_impression_sql."' ORDER BY UPPER(NOM) ASC";

}$result = $bdd->query($sqlquery);

while ($row = mysqli_fetch_row($result)) {
    $Id = $row[0];
    $url = 'http://journees.slat.asso.fr/fiche.php?Id=' . $Id; // Remplacez par l'URL correcte de votre site
    $pageContent = file_get_contents_using_curl($url); // Utilisez une fonction pour obtenir le contenu
    echo $pageContent; // Affichez le contenu de la page
}

// Fonction pour obtenir le contenu en utilisant cURL
function file_get_contents_using_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
?>
