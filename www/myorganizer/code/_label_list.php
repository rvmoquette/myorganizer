<?php declare(strict_types=1);

    // Actualisation des droits
    Hook_label::hook_actualiser_les_droits_ajouter();

    $table_label = new label();

    // liste
        $liste = $db->label()->mf_lister_contexte(null, [OPTION_LIMIT => [0, NB_ELEM_MAX_TABLEAU]]);
        $tab = new Tableau($liste, "");
        $tab->desactiver_pagination();
        $tab->ajouter_ref_Colonne_Code("Code_label");
        $tab->set_ligne_selectionnee('Code_label', mf_Code_label());
        $tab->modifier_code_action("apercu_label");
        $tab->ajouter_colonne('label_Name', false, '');
        $trans['{tableau_label}'] = (count($liste) < NB_ELEM_MAX_TABLEAU ? '' : get_code_alert_warning("Attention, affichage partielle des données (soit " . NB_ELEM_MAX_TABLEAU . " enregistrements)")) . $tab->generer_code();

    // boutons
        if ($mf_droits_defaut['label__AJOUTER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_AJOUTER_PREC . get_nom_colonne('bouton_ajouter_label') . BOUTON_LIBELLE_AJOUTER_SUIV, get_nom_page_courante().'?act=ajouter_label', 'lien', 'bouton_ajouter_label');
        }
        $trans['{bouton_ajouter_label}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_ajouter_label', BOUTON_CLASSE_AJOUTER) : '';
        if ($mf_droits_defaut['label__CREER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_AJOUTER_PREC . get_nom_colonne('bouton_creer_label') . BOUTON_LIBELLE_AJOUTER_SUIV, get_nom_page_courante().'?act=creer_label', 'lien', 'bouton_creer_label');
        }
        $trans['{bouton_creer_label}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_creer_label', BOUTON_CLASSE_AJOUTER) : '';
