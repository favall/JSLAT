<?php
// Désactive l'affichage des erreurs PHP à l'écran pour les visiteurs.
// C'est une sécurité importante en production pour ne pas révéler la structure du serveur.
ini_alter("display_errors", false); 

// Forcer le fuseau horaire et la langue française pour s'assurer que les dates 
// générées (comme lors des transactions) soient correctes, peu importe la configuration du serveur.
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');

// On appelle le fichier de configuration contenant les mots de passe.
// __DIR__ garantit que le chemin est absolu depuis le dossier actuel, évitant les erreurs d'inclusion.
require_once __DIR__ . '/config.php';

// Établissement de la connexion à la base de données en utilisant 
// les constantes (DB_HOST, DB_USER, etc.) définies dans config.php.
$bdd = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($bdd, "utf8");

// On vérifie immédiatement si la connexion a échoué.
if (mysqli_connect_errno()) {
    // Si c'est le cas, on affiche un message d'erreur et on arrête brutalement l'exécution du script (exit) 
    // pour éviter que le reste du code ne plante en essayant de lire une base de données inactive.
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
}
?>
