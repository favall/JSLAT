<?php
ini_alter("display_errors", true);
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');

//Sert au fiche_all
$date_impression = "2025-11-13 09:00:00";

//Affiché à divers endroits, dont mail de confirmation
$date_depot = "13/11/2025 (de 12h00 à 19h) ou le 14/11/2025 (de 12h00 à 18h)";

//Sert à forcer le statut à "Réceptionné", même s'il a été vendu lors des préventes (dans modfich_locked)
$date_fin_prevente = "2025-11-15 10:00:00";

//Affiché à divers endroits, dont mail de confirmation et fiche papier (partie vendeur)
$date_retour = "Mardi 9/12 (15h à 19h),  Mercredi 10/12 (15h à 19h), Jeudi 11/12 (11h à 14h)";