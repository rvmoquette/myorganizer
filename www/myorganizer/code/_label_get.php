<?php declare(strict_types=1);

/** @var array $label */

    // Actualisation des droits
    Hook_label::hook_actualiser_les_droits_modifier($label['Code_label']);
    Hook_label::hook_actualiser_les_droits_supprimer($label['Code_label']);

    // boutons
        if ($mf_droits_defaut['label__MODIFIER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_label') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_label&Code_label='.$Code_label, 'lien', 'bouton_modifier_label');
        }
        $trans['{bouton_modifier_label}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_label') : '';
        if ($mf_droits_defaut['label__SUPPRIMER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_SUPPRIMER_PREC . get_nom_colonne('bouton_supprimer_label') . BOUTON_LIBELLE_SUPPRIMER_SUIV, get_nom_page_courante().'?act=supprimer_label&Code_label='.$Code_label, 'lien', 'bouton_supprimer_label');
        }
        $trans['{bouton_supprimer_label}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_supprimer_label', BOUTON_CLASSE_SUPPRIMER) : '';

        // label_Name
        if ($mf_droits_defaut['api_modifier__label_Name']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_label_Name') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_label_Name&Code_label='.$Code_label, 'lien', 'bouton_modifier_label_Name');
        }
        $trans['{bouton_modifier_label_Name}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_label_Name') : '';

    /* prec_et_suiv */
    if ($db->label()->mf_compter() < 100) {
        $liste_label = $db->label()->mf_lister_contexte();
        // prec
        $prec_et_suiv = prec_suiv($liste_label, $label['Code_label']);
        $prec=['link'=>'', 'title'=>''];
        $suiv=['link'=>'', 'title'=>''];
        if (isset($prec_et_suiv['prec']['Code_label'])) {
            $prec['link'] = get_nom_page_courante().'?act=apercu_label&Code_label='.$prec_et_suiv['prec']['Code_label'];
            $prec['title'] = htmlspecialchars(get_titre_ligne_table('label', $prec_et_suiv['prec']));
        }
        // suiv
        if (isset($prec_et_suiv['suiv']['Code_label'])) {
            $suiv['link'] = get_nom_page_courante().'?act=apercu_label&Code_label='.$prec_et_suiv['suiv']['Code_label'];
            $suiv['title'] = htmlspecialchars(get_titre_ligne_table('label', $prec_et_suiv['suiv']));
        }
        $trans['{pager_label}'] = get_code_pager($prec, $suiv);
    } else {
        $trans['{pager_label}'] = '';
    }

    /* label_Name */
        if ($mf_droits_defaut['api_modifier__label_Name']) {
            $trans['{label_Name}'] = ajouter_champ_modifiable_interface(['liste_valeurs_cle_table' => ['Code_label' => $label['Code_label']], 'DB_name' => 'label_Name', 'valeur_initiale' => $label['label_Name']]);
        } else {
            $trans['{label_Name}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_label' => $label['Code_label']], 'DB_name' => 'label_Name', 'valeur_initiale' => $label['label_Name']]);
        }

