<?php
include __DIR__ . '/dblayer_light.php';

if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
    $mf_connexion = new Mf_Connexion(false);
    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'])) {
        unset($_SESSION[PREFIXE_SESSION]['token']);
    }
}

if (! isset($_SESSION[PREFIXE_SESSION]['token'])) {
    $mf_token = lecture_parametre_api('mf_token', '');

    // pas d'erreur par defaut
    $retour_json = array(
        'code_erreur' => 0
    );

    $mf_connexion = new Mf_Connexion(true);
    if (! $mf_connexion->est_connecte($mf_token)) {
        $retour_json['code_erreur'] = 1;
        vue_api_echo($retour_json);
        exit();
    }
}
