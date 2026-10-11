<?php
ini_alter("display_errors", true);
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');

//Sert au fiche_all
$date_impression = new DateTime('2026-11-19 09:00:00');

//Sert à empêcher les saisies après la fin du dépôt
$date_fin_depot = new DateTime('2026-11-20 17:30:00');

//Affiché à divers endroits, dont mail de confirmation
$date_depot = "19/11/2025 (de 11h30 à 18h) ou le 20/11/2025 (de 11h30 à 17h30)";

//Sert à forcer le statut à "Réceptionné", même s'il a été vendu lors des préventes (dans modfich_locked)
$date_fin_prevente = "2026-11-21 10:00:00";

//Affiché à divers endroits, dont mail de confirmation et fiche papier (partie vendeur)
$date_retour = "Mardi 9/12 (15h à 19h),  Mercredi 10/12 (15h à 19h), Jeudi 11/12 (11h à 14h)";
