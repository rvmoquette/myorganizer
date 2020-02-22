<?php declare(strict_types=1);

/** @var array $user */

    // Actualisation des droits
    Hook_user::hook_actualiser_les_droits_modifier($user['Code_user']);
    Hook_user::hook_actualiser_les_droits_supprimer($user['Code_user']);

    // boutons
        if ($mf_droits_defaut['user__MODIFIER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_user') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_user&Code_user='.$Code_user, 'lien', 'bouton_modifier_user');
        }
        $trans['{bouton_modifier_user}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_user') : '';
        if ($mf_droits_defaut['user__MODIFIER_PWD'])
        {
            $menu_a_droite->ajouter_bouton(BOUTON_LIBELLE_MODIFIER_PWD_PREC . get_nom_colonne('bouton_modpwd_user') . BOUTON_LIBELLE_MODIFIER_PWD_SUIV, get_nom_page_courante().'?act=modpwd&Code_user='.$Code_user, 'lien', 'bouton_modpwd_user');
        }
        $trans['{bouton_modpwd_user}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modpwd_user') : '';
        if ($mf_droits_defaut['user__SUPPRIMER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_SUPPRIMER_PREC . get_nom_colonne('bouton_supprimer_user') . BOUTON_LIBELLE_SUPPRIMER_SUIV, get_nom_page_courante().'?act=supprimer_user&Code_user='.$Code_user, 'lien', 'bouton_supprimer_user');
        }
        $trans['{bouton_supprimer_user}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_supprimer_user', BOUTON_CLASSE_SUPPRIMER) : '';

        // user_Login
        if ($mf_droits_defaut['api_modifier__user_Login']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_user_Login') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_user_Login&Code_user='.$Code_user, 'lien', 'bouton_modifier_user_Login');
        }
        $trans['{bouton_modifier_user_Login}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_user_Login') : '';

        // user_Password
        if ($mf_droits_defaut['api_modifier__user_Password']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_user_Password') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_user_Password&Code_user='.$Code_user, 'lien', 'bouton_modifier_user_Password');
        }
        $trans['{bouton_modifier_user_Password}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_user_Password') : '';

        // user_Email
        if ($mf_droits_defaut['api_modifier__user_Email']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_user_Email') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_user_Email&Code_user='.$Code_user, 'lien', 'bouton_modifier_user_Email');
        }
        $trans['{bouton_modifier_user_Email}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_user_Email') : '';

    /* prec_et_suiv */
    if ($db->user()->mf_compter() < 100) {
        $liste_user = $db->user()->mf_lister_contexte();
        // prec
        $prec_et_suiv = prec_suiv($liste_user, $user['Code_user']);
        $prec=['link'=>'', 'title'=>''];
        $suiv=['link'=>'', 'title'=>''];
        if (isset($prec_et_suiv['prec']['Code_user'])) {
            $prec['link'] = get_nom_page_courante().'?act=apercu_user&Code_user='.$prec_et_suiv['prec']['Code_user'];
            $prec['title'] = htmlspecialchars(get_titre_ligne_table('user', $prec_et_suiv['prec']));
        }
        // suiv
        if (isset($prec_et_suiv['suiv']['Code_user'])) {
            $suiv['link'] = get_nom_page_courante().'?act=apercu_user&Code_user='.$prec_et_suiv['suiv']['Code_user'];
            $suiv['title'] = htmlspecialchars(get_titre_ligne_table('user', $prec_et_suiv['suiv']));
        }
        $trans['{pager_user}'] = get_code_pager($prec, $suiv);
    } else {
        $trans['{pager_user}'] = '';
    }

    /* user_Login */
        if ($mf_droits_defaut['api_modifier__user_Login']) {
            $trans['{user_Login}'] = ajouter_champ_modifiable_interface(['liste_valeurs_cle_table' => ['Code_user' => $user['Code_user']], 'DB_name' => 'user_Login', 'valeur_initiale' => $user['user_Login']]);
        } else {
            $trans['{user_Login}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_user' => $user['Code_user']], 'DB_name' => 'user_Login', 'valeur_initiale' => $user['user_Login']]);
        }

    /* user_Password */
        $trans['{user_Password}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_user' => $user['Code_user']], 'DB_name' => 'user_Password', 'valeur_initiale' => $trans['{bouton_modpwd_user}'], 'class' => 'html', 'maj_auto' => false ]);

    /* user_Email */
        if ($mf_droits_defaut['api_modifier__user_Email']) {
            $trans['{user_Email}'] = ajouter_champ_modifiable_interface(['liste_valeurs_cle_table' => ['Code_user' => $user['Code_user']], 'DB_name' => 'user_Email', 'valeur_initiale' => $user['user_Email']]);
        } else {
            $trans['{user_Email}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_user' => $user['Code_user']], 'DB_name' => 'user_Email', 'valeur_initiale' => $user['user_Email']]);
        }

