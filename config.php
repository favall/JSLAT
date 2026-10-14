<?php
// =========================================================================
// config.php
// Ce fichier centralise la configuration globale de l'application.
// Il peut être envoyé sur GitHub en toute sécurité car il ne contient 
// AUCUN mot de passe de production en clair.
// =========================================================================

// COMMENT ÇA MARCHE ?
// La fonction getenv('NOM_VARIABLE') demande au serveur (ici, le conteneur Coolify)
// s'il possède une variable secrète portant ce nom en mémoire.
// Le symbole "?:" (opérateur Elvis) signifie : 
// "Si la variable existe dans Coolify, utilise-la. SINON, utilise la valeur par défaut."
// La valeur par défaut (à droite) ne sert que pour les tests sur ton ordinateur local.

// -------------------------------------------------------------------------
// 1. CONFIGURATION DE LA BASE DE DONNÉES
// -------------------------------------------------------------------------

// Hôte de la base : correspond au nom du service BDD dans le réseau Docker Coolify
define('DB_HOST', getenv('DB_HOST')); 

// Utilisateur de la base de données
define('DB_USER', getenv('DB_USER'));

// Mot de passe de la base de données (laissé vide en local par défaut sur WAMP/XAMPP)
define('DB_PASS', getenv('DB_PASS'));

// Nom de la base de données
define('DB_NAME', getenv('DB_NAME'));


// -------------------------------------------------------------------------
// 2. CONFIGURATION DU SERVEUR SMTP (POUR L'ENVOI D'EMAILS)
// -------------------------------------------------------------------------

// Adresse du serveur SMTP (ex: Postmark)
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.postmarkapp.com');

// Port de connexion SMTP (souvent 587 pour TLS)
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);

// Identifiant de connexion au serveur SMTP
define('SMTP_USER', getenv('SMTP_USER'));

// Mot de passe ou Clé d'API du serveur SMTP
define('SMTP_PASS', getenv('SMTP_PASS'));

// Adresse e-mail de l'expéditeur (doit être validée chez le fournisseur SMTP, ex: Brevo)
define('SMTP_FROM', getenv('SMTP_FROM') ?: 'lesjournees@slat.info');

// Nom affiché pour l'expéditeur
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'ne pas répondre les journées SLAT');


// -------------------------------------------------------------------------
// 3. SÉCURITÉ DE L'APPLICATION
// -------------------------------------------------------------------------

// Clé secrète utilisée par les fonctions f_crypt() et f_decrypt() 
// pour chiffrer les données sensibles (comme les IBAN ou les matricules).
// En production, cette clé pourra être définie dans Coolify.
$appKey = getenv('APP_KEY');
if (!$appKey || strlen($appKey) < 32) {
    http_response_code(500);
    exit('Configuration incomplète.');
}
define('APP_KEY', $appKey);
?>
