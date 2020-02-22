<?php declare(strict_types=1);

    // Actualisation des droits
    Hook_task::hook_actualiser_les_droits_ajouter(mf_Code_user());

    $table_task = new task();

    // liste
        $liste = $db->task()->mf_lister_contexte(null, [OPTION_LIMIT => [0, NB_ELEM_MAX_TABLEAU]]);
        $tab = new Tableau($liste, "");
        $tab->desactiver_pagination();
        $tab->ajouter_ref_Colonne_Code("Code_task");
        $tab->set_ligne_selectionnee('Code_task', mf_Code_task());
        $tab->modifier_code_action("apercu_task");
        $tab->ajouter_colonne('task_Name', false, '');
        $tab->ajouter_colonne('task_Date_creation', false, 'date');
        // $tab->ajouter_colonne('task_Description', false, '');
        $tab->ajouter_colonne('task_Workflow', true, '');
        if (! isset($est_charge['user'])) {
            $tab->ajouter_colonne('Code_user', true, '');
        }
        $trans['{tableau_task}'] = (count($liste) < NB_ELEM_MAX_TABLEAU ? '' : get_code_alert_warning("Attention, affichage partielle des données (soit " . NB_ELEM_MAX_TABLEAU . " enregistrements)")) . $tab->generer_code();

    // boutons
        if ($mf_droits_defaut['task__AJOUTER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_AJOUTER_PREC . get_nom_colonne('bouton_ajouter_task') . BOUTON_LIBELLE_AJOUTER_SUIV, get_nom_page_courante().'?act=ajouter_task&Code_user='.$Code_user.'', 'lien', 'bouton_ajouter_task');
        }
        $trans['{bouton_ajouter_task}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_ajouter_task', BOUTON_CLASSE_AJOUTER) : '';
        if ($mf_droits_defaut['task__CREER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_AJOUTER_PREC . get_nom_colonne('bouton_creer_task') . BOUTON_LIBELLE_AJOUTER_SUIV, get_nom_page_courante().'?act=creer_task&Code_user='.$Code_user.'', 'lien', 'bouton_creer_task');
        }
        $trans['{bouton_creer_task}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_creer_task', BOUTON_CLASSE_AJOUTER) : '';
