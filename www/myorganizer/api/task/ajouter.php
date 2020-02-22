<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/task.php';
    if (API_REST_ACCESS_POST_TASK == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_task = new task();

/*
    +-----------+
    |  Ajouter  |
    +-----------+
*/
    $task_Name = (string) lecture_parametre_api("task_Name", '');
    $task_Date_creation = (string) lecture_parametre_api("task_Date_creation", '');
    $task_Description = (string) lecture_parametre_api("task_Description", '');
    $task_Workflow = (int) lecture_parametre_api("task_Workflow", '');
    $Code_user = (int) lecture_parametre_api("Code_user", $user_courant['Code_user']);
    $retour = $table_task->mf_ajouter($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user);
    if ( $retour['code_erreur']==0 )
    {
        $cache = new Cachehtml();
        $cache->clear();
    }
    $retour_json = [];
    $retour_json['code_erreur'] = $retour['code_erreur'];
    $retour_json['message_erreur'] = ( (isset($retour) && $retour['code_erreur']>0) ? (isset($mf_libelle_erreur[$retour['code_erreur']]) ? $mf_libelle_erreur[$retour['code_erreur']] : 'ERREUR N_'.$retour['code_erreur'] ) : '' );
    $retour_json['Code_task'] = $retour['Code_task'];
    fermeture_connexion_db();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
