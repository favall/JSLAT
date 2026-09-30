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
    <div class="container">
        <img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

        <h1>Liste des vendeurs</h1>
        
	     <br>
	     <center><font color="red">
	     <h3>Pensez à rafraichir la page de temps en temps (touche F5).</h3>
		  </font></center>    


        <?php

        include('connection.php');
        require_once 'inc/functions.php';
        $table = "VenteSLAT";

		  // On récupère le nombre d'articles en vente
        $sqlquery = "SELECT * FROM `$table`";
        $result = $bdd->query($sqlquery);
        $NbArticles = mysqli_num_rows($result);

		  // On récupère le nombre de vendeurs
        $sqlquery = "SELECT DISTINCT MATRICULE FROM `$table`";
        $result = $bdd->query($sqlquery);
        $NbVendeurs = mysqli_num_rows($result);

        echo '<h3>Nombre total de vendeurs : ' . $NbVendeurs . ' (pour ' . $NbArticles . ' articles)</h3>';

        $sqlquery = "SELECT UPPER(Nom) AS Nom, Prenom AS 'Prénom', Matricule AS 'Matricule / Commande', emailPerso AS Email, phoneNumber as Portable, count(Id) AS Articles, MAX(ModifiedTime) AS Modification FROM `$table` WHERE `Origine` <> 'FrancoisDesAlpes' GROUP BY Nom, Prenom, Matricule, Email, Portable ORDER BY Modification DESC";
        $result = $bdd->query($sqlquery);

        echo '<table class="table" id="demo"><thead><tr>';
        while ($field_info = mysqli_fetch_field($result)) {
            echo '<th>', "{$field_info->name}", "</th>";
        }
        echo '<th>Lien Admin</th><th>Lien Fiche</th>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo '<td>', $row['Nom'], "</td>";
            echo '<td>', $row['Prénom'], "</td>";
            echo '<td>', $row['Matricule / Commande'], "</td>";
            echo '<td>', $row['Email'], "</td>";
            echo '<td>', $row['Portable'], "</td>";
            echo '<td>', $row['Articles'], "</td>";
            //echo '<td>', $row['Liste'], "</td>";
            echo '<td>', $row['Modification'], "</td>";
            $chaine_crypte = f_crypt("!Slatparapente31!", $row['Matricule / Commande']);
            $lien = "modfich_admin.php?Id=" . urlencode($chaine_crypte);
            echo '<td><a href=', "$lien", ' target="_blank">', "Admin", '</a></td>';
            $lien = "ficheA4.php?Id=" . urlencode($chaine_crypte);
            echo '<td><a href=', "$lien", ' target="_blank">', "Fiche", '</a></td>';
            echo "</tr>";
        }
        echo '</tbody>';
        echo "</table>";

        mysqli_close($bdd);

        ?>

    </div>


</body>

</html>

<script src="inc/tablefilter/tablefilter.js"></script>
<script data-config>
    var tfConfig = {
        base_path: 'inc/tablefilter/',
        auto_filter: {
            delay: 1 //milliseconds
        },
        //col_5: 'select',
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
        }, {
            name: 'colOps',
            id: ['demosum2'],
            col: [10],
            operation: ['sum'],
            decimal_precision: [0]
        }]
    };
    var tf = new TableFilter('demo', tfConfig);
    tf.init();
</script>
