<?php
include __DIR__ . '/dblayer_light.php';

if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
    $mf_connexion = new Mf_Connexion(false);
    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'], false)) {
        unset($_SESSION[PREFIXE_SESSION]['token']);
    }
}

if (! isset($_SESSION[PREFIXE_SESSION]['token'])) {
    $mf_token = lecture_parametre_api('mf_token', '');

    $mf_connexion = new Mf_Connexion(true);
    $mf_connexion->est_connecte($mf_token, false);
}
