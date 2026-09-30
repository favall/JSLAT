<?php
include('connection.php');

// Requête pour récupérer toutes les données de la première table
$sql1 = "SELECT * FROM VenteSLAT ORDER BY Id";
$result1 = $bdd->query($sql1);

// Requête pour récupérer toutes les données de la deuxième table
$sql2 = "SELECT * FROM VenteSLAT_factu ORDER BY Id";
$result2 = $bdd->query($sql2);

// Créer un fichier Excel et écrire les données
$filename = 'export_table_vente_SLAT.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Créer un objet PHPExcel
require_once 'inc/PHPExcel/PHPExcel.php';
$objPHPExcel = new PHPExcel();

// Première feuille de calcul pour la première table
$objPHPExcel->getActiveSheet()->setTitle('VenteSLAT');

// Ajouter les noms des colonnes dans la première ligne du fichier Excel pour la première table
$colNames1 = $result1->fetch_assoc();
if ($colNames1) {
    $objPHPExcel->getActiveSheet()->fromArray(array_keys($colNames1), null, 'A1');
}

// Ajouter les données récupérées depuis la première table SQL
$row1 = 2;
$result1->data_seek(0);
while ($row_data1 = $result1->fetch_assoc()) {
    $col = 0;
    foreach ($row_data1 as $value) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row1, $value);
        $col++;
    }
    $row1++;
}

// Deuxième feuille de calcul pour la deuxième table
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1); // Passer à la nouvelle feuille de calcul

$objPHPExcel->getActiveSheet()->setTitle('VenteSLAT_factu');

// Ajouter les noms des colonnes dans la première ligne du fichier Excel pour la deuxième table
$colNames2 = $result2->fetch_assoc();
if ($colNames2) {
    $objPHPExcel->getActiveSheet()->fromArray(array_keys($colNames2), null, 'A1');
}

// Ajouter les données récupérées depuis la deuxième table SQL
$row2 = 2;
$result2->data_seek(0);
while ($row_data2 = $result2->fetch_assoc()) {
    $col = 0;
    foreach ($row_data2 as $value) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row2, $value);
        $col++;
    }
    $row2++;
}

// Sortie du fichier Excel
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
try {
    $objWriter->save('php://output');
} catch (Exception $e) {
    echo 'Erreur lors de la génération du fichier Excel : ',  $e->getMessage(), "\n";
}

// Fermer la connexion à la base de données
$bdd->close();
?>
