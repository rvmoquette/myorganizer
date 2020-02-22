<?php

include __DIR__ . '/../../systeme/myorganizer/dblayer.php';

$mf_connexion = new Mf_Connexion();
$menu_a_droite = new Menu_a_droite();
$fil_ariane = new Fil_Ariane('Accueil', '');

if (isset($_GET['act'])) { $mf_action = $_GET['act']; } else { $mf_action = ''; }
$token = lecture_parametre_api('token', '');

$mess_2 = '';
if ($mf_action == 'new_pwd' && isset($_POST['validation_formulaire'])) {
    $mf_mdp_new_1 = $_POST['mf_mdp_new_1'];
    $mf_mdp_new_2 = $_POST['mf_mdp_new_2'];
    if ($mf_mdp_new_1 != $mf_mdp_new_2) {
        $mess_2 = 'Les mots de passe de correspondent pas !';
    } else {
        $retour = $mf_connexion->modifier_mdp_oublie($token, $mf_mdp_new_1);
        if ($retour['code_erreur'] == 0) {
            $mess_2 = 'Votre mot de passe a été modifié';
        }
    }
    $cache = new Cachehtml();
    $cache->clear();
}

$cache = new Cachehtml();

if (! $cache->start('new_pwd')) {

    ob_start(); include __DIR__ . '/scripts/css.php'; $css = ob_get_clean();
    ob_start(); include __DIR__ . '/scripts/js.php'; $js = ob_get_clean();
    ob_start(); include __DIR__ . '/scripts/menu.php'; $menu = ob_get_clean();

    $mess = ( (isset($retour) && $retour['code_erreur']>0) ? (isset($mf_libelle_erreur[$retour['code_erreur']]) ? $mf_libelle_erreur[$retour['code_erreur']] : 'ERREUR N_'.$retour['code_erreur'] ) : '' );
    if ($mess_2 != '') {
        $mess = $mess_2;
    }

    $code_html='';

    $fil_ariane = new Fil_Ariane('NOUVEAU MOT DE PASSE', '');

    $form = new Formulaire('', $mess);
    if ( ! ( isset($retour) && ( $retour['code_erreur']==0 || $retour['code_erreur']==7 ) ) )
    {
        $form->ajouter_input('mf_mdp_new_1', '', true, 'password');
        $form->ajouter_input('mf_mdp_new_2', '', true, 'password');
    }
    else
    {
        $form->ajouter_input('mf_mdp_new_1', '', true, 'hidden');
        $form->ajouter_input('mf_mdp_new_2', '', true, 'hidden');
        $form->bouton_fermer_page();
    }
    if ( TABLE_INSTANCE != '' )
    {
        $form -> ajouter_input('mf_instance', get_instance(), true, 'hidden');
    }
    $form -> ajouter_input('token', $token, true, 'hidden');
    $form->set_action('?act=new_pwd');
    if ( isset($retour) && $retour['code_erreur']==0 )
    {
        $form->set_action(ADRESSE_SITE);
    }
    $form->desactiver_le_mode_inline();
    $code_html .= $form->generer_BS4_Forms();

    echo recuperer_gabarit('main/page_procedure_mdp_oublie.html', array(
        '{titre_page}' => 'Procédure de récupération de mot de passe',
        '{css}' => $css,
        '{js}' => $js,
        '{menu_principal}' => $menu,
        '{fil_ariane}' => $fil_ariane->generer_code(),
        '{sections}' => $code_html,
        '{formulaire_inscription}' => '',
        '{menu_secondaire}' => $menu_a_droite->generer_code(),
        '{script_end}' => generer_script_maj_auto(),
        '{header}' => recuperer_gabarit('main/header.html',array()),
        '{footer}' => recuperer_gabarit('main/footer.html',array())
    ), true);

    $cache->end();

}

fermeture_connexion_db();

exit;
