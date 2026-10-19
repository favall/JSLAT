<?php
session_start();

include('connection.php');

// Hypothèse : connection.php crée une connexion mysqli dans $conn.
if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Erreur : connexion MySQLi introuvable dans connection.php.");
}

// Évite les exceptions automatiques de MySQLi
mysqli_report(MYSQLI_REPORT_OFF);

// Tables à purger, dans l'ordre
// VenteSLAT.log est ici interprétée comme un nom de table littéral.
$tables = array(
    'VenteSLAT.log',
    'VenteSLAT_factu',
    'VenteSLAT'
);

$erreur = '';
$resultats = array();
$purgeTerminee = false;

// Création d'un jeton de sécurité pour le formulaire
if (!isset($_SESSION['purge_token'])) {
    if (!function_exists('openssl_random_pseudo_bytes')) {
        die("Erreur : OpenSSL est nécessaire pour sécuriser le formulaire.");
    }

    $_SESSION['purge_token'] = bin2hex(
        openssl_random_pseudo_bytes(32)
    );
}

// Traitement du formulaire de confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification du jeton de sécurité
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['purge_token'],
            $_POST['csrf_token']
        )
    ) {
        $erreur = "Demande invalide. Rechargez la page et réessayez.";

    // Vérification de la confirmation explicite
    } elseif (
        !isset($_POST['confirmation']) ||
        $_POST['confirmation'] !== 'PURGER'
    ) {
        $erreur = "Confirmation incorrecte. Saisissez exactement PURGER.";

    } else {

        // Démarrage d'une transaction
        if (!$conn->begin_transaction()) {
            $erreur = "Impossible de démarrer la transaction : "
                    . $conn->error;

        } else {

            $ok = true;

            foreach ($tables as $table) {

                // Protection du nom de table, y compris s'il contient un point
                $nomTable = '`' . str_replace('`', '``', $table) . '`';

                $sql = "DELETE FROM " . $nomTable;

                if (!$conn->query($sql)) {
                    $erreur = "Erreur lors de la purge de la table "
                            . $table . " : " . $conn->error;

                    $ok = false;
                    break;
                }

                $resultats[$table] = $conn->affected_rows;
            }

            if ($ok) {

                if ($conn->commit()) {
                    $purgeTerminee = true;
                    unset($_SESSION['purge_token']);

                } else {
                    $erreur = "Erreur lors de la validation : "
                            . $conn->error;

                    $conn->rollback();
                    $resultats = array();
                }

            } else {
                // Annulation de la transaction en cas d'erreur
                $conn->rollback();
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

    <h2>Purge terminée</h2>

    <p>Les tables suivantes ont été purgées avec succès :</p>

    <ul>
        <?php foreach ($resultats as $table => $nombre) { ?>
            <li>
                <?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?>
                : <?php echo (int) $nombre; ?> ligne(s) supprimée(s)
            </li>
        <?php } ?>
    </ul>

<?php } else { ?>

    <p style="color: red; font-weight: bold;">
        ATTENTION : cette action est irréversible.
    </p>

    <p>
        Toutes les données des tables suivantes seront supprimées :
    </p>

    <ul>
        <?php foreach ($tables as $table) { ?>
            <li>
                <?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?>
            </li>
        <?php } ?>
    </ul>

    <p>La structure des tables sera conservée.</p>

    <?php if ($erreur !== '') { ?>
        <p style="color: red;">
            <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
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
            Cette opération est irréversible.
        </p>

        <label for="confirmation">
            Saisissez <strong>PURGER</strong> pour confirmer :
        </label>

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
