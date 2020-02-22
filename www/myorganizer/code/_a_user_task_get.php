<?php declare(strict_types=1);

/** @var array $a_user_task */

    // Actualisation des droits
    Hook_a_user_task::hook_actualiser_les_droits_modifier($a_user_task['Code_user'], $a_user_task['Code_task']);
    Hook_a_user_task::hook_actualiser_les_droits_supprimer($a_user_task['Code_user'], $a_user_task['Code_task']);

    // boutons
        if ($mf_droits_defaut['a_user_task__MODIFIER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_a_user_task') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_a_user_task&Code_user='.$Code_user.'&Code_task='.$Code_task.'', 'lien', 'bouton_modifier_a_user_task');
        }
        $trans['{bouton_modifier_a_user_task}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_a_user_task') : '';
        if ($mf_droits_defaut['a_user_task__SUPPRIMER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_SUPPRIMER_PREC . get_nom_colonne('bouton_supprimer_a_user_task') . BOUTON_LIBELLE_SUPPRIMER_SUIV, get_nom_page_courante().'?act=supprimer_a_user_task&Code_user='.$Code_user.'&Code_task='.$Code_task.'', 'lien', 'bouton_supprimer_a_user_task');
        }
        $trans['{bouton_supprimer_a_user_task}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_supprimer_a_user_task', BOUTON_CLASSE_SUPPRIMER) : '';

        // a_user_task_Link
        if ($mf_droits_defaut['api_modifier__a_user_task_Link']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_a_user_task_Link') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_a_user_task_Link&Code_user='.$Code_user.'&Code_task='.$Code_task.'', 'lien', 'bouton_modifier_a_user_task_Link');
        }
        $trans['{bouton_modifier_a_user_task_Link}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_a_user_task_Link') : '';

    /* prec_et_suiv */
    if ($db->a_user_task()->mf_compter((isset($est_charge['user']) ? $mf_contexte['Code_user'] : 0), (isset($est_charge['task']) ? $mf_contexte['Code_task'] : 0)) < 100) {
        $liste_a_user_task = $db->a_user_task()->mf_lister_contexte();
        // prec
        $prec_et_suiv = prec_suiv($liste_a_user_task, $a_user_task['Code_user'].'-'.$a_user_task['Code_task']);
        $prec=['link'=>'', 'title'=>''];
        $suiv=['link'=>'', 'title'=>''];
        if (isset($prec_et_suiv['prec']['Code_user'])) {
            $prec['link'] = get_nom_page_courante().'?act=apercu_a_user_task&Code_user='.$prec_et_suiv['prec']['Code_user'].'&Code_task='.$prec_et_suiv['prec']['Code_task'].'';
            $prec['title'] = htmlspecialchars(get_titre_ligne_table('a_user_task', $prec_et_suiv['prec']));
        }
        // suiv
        if (isset($prec_et_suiv['suiv']['Code_user'])) {
            $suiv['link'] = get_nom_page_courante().'?act=apercu_a_user_task&Code_user='.$prec_et_suiv['suiv']['Code_user'].'&Code_task='.$prec_et_suiv['suiv']['Code_task'].'';
            $suiv['title'] = htmlspecialchars(get_titre_ligne_table('a_user_task', $prec_et_suiv['suiv']));
        }
        $trans['{pager_a_user_task}'] = get_code_pager($prec, $suiv);
    } else {
        $trans['{pager_a_user_task}'] = '';
    }

    /* Code_user */
        $trans['{Code_user}'] = get_valeur_html_maj_auto_interface([ 'liste_valeurs_cle_table' => ['Code_user'=>$a_user_task['Code_user'], 'Code_task'=>$a_user_task['Code_task']], 'DB_name' => 'Code_user' , 'valeur_initiale' => $a_user_task['Code_user'] ]);

    /* Code_task */
        $trans['{Code_task}'] = get_valeur_html_maj_auto_interface([ 'liste_valeurs_cle_table' => ['Code_user'=>$a_user_task['Code_user'], 'Code_task'=>$a_user_task['Code_task']], 'DB_name' => 'Code_task' , 'valeur_initiale' => $a_user_task['Code_task'] ]);

    /* a_user_task_Link */
        if ($mf_droits_defaut['api_modifier__a_user_task_Link']) {
            $trans['{a_user_task_Link}'] = ajouter_champ_modifiable_interface([ 'liste_valeurs_cle_table' => ['Code_user'=>$a_user_task['Code_user'], 'Code_task'=>$a_user_task['Code_task']] , 'DB_name' => 'a_user_task_Link' , 'valeur_initiale' => $a_user_task['a_user_task_Link'] , 'class' => 'button' ]);
        } else {
            $trans['{a_user_task_Link}'] = get_valeur_html_maj_auto_interface([ 'liste_valeurs_cle_table' => ['Code_user'=>$a_user_task['Code_user'], 'Code_task'=>$a_user_task['Code_task']] , 'DB_name' => 'a_user_task_Link' , 'valeur_initiale' => $a_user_task['a_user_task_Link'] ]);
        }

