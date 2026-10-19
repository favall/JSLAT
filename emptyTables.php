<?php
session_start();

include('connection.php');

// Désactive les exceptions automatiques MySQLi pour gérer les erreurs
mysqli_report(MYSQLI_REPORT_OFF);

// Vérification de la connexion
if (!isset($bdd) || !($bdd instanceof mysqli)) {
    die("Erreur : connexion MySQLi indisponible.");
}

// Tables à purger
$tables = array(
    'VenteSLAT.log',
    'VenteSLAT_factu',
    'VenteSLAT'
);

$erreur = '';
$resultats = array();
$purgeTerminee = false;

// Génération du jeton de sécurité du formulaire
if (!isset($_SESSION['purge_token'])) {
    $aleatoire = openssl_random_pseudo_bytes(32);

    if ($aleatoire === false) {
        die("Erreur : impossible de sécuriser le formulaire.");
    }

    $_SESSION['purge_token'] = bin2hex($aleatoire);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification du jeton de sécurité
    if (
        !isset($_POST['csrf_token'], $_SESSION['purge_token']) ||
        !hash_equals(
            $_SESSION['purge_token'],
            $_POST['csrf_token']
        )
    ) {
        $erreur = "Demande invalide. Rechargez la page.";

    // Vérification de la confirmation explicite
    } elseif (
        !isset($_POST['confirmation']) ||
        $_POST['confirmation'] !== 'PURGER'
    ) {
        $erreur = "Confirmation incorrecte. Saisissez PURGER.";

    } else {

        // Début de la transaction
        if (!$bdd->begin_transaction()) {
            $erreur = "Impossible de démarrer la transaction : "
                    . $bdd->error;

        } else {

            $ok = true;

            foreach ($tables as $table) {

                // Protection du nom de table
                $nomTable = '`' . str_replace('`', '``', $table) . '`';

                $sql = "DELETE FROM " . $nomTable;

                if (!$bdd->query($sql)) {
                    $erreur = "Erreur lors de la purge de "
                            . $table . " : " . $bdd->error;

                    $ok = false;
                    break;
                }

                $resultats[$table] = $bdd->affected_rows;
            }

            if ($ok) {

                // Validation des suppressions
                if ($bdd->commit()) {
                    $purgeTerminee = true;

                    // Empêche la réutilisation du formulaire
                    unset($_SESSION['purge_token']);

                } else {
                    $erreur = "Erreur lors de la validation : "
                            . $bdd->error;

                    $bdd->rollback();
                    $resultats = array();
                }

            } else {
                // Annulation des suppressions en cas d'erreur
                $bdd->rollback();
                $resultats = array();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Purge des tables VenteSLAT</title>
</head>

<body>

<h1>Purge des tables VenteSLAT</h1>

<?php if ($purgeTerminee) { ?>

    <h2>Purge terminée avec succès</h2>

    <ul>
        <?php foreach ($resultats as $table => $nombre) { ?>
            <li>
                <?php
                echo htmlspecialchars(
                    $table,
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
                :
                <?php echo (int) $nombre; ?>
                ligne(s) supprimée(s)
            </li>
        <?php } ?>
    </ul>

<?php } else { ?>

    <p style="color: red; font-size: 1.2em; font-weight: bold;">
        ATTENTION : ACTION IRRÉVERSIBLE !
    </p>

    <p>
        Toutes les données des tables suivantes seront supprimées :
    </p>

    <ul>
        <?php foreach ($tables as $table) { ?>
            <li>
                <?php
                echo htmlspecialchars(
                    $table,
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </li>
        <?php } ?>
    </ul>

    <p>
        Les tables elles-mêmes et leur structure seront conservées.
    </p>

    <?php if ($erreur !== '') { ?>
        <p style="color: red; font-weight: bold;">
            <?php
            echo htmlspecialchars(
                $erreur,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>
        </p>
    <?php } ?>

    <form method="post">

        <input
            type="hidden"
            name="csrf_token"
            value="<?php
                echo htmlspecialchars(
                    $_SESSION['purge_token'],
                    ENT_QUOTES,
                    'UTF-8'
                );
            ?>"
        >

        <p>
            Êtes-vous sûr de vouloir continuer ?
            Cette action est irréversible.
        </p>

        <label for="confirmation">
            Pour confirmer, saisissez <strong>PURGER</strong> :
        </label>

        <br><br>

        <input
            type="text"
            id="confirmation"
            name="confirmation"
            required
            autocomplete="off"
        >

        <button type="submit">
            Purger définitivement
        </button>

    </form>

<?php } ?>

</body>
</html>
