<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/task.php';
    if (API_REST_ACCESS_PUT_TASK == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_task = new task();

/*
    +------------+
    |  Modifier  |
    +------------+
*/
    $Code_task = lecture_parametre_api("Code_task", 0 );
    $champs = [];
    if ( isset_parametre_api("task_Name") ) $champs['task_Name'] = lecture_parametre_api("task_Name");
    if ( isset_parametre_api("task_Date_creation") ) $champs['task_Date_creation'] = lecture_parametre_api("task_Date_creation");
    if ( isset_parametre_api("task_Description") ) $champs['task_Description'] = lecture_parametre_api("task_Description");
    if ( isset_parametre_api("task_Workflow") ) $champs['task_Workflow'] = lecture_parametre_api("task_Workflow");
    if ( isset_parametre_api("Code_user") ) $champs['Code_user'] = lecture_parametre_api("Code_user");
    $retour = $table_task->mf_modifier_2([$Code_task => $champs]);
    if ($retour['code_erreur'] == 0) {
        $cache = new Cachehtml();
        $cache->clear();
    }
    $retour_json = [];
    $retour_json['code_erreur'] = $retour['code_erreur'];
    $retour_json['message_erreur'] = ( (isset($retour) && $retour['code_erreur']>0) ? (isset($mf_libelle_erreur[$retour['code_erreur']]) ? $mf_libelle_erreur[$retour['code_erreur']] : 'ERREUR N_'.$retour['code_erreur'] ) : '' );
    fermeture_connexion_db();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
