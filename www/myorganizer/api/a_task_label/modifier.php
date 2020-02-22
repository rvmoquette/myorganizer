<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/a_task_label.php';
    if (API_REST_ACCESS_PUT_A_TASK_LABEL == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_a_task_label = new a_task_label();

/*
    +------------+
    |  Modifier  |
    +------------+
*/
    $Code_task = lecture_parametre_api("Code_task", 0 );
    $Code_label = lecture_parametre_api("Code_label", 0 );
    $champs = ['Code_task'=>$Code_task, 'Code_label'=>$Code_label];
    if ( isset_parametre_api("a_task_label_Link") ) $champs['a_task_label_Link'] = lecture_parametre_api("a_task_label_Link");
    $retour = $table_a_task_label->mf_modifier_2([$champs]);
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
