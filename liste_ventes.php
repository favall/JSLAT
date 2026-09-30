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
            background-size: 100%;
        }

        h1 {
            text-align: center;
        }
    </style>

</head>

<body>
    <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

    <h1>Liste des ventes</h1>

    <?php

    include('connection.php');
    require_once 'inc/functions.php';

    $table = "VenteSLAT";

    $sqlquery = "SELECT UPPER(`Nom`) AS Nom, `Prenom`, `Matricule`, `emailPerso` AS Email, `phoneNumber` AS Portable, `IBAN`, COUNT(`Id`) AS 'Article(s) Vendu(s)', SUM(`PrixArticle`) as 'Total Vendeur' FROM `$table` WHERE `Origine` <> 'FrancoisDesAlpes' AND `Statut`='Vendu' GROUP BY Nom, Prenom, Matricule, Email, Portable, IBAN ORDER BY Nom";
    $result = $bdd->query($sqlquery);

    echo '<table class="table" id="demo"><thead><tr>';
    while ($field_info = mysqli_fetch_field($result)) {
        echo '<th>', "{$field_info->name}", '</th>';
    }
    echo '<th>Total - 3%</th></thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {

        $IBAN = f_decrypt('!Slatparapente31!', $row['IBAN']);
        $PrixLessComm = 0.97 * $row['Total Vendeur'];
        echo '<tr>';
        echo '<td>', $row['Nom'], '</td>';
        echo '<td>', $row['Prenom'], '</td>';
        echo '<td>', $row['Matricule'], '</td>';
        echo '<td>', $row['Email'], '</td>';
        echo '<td>', $row['Portable'], '</td>';
        echo '<td>', $IBAN, '</td>';
        echo '<td>', $row['Article(s) Vendu(s)'], '</td>';
        echo '<td>', $row['Total Vendeur'], '</td>';
        echo '<td>', $PrixLessComm, '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    mysqli_close($bdd);

    ?>

</body>

</html>

<script src="inc/tablefilter/tablefilter.js"></script>
<script data-config>
    var tfConfig = {
        base_path: 'inc/tablefilter/',
        auto_filter: {
            delay: 1 //milliseconds
        },
        col_9: 'select',
        col_10: 'select',
        filters_row_index: 1,
        state: false,
        alternate_rows: true,
        rows_counter: true,
        btn_reset: {
            text: 'Effacer les filtres'
        },
        status_bar: false,
        msg_filter: 'Filtering...',
        extensions: [{
            name: 'sort'
        }]
    };
    var tf = new TableFilter('demo', tfConfig);
    tf.init();
</script>
