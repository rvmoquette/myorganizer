<?php declare(strict_types=1);

    // Actualisation des droits
    Hook_a_user_task::hook_actualiser_les_droits_ajouter(mf_Code_user(), mf_Code_task());
    Hook_a_user_task::hook_actualiser_les_droits_modifier(mf_Code_user(), mf_Code_task());
    Hook_a_user_task::hook_actualiser_les_droits_supprimer(mf_Code_user(), mf_Code_task());

    $table_a_user_task = new a_user_task();

    // liste
        $liste = $db->a_user_task()->mf_lister_contexte([OPTION_LIMIT => [0, NB_ELEM_MAX_TABLEAU]]);
        $tab = new Tableau($liste, '');
        $tab->desactiver_pagination();
        if (! isset($est_charge['user'])) {
            $tab->ajouter_colonne('Code_user', true, '');
        }
        $tab->ajouter_ref_Colonne_Code('Code_user');
        if (! isset($est_charge['task'])) {
            $tab->ajouter_colonne('Code_task', true, '');
        }
        $tab->ajouter_ref_Colonne_Code('Code_task');
        $tab->modifier_code_action('apercu_a_user_task');
        $tab->ajouter_colonne('a_user_task_Link', true, '');
        if ($mf_droits_defaut['a_user_task__SUPPRIMER']) {
            $tab->ajouter_colonne_bouton('supprimer_a_user_task', BOUTON_LIBELLE_SUPPRIMER_PREC . get_nom_colonne('bouton_supprimer_a_user_task') . BOUTON_LIBELLE_SUPPRIMER_SUIV );
        }
        $trans['{tableau_a_user_task}'] = (count($liste) < NB_ELEM_MAX_TABLEAU ? '' : get_code_alert_warning("Attention, affichage partielle des données (soit " . NB_ELEM_MAX_TABLEAU . " enregistrements)")) . $tab->generer_code();

    // boutons
        if ($mf_droits_defaut['a_user_task__AJOUTER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_AJOUTER_PREC . get_nom_colonne('bouton_ajouter_a_user_task') . BOUTON_LIBELLE_AJOUTER_SUIV , get_nom_page_courante().'?act=ajouter_a_user_task&Code_user='.$Code_user.'&Code_task='.$Code_task.'', 'lien', 'bouton_ajouter_a_user_task');
        }
        $trans['{bouton_ajouter_a_user_task}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_ajouter_a_user_task', BOUTON_CLASSE_AJOUTER) : '';
