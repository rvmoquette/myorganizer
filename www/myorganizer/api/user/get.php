<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/user.php';
    if (API_REST_ACCESS_GET_USER == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_user = new user();

/*
    +-------+
    |  Get  |
    +-------+
*/
    $Code_user = (int) lecture_parametre_api("Code_user", $user_courant['Code_user']);
    $retour_json = [];
    $retour_json['get'] = $table_user->mf_get( $Code_user, ['autocompletion' => true]);
    unset($retour_json['get']['user_Password']);
    fermeture_connexion_db();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
