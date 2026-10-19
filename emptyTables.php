<?php

mysqli_report(MYSQLI_REPORT_OFF);

session_start();

include('connection.php');

// Vérification de la connexion
if (!isset($bdd) || !($bdd instanceof mysqli)) {
    die("Erreur : connexion MySQLi indisponible.");
}

// Tables d'origine et leurs sauvegardes
$tables = array(
    'VenteSLAT'       => 'VenteSLAT.bak',
    'VenteSLAT.log'   => 'VenteSLAT.log.bak',
    'VenteSLAT_factu' => 'VenteSLAT_factu.bak'
);

$erreur = '';
$resultats = array();
$sauvegardes = array();
$erreursReset = array();
$purgeTerminee = false;

// Génération du jeton de sécurité
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
        !is_string($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['purge_token'],
            $_POST['csrf_token']
        )
    ) {
        $erreur = "Demande invalide. Rechargez la page.";

    // Confirmation explicite
    } elseif (
        !isset($_POST['confirmation']) ||
        $_POST['confirmation'] !== 'PURGER'
    ) {
        $erreur = "Confirmation incorrecte. Saisissez PURGER.";

    } else {

        $ok = true;

        /*
         * ÉTAPE 1 : suppression des anciennes sauvegardes
         */
        foreach ($tables as $table => $backup) {

            $nomBackup = '`' . str_replace('`', '``', $backup) . '`';

            if (!$bdd->query("DROP TABLE IF EXISTS " . $nomBackup)) {

                $erreur = "Impossible de supprimer l'ancienne sauvegarde "
                        . $backup . " : " . $bdd->error;

                $ok = false;
                break;
            }
        }

        /*
         * ÉTAPE 2 : création des sauvegardes
         */
        if ($ok) {

            foreach ($tables as $table => $backup) {

                $nomTable = '`' . str_replace('`', '``', $table) . '`';
                $nomBackup = '`' . str_replace('`', '``', $backup) . '`';

                // Copie de la structure et des index
                $sql = "CREATE TABLE " . $nomBackup
                     . " LIKE " . $nomTable;

                if (!$bdd->query($sql)) {

                    $erreur = "Échec de la création de la sauvegarde "
                            . $backup . " : " . $bdd->error;

                    $ok = false;
                    break;
                }

                // Copie des données
                $sql = "INSERT INTO " . $nomBackup
                     . " SELECT * FROM " . $nomTable;

                if (!$bdd->query($sql)) {

                    $erreur = "Échec de la copie des données de "
                            . $table . " vers " . $backup
                            . " : " . $bdd->error;

                    $ok = false;
                    break;
                }

                $sauvegardes[$backup] = $bdd->affected_rows;
            }
        }

        /*
         * ÉTAPE 3 : purge des tables d'origine
         * Uniquement si toutes les sauvegardes ont réussi.
         */
        if ($ok) {

            if (!$bdd->begin_transaction()) {

                $erreur = "Impossible de démarrer la transaction : "
                        . $bdd->error;

            } else {

                foreach ($tables as $table => $backup) {

                    $nomTable = '`' . str_replace('`', '``', $table) . '`';

                    if (!$bdd->query("DELETE FROM " . $nomTable)) {

                        $erreur = "Échec de la purge de "
                                . $table . " : " . $bdd->error;

                        $ok = false;
                        break;
                    }

                    $resultats[$table] = $bdd->affected_rows;
                }

                if ($ok) {

                    if ($bdd->commit()) {

                        $purgeTerminee = true;

                        // Empêche la réutilisation du formulaire
                        unset($_SESSION['purge_token']);

                        /*
                         * ÉTAPE 4 : remise à zéro des compteurs ID
                         *
                         * Cette opération est effectuée après le commit,
                         * car ALTER TABLE peut valider implicitement
                         * une transaction MySQL.
                         */
                        foreach ($tables as $table => $backup) {

                            $nomTable = '`'
                                      . str_replace('`', '``', $table)
                                      . '`';

                            $sql = "ALTER TABLE "
                                 . $nomTable
                                 . " AUTO_INCREMENT = 1";

                            if (!$bdd->query($sql)) {

                                $erreursReset[$table] = $bdd->error;
                            }
                        }

                    } else {

                        $erreur = "Échec de validation de la purge : "
                                . $bdd->error;

                        $bdd->rollback();
                        $resultats = array();
                    }

                } else {

                    $bdd->rollback();
                    $resultats = array();
                }
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

    <h2 style="color: green;">
        Purge terminée
    </h2>

    <h3>Sauvegardes créées</h3>

    <ul>
        <?php foreach ($sauvegardes as $table => $nombre) { ?>
            <li>
                <?php
                echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8');
                ?>
                : <?php echo (int) $nombre; ?>
                ligne(s) sauvegardée(s)
            </li>
        <?php } ?>
    </ul>

    <h3>Lignes supprimées</h3>

    <ul>
        <?php foreach ($resultats as $table => $nombre) { ?>
            <li>
                <?php
                echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8');
                ?>
                : <?php echo (int) $nombre; ?>
                ligne(s) supprimée(s)
            </li>
        <?php } ?>
    </ul>

    <?php if (empty($erreursReset)) { ?>

        <p style="color: green; font-weight: bold;">
            Les compteurs AUTO_INCREMENT ont été remis à 1.
            Le prochain ajout pourra donc recevoir l'ID 1.
        </p>

    <?php } else { ?>

        <h3 style="color: red;">
            Attention : certains compteurs n'ont pas été réinitialisés
        </h3>

        <ul>
            <?php foreach ($erreursReset as $table => $message) { ?>
                <li>
                    <?php
                    echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8');
                    ?>
                    :
                    <?php
                    echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
                    ?>
                </li>
            <?php } ?>
        </ul>

        <p>
            La purge est effectuée, mais vérifiez les compteurs concernés.
        </p>

    <?php } ?>

<?php } else { ?>

    <p style="color: red; font-size: 1.2em; font-weight: bold;">
        ATTENTION : ACTION IRRÉVERSIBLE !
    </p>

    <p>Les opérations suivantes seront effectuées :</p>

    <ol>
        <li>Suppression des anciennes sauvegardes.</li>
        <li>Création des nouvelles sauvegardes.</li>
        <li>Purge des tables d'origine si les sauvegardes réussissent.</li>
        <li>Remise à 1 des compteurs AUTO_INCREMENT.</li>
    </ol>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Table d'origine</th>
            <th>Sauvegarde</th>
        </tr>

        <?php foreach ($tables as $table => $backup) { ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($backup, ENT_QUOTES, 'UTF-8'); ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <p>
        <strong>Attention :</strong>
        les anciennes sauvegardes seront remplacées.
    </p>

    <?php if ($erreur !== '') { ?>

        <p style="color: red; font-weight: bold;">
            <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <?php if (!empty($sauvegardes)) { ?>
            <p>
                Certaines sauvegardes ont été créées avant l'erreur.
                Vérifiez leur contenu avant de relancer l'opération.
            </p>
        <?php } ?>

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
            Saisissez <strong>PURGER</strong> pour confirmer :
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
            Sauvegarder puis purger
        </button>

    </form>

<?php } ?>

</body>
</html>
