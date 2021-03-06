<?php declare(strict_types=1);

    $time_start = microtime(true);

    include __DIR__ . '/../../../../systeme/myorganizer/acces_api_rest/user.php';
    if (API_REST_ACCESS_POST_USER == 'all') {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';
    } else {
        include __DIR__ . '/../../../../systeme/myorganizer/api_espace_privee.php';
    }

    session_write_close();

    $table_user = new user();

/*
    +-----------+
    |  Ajouter  |
    +-----------+
*/
    $user_Login = (string) lecture_parametre_api("user_Login", '');
    $user_Password = (string) lecture_parametre_api("user_Password", '');
    $user_Email = (string) lecture_parametre_api("user_Email", '');
    $user_Admin = (bool) lecture_parametre_api("user_Admin", '');
    $retour = $table_user->mf_ajouter($user_Login, $user_Password, $user_Email, $user_Admin);
    if ( $retour['code_erreur']==0 )
    {
        $cache = new Cachehtml();
        $cache->clear();
    }
    $retour_json = [];
    $retour_json['code_erreur'] = $retour['code_erreur'];
    $retour_json['message_erreur'] = ( (isset($retour) && $retour['code_erreur']>0) ? (isset($mf_libelle_erreur[$retour['code_erreur']]) ? $mf_libelle_erreur[$retour['code_erreur']] : 'ERREUR N_'.$retour['code_erreur'] ) : '' );
    $retour_json['Code_user'] = $retour['Code_user'];
    fermeture_connexion_db();
    $time_end = microtime(true);
    $retour_json['duree'] = round($time_end - $time_start, 4);
    vue_api_echo($retour_json);
