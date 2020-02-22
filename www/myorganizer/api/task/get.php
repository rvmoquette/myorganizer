<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/task.php';
    if (API_REST_ACCESS_GET_TASK == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_task = new task();

/*
    +-------+
    |  Get  |
    +-------+
*/
    $Code_task = (int) lecture_parametre_api("Code_task", 0);
    $retour_json = [];
    $retour_json['get'] = $table_task->mf_get( $Code_task, ['autocompletion' => true]);
    fermeture_connexion_db();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
