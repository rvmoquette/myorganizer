<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/label.php';
    if (API_REST_ACCESS_GET_LABEL == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_label = new label();

/*
    +-------+
    |  Get  |
    +-------+
*/
    $Code_label = (int) lecture_parametre_api("Code_label", 0);
    $retour_json = [];
    $retour_json['get'] = $table_label->mf_get( $Code_label, ['autocompletion' => true]);
    fermeture_connexion_db();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
