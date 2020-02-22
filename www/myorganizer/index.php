<?php declare(strict_types=1);

include __DIR__ . '/../../systeme/myorganizer/espace_privee.php';

if (! $cache->start()) {

    /* Chargement des tables */

    require __DIR__ . '/scripts/lecture_parametres.php';

    /* Chargement des actions */

    require __DIR__ . '/scripts/genealogie.php';

    ob_start(); include __DIR__ . '/scripts/css.php'; $css = ob_get_clean();
    ob_start(); include __DIR__ . '/scripts/js.php'; $js = ob_get_clean();
    ob_start(); include __DIR__ . '/scripts/menu.php'; $menu = ob_get_clean();

    $mess = ( (isset($retour) && $retour['code_erreur']>0) ? (isset($mf_libelle_erreur[$retour['code_erreur']]) ? $mf_libelle_erreur[$retour['code_erreur']] : 'ERREUR N_'.$retour['code_erreur'] ) : '' );

    $code_html = generer_menu_bandeau();
    /* Chargement des forms */

    $menu_a_droite->ajouter_bouton_deconnexion();

    echo recuperer_gabarit('main/page.html', [
            '{titre_page}' => 'Accueil',
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
