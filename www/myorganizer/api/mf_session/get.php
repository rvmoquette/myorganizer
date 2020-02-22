<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';

    fermeture_connexion_db();

/*
    +-------+
    |  Get  |
    +-------+
*/
    $name = lecture_parametre_api('name', '');
    $retour_json = [];
    $retour_json['get'][$name] = ( isset($_SESSION[PREFIXE_SESSION]['parametres'][$name]) ? $_SESSION[PREFIXE_SESSION]['parametres'][$name] : null );
    session_write_close();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
