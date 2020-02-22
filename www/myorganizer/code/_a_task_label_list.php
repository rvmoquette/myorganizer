<?php declare(strict_types=1);

    // Actualisation des droits
    Hook_a_task_label::hook_actualiser_les_droits_ajouter(mf_Code_task(), mf_Code_label());
    Hook_a_task_label::hook_actualiser_les_droits_modifier(mf_Code_task(), mf_Code_label());
    Hook_a_task_label::hook_actualiser_les_droits_supprimer(mf_Code_task(), mf_Code_label());

    $table_a_task_label = new a_task_label();

    // liste
        $liste = $db->a_task_label()->mf_lister_contexte([OPTION_LIMIT => [0, NB_ELEM_MAX_TABLEAU]]);
        $tab = new Tableau($liste, '');
        $tab->desactiver_pagination();
        if (! isset($est_charge['task'])) {
            $tab->ajouter_colonne('Code_task', true, '');
        }
        $tab->ajouter_ref_Colonne_Code('Code_task');
        if (! isset($est_charge['label'])) {
            $tab->ajouter_colonne('Code_label', true, '');
        }
        $tab->ajouter_ref_Colonne_Code('Code_label');
        $tab->modifier_code_action('apercu_a_task_label');
        $tab->ajouter_colonne('a_task_label_Link', true, '');
        if ($mf_droits_defaut['a_task_label__SUPPRIMER']) {
            $tab->ajouter_colonne_bouton('supprimer_a_task_label', BOUTON_LIBELLE_SUPPRIMER_PREC . get_nom_colonne('bouton_supprimer_a_task_label') . BOUTON_LIBELLE_SUPPRIMER_SUIV );
        }
        $trans['{tableau_a_task_label}'] = (count($liste) < NB_ELEM_MAX_TABLEAU ? '' : get_code_alert_warning("Attention, affichage partielle des données (soit " . NB_ELEM_MAX_TABLEAU . " enregistrements)")) . $tab->generer_code();

    // boutons
        if ($mf_droits_defaut['a_task_label__AJOUTER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_AJOUTER_PREC . get_nom_colonne('bouton_ajouter_a_task_label') . BOUTON_LIBELLE_AJOUTER_SUIV , get_nom_page_courante().'?act=ajouter_a_task_label&Code_task='.$Code_task.'&Code_label='.$Code_label.'', 'lien', 'bouton_ajouter_a_task_label');
        }
        $trans['{bouton_ajouter_a_task_label}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_ajouter_a_task_label', BOUTON_CLASSE_AJOUTER) : '';
