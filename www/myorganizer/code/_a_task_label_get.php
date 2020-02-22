<?php declare(strict_types=1);

/** @var array $a_task_label */

    // Actualisation des droits
    Hook_a_task_label::hook_actualiser_les_droits_modifier($a_task_label['Code_task'], $a_task_label['Code_label']);
    Hook_a_task_label::hook_actualiser_les_droits_supprimer($a_task_label['Code_task'], $a_task_label['Code_label']);

    // boutons
        if ($mf_droits_defaut['a_task_label__MODIFIER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_a_task_label') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_a_task_label&Code_task='.$Code_task.'&Code_label='.$Code_label.'', 'lien', 'bouton_modifier_a_task_label');
        }
        $trans['{bouton_modifier_a_task_label}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_a_task_label') : '';
        if ($mf_droits_defaut['a_task_label__SUPPRIMER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_SUPPRIMER_PREC . get_nom_colonne('bouton_supprimer_a_task_label') . BOUTON_LIBELLE_SUPPRIMER_SUIV, get_nom_page_courante().'?act=supprimer_a_task_label&Code_task='.$Code_task.'&Code_label='.$Code_label.'', 'lien', 'bouton_supprimer_a_task_label');
        }
        $trans['{bouton_supprimer_a_task_label}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_supprimer_a_task_label', BOUTON_CLASSE_SUPPRIMER) : '';

        // a_task_label_Link
        if ($mf_droits_defaut['api_modifier__a_task_label_Link']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_a_task_label_Link') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_a_task_label_Link&Code_task='.$Code_task.'&Code_label='.$Code_label.'', 'lien', 'bouton_modifier_a_task_label_Link');
        }
        $trans['{bouton_modifier_a_task_label_Link}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_a_task_label_Link') : '';

    /* prec_et_suiv */
    if ($db->a_task_label()->mf_compter((isset($est_charge['task']) ? $mf_contexte['Code_task'] : 0), (isset($est_charge['label']) ? $mf_contexte['Code_label'] : 0)) < 100) {
        $liste_a_task_label = $db->a_task_label()->mf_lister_contexte();
        // prec
        $prec_et_suiv = prec_suiv($liste_a_task_label, $a_task_label['Code_task'].'-'.$a_task_label['Code_label']);
        $prec=['link'=>'', 'title'=>''];
        $suiv=['link'=>'', 'title'=>''];
        if (isset($prec_et_suiv['prec']['Code_task'])) {
            $prec['link'] = get_nom_page_courante().'?act=apercu_a_task_label&Code_task='.$prec_et_suiv['prec']['Code_task'].'&Code_label='.$prec_et_suiv['prec']['Code_label'].'';
            $prec['title'] = htmlspecialchars(get_titre_ligne_table('a_task_label', $prec_et_suiv['prec']));
        }
        // suiv
        if (isset($prec_et_suiv['suiv']['Code_task'])) {
            $suiv['link'] = get_nom_page_courante().'?act=apercu_a_task_label&Code_task='.$prec_et_suiv['suiv']['Code_task'].'&Code_label='.$prec_et_suiv['suiv']['Code_label'].'';
            $suiv['title'] = htmlspecialchars(get_titre_ligne_table('a_task_label', $prec_et_suiv['suiv']));
        }
        $trans['{pager_a_task_label}'] = get_code_pager($prec, $suiv);
    } else {
        $trans['{pager_a_task_label}'] = '';
    }

    /* Code_task */
        $trans['{Code_task}'] = get_valeur_html_maj_auto_interface([ 'liste_valeurs_cle_table' => ['Code_task'=>$a_task_label['Code_task'], 'Code_label'=>$a_task_label['Code_label']], 'DB_name' => 'Code_task' , 'valeur_initiale' => $a_task_label['Code_task'] ]);

    /* Code_label */
        $trans['{Code_label}'] = get_valeur_html_maj_auto_interface([ 'liste_valeurs_cle_table' => ['Code_task'=>$a_task_label['Code_task'], 'Code_label'=>$a_task_label['Code_label']], 'DB_name' => 'Code_label' , 'valeur_initiale' => $a_task_label['Code_label'] ]);

    /* a_task_label_Link */
        if ($mf_droits_defaut['api_modifier__a_task_label_Link']) {
            $trans['{a_task_label_Link}'] = ajouter_champ_modifiable_interface([ 'liste_valeurs_cle_table' => ['Code_task'=>$a_task_label['Code_task'], 'Code_label'=>$a_task_label['Code_label']] , 'DB_name' => 'a_task_label_Link' , 'valeur_initiale' => $a_task_label['a_task_label_Link'] , 'class' => 'button' ]);
        } else {
            $trans['{a_task_label_Link}'] = get_valeur_html_maj_auto_interface([ 'liste_valeurs_cle_table' => ['Code_task'=>$a_task_label['Code_task'], 'Code_label'=>$a_task_label['Code_label']] , 'DB_name' => 'a_task_label_Link' , 'valeur_initiale' => $a_task_label['a_task_label_Link'] ]);
        }

