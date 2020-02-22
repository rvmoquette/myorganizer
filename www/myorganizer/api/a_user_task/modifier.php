<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/a_user_task.php';
    if (API_REST_ACCESS_PUT_A_USER_TASK == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_a_user_task = new a_user_task();

/*
    +------------+
    |  Modifier  |
    +------------+
*/
    $Code_user = lecture_parametre_api("Code_user", $user_courant['Code_user'] );
    $Code_task = lecture_parametre_api("Code_task", 0 );
    $champs = ['Code_user'=>$Code_user, 'Code_task'=>$Code_task];
    if ( isset_parametre_api("a_user_task_Link") ) $champs['a_user_task_Link'] = lecture_parametre_api("a_user_task_Link");
    $retour = $table_a_user_task->mf_modifier_2([$champs]);
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
