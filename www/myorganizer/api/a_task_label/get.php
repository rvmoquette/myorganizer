<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/a_task_label.php';
    if (API_REST_ACCESS_GET_A_TASK_LABEL == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_a_task_label = new a_task_label();

/*
    +-------+
    |  Get  |
    +-------+
*/
    $Code_task = (int) lecture_parametre_api("Code_task", 0 );
    $Code_label = (int) lecture_parametre_api("Code_label", 0 );
    $retour_json = [];
    $retour_json['get'] = $table_a_task_label->mf_get($Code_task, $Code_label, ['autocompletion' => true]);
    fermeture_connexion_db();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
