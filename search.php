<?php

error_reporting(E_ALL);
ini_set('display_errors', '0');

// La réponse est au format JSON, en UTF-8.
header('Content-Type: application/json; charset=utf-8');

// Connexion à la base de données.
require_once __DIR__ . '/connection.php';

// Vérification du paramètre de recherche.
$searchTerm = $_GET['term'] ?? '';

if (!is_string($searchTerm) || trim($searchTerm) === '') {
    echo json_encode([]);
    exit;
}

// Protection de la valeur utilisée dans la requête SQL.
$searchTerm = mysqli_real_escape_string($bdd, trim($searchTerm));

$tableName = "VenteSLAT";
$rowName = "Id";

// Initialisation du tableau des résultats.
$data = array();

// Recherche des articles dont l'ID correspond.
// Le filtre inclut les statuts "Réceptionné" et "Non réceptionné".
$sql = "SELECT *
        FROM " . $tableName . "
        WHERE Id LIKE '%" . $searchTerm . "%'
        AND Statut LIKE '%éceptionné'
        ORDER BY " . $rowName . " ASC";

// Exécution de la requête avec la connexion $bdd.
$query = $bdd->query($sql);

// Vérification du résultat de la requête.
if ($query === false) {
    error_log('Erreur SQL dans search.php : ' . $bdd->error);
    http_response_code(500);
    echo json_encode([]);
    exit;
}

// Construction de la liste des résultats.
while ($row = $query->fetch_assoc()) {
    $data[] = $row["Id"]
        . ': ' . $row["TypeArticle"]
        . ' de marque ' . $row["MarqueArticle"]
        . ' taille ' . $row["TailleArticle"]
        . ' (' . $row["PrixArticle"] . ' euros)';
}

// Libération du résultat SQL.
$query->free();

// Retour des données au format JSON.
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>
