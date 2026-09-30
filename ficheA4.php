<?php

include('connection.php');
require_once 'inc/functions.php';
$table = "VenteSLAT";

// ****************************************************
$Id = $_GET['Id'] ;
$Matricule = f_decrypt('!Slatparapente31!', $Id);

// ****************************************************

$sqlquery="SELECT * FROM `$table` WHERE Matricule='$Matricule' ORDER BY Id";
$result = $bdd->query($sqlquery);

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