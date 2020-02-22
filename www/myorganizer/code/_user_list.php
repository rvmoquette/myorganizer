<?php declare(strict_types=1);

    // Actualisation des droits
    Hook_user::hook_actualiser_les_droits_ajouter();

    $table_user = new user();

    // liste
        $liste = $db->user()->mf_lister_contexte(null, [OPTION_LIMIT => [0, NB_ELEM_MAX_TABLEAU]]);
        $tab = new Tableau($liste, "");
        $tab->desactiver_pagination();
        $tab->ajouter_ref_Colonne_Code("Code_user");
        $tab->set_ligne_selectionnee('Code_user', mf_Code_user());
        $tab->modifier_code_action("apercu_user");
        $tab->ajouter_colonne('user_Login', false, '');
        $tab->ajouter_colonne('user_Email', false, '');
        $trans['{tableau_user}'] = (count($liste) < NB_ELEM_MAX_TABLEAU ? '' : get_code_alert_warning("Attention, affichage partielle des données (soit " . NB_ELEM_MAX_TABLEAU . " enregistrements)")) . $tab->generer_code();

    // boutons
        if ($mf_droits_defaut['user__AJOUTER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_AJOUTER_PREC . get_nom_colonne('bouton_ajouter_user') . BOUTON_LIBELLE_AJOUTER_SUIV, get_nom_page_courante().'?act=ajouter_user', 'lien', 'bouton_ajouter_user');
        }
        $trans['{bouton_ajouter_user}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_ajouter_user', BOUTON_CLASSE_AJOUTER) : '';
