<?php declare(strict_types=1);

include __DIR__ . '/../../systeme/myorganizer/espace_privee.php';

if (! $cache->start()) {

    /* Chargement des tables */
        $table_task = new task();
    $table_a_user_task = new a_user_task();

    require __DIR__ . '/scripts/lecture_parametres.php';

    /* Chargement des actions */
        include __DIR__ . '/code/_task_actions.php';
    include __DIR__ . '/code/_a_user_task_actions.php';

    require __DIR__ . '/scripts/genealogie.php';

    ob_start(); include __DIR__ . '/scripts/css.php'; $css = ob_get_clean();
    ob_start(); include __DIR__ . '/scripts/js.php'; $js = ob_get_clean();
    ob_start(); include __DIR__ . '/scripts/menu.php'; $menu = ob_get_clean();

    $mess = ((isset($retour) && $retour['code_erreur']>0) ? (isset($mf_libelle_erreur[$retour['code_erreur']]) ? $mf_libelle_erreur[$retour['code_erreur']] : 'ERREUR N_'.$retour['code_erreur'] ) : '');

    $code_html = '';
    /* Chargement des forms */
        include __DIR__ . '/code/_task_form.php';
        if (mf_Code_task()) {
            include __DIR__ . '/code/_a_user_task_form.php';
        }

    $menu_a_droite->ajouter_bouton_deconnexion();

    echo recuperer_gabarit('main/page.html', [
            '{titre_page}' => 'task',
            '{css}' => $css,
            '{js}' => $js,
            '{menu_principal}' => $menu,
            '{fil_ariane}' => $fil_ariane->generer_code(),
            '{sections}' => $code_html,
            '{menu_secondaire}' => $menu_a_droite->generer_code(),
            '{script_end}' => generer_script_maj_auto(),
            '{header}' => recuperer_gabarit('main/header.html',[]),
            '{footer}' => recuperer_gabarit('main/footer.html',[])
    ], true);

    $cache->end();

}

fermeture_connexion_db();
