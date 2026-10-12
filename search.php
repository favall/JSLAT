<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('connection.php');

$searchTerm = mysqli_real_escape_string($bdd, $_GET['term']);

$tableName = "VenteSLAT";
$rowName = "Id";

// Connexion à la base de données
//$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Initialisation du tableau $data
$data = array();

// Récupération des données correspondantes depuis la table
//$query = ‎$bdd->query("SELECT * FROM " . $tableName . " WHERE Id LIKE '%" . $searchTerm . "%' AND Statut = 'Réceptionné' ORDER BY " . $rowName . " ASC");
// On inclue aussi les non réceptionnés, pour éviter de coincer en caisse (ou lors de l'inventaire) si un article a été mal scanné
$query = $db->query("SELECT * FROM " . $tableName . " WHERE Id LIKE '%" . $searchTerm . "%' AND Statut LIKE '%éceptionné' ORDER BY " . $rowName . " ASC");

while ($row = $query->fetch_assoc()) {
    $data[] = $row["Id"] . ': ' . $row["TypeArticle"] . ' de marque ' . $row["MarqueArticle"] . ' taille ' . $row["TailleArticle"] . ' (' . $row["PrixArticle"] . ' euros)';
}

// Retournez les données au format JSON
echo json_encode($data);
?>
