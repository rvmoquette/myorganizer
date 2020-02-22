<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';

/*
    +------------+
    |  Modifier  |
    +------------+
*/
    $name = lecture_parametre_api('name', '');
    $value = lecture_parametre_api($name);
    if ($value !== null) {
        Hook_mf_systeme::controle_parametres_session($name, $value);
    }
    if ($value !== null) {
        $_SESSION[PREFIXE_SESSION]['parametres'][$name] = $value;
    }
    session_write_close();
    fermeture_connexion_db();
    $retour_json = [];
    $retour_json['code_erreur'] = 0;
    $retour_json['message_erreur'] = 0;
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end-$time_start, 4);
    vue_api_echo($retour_json);
