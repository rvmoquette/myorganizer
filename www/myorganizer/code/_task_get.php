<?php declare(strict_types=1);

/** @var array $task */

    // Actualisation des droits
    Hook_task::hook_actualiser_les_droits_modifier($task['Code_task']);
    Hook_task::hook_actualiser_les_droits_supprimer($task['Code_task']);

    // boutons
        if ($mf_droits_defaut['task__MODIFIER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_task') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_task&Code_task='.$Code_task, 'lien', 'bouton_modifier_task');
        }
        $trans['{bouton_modifier_task}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_task') : '';
        if ($mf_droits_defaut['task__SUPPRIMER'])
        {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_SUPPRIMER_PREC . get_nom_colonne('bouton_supprimer_task') . BOUTON_LIBELLE_SUPPRIMER_SUIV, get_nom_page_courante().'?act=supprimer_task&Code_task='.$Code_task, 'lien', 'bouton_supprimer_task');
        }
        $trans['{bouton_supprimer_task}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_supprimer_task', BOUTON_CLASSE_SUPPRIMER) : '';

        // task_Name
        if ($mf_droits_defaut['api_modifier__task_Name']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_task_Name') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_task_Name&Code_task='.$Code_task, 'lien', 'bouton_modifier_task_Name');
        }
        $trans['{bouton_modifier_task_Name}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_task_Name') : '';

        // task_Date_creation
        if ($mf_droits_defaut['api_modifier__task_Date_creation']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_task_Date_creation') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_task_Date_creation&Code_task='.$Code_task, 'lien', 'bouton_modifier_task_Date_creation');
        }
        $trans['{bouton_modifier_task_Date_creation}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_task_Date_creation') : '';

        // task_Description
        if ($mf_droits_defaut['api_modifier__task_Description']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_task_Description') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_task_Description&Code_task='.$Code_task, 'lien', 'bouton_modifier_task_Description');
        }
        $trans['{bouton_modifier_task_Description}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_task_Description') : '';

        // task_Workflow
        if ($mf_droits_defaut['api_modifier__task_Workflow']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_task_Workflow') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_task_Workflow&Code_task='.$Code_task, 'lien', 'bouton_modifier_task_Workflow');
        }
        $trans['{bouton_modifier_task_Workflow}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_task_Workflow') : '';

        // Code_user
        if ($mf_droits_defaut['api_modifier_ref__task__Code_user']) {
            $menu_a_droite->ajouter_bouton( BOUTON_LIBELLE_MODIFIER_PREC . get_nom_colonne('bouton_modifier_task__Code_user') . BOUTON_LIBELLE_MODIFIER_SUIV, get_nom_page_courante().'?act=modifier_task__Code_user&Code_task='.$Code_task, 'lien', 'bouton_modifier_task__Code_user');
        }
        $trans['{bouton_modifier_task__Code_user}'] = BOUTON_INTEGRABLE ? $menu_a_droite->generer_code_bouton('bouton_modifier_task__Code_user') : '';

    /* prec_et_suiv */
    if ($db->task()->mf_compter((isset($est_charge['user']) ? $mf_contexte['Code_user'] : 0)) < 100) {
        $liste_task = $db->task()->mf_lister_contexte();
        // prec
        $prec_et_suiv = prec_suiv($liste_task, $task['Code_task']);
        $prec=['link'=>'', 'title'=>''];
        $suiv=['link'=>'', 'title'=>''];
        if (isset($prec_et_suiv['prec']['Code_task'])) {
            $prec['link'] = get_nom_page_courante().'?act=apercu_task&Code_task='.$prec_et_suiv['prec']['Code_task'];
            $prec['title'] = htmlspecialchars(get_titre_ligne_table('task', $prec_et_suiv['prec']));
        }
        // suiv
        if (isset($prec_et_suiv['suiv']['Code_task'])) {
            $suiv['link'] = get_nom_page_courante().'?act=apercu_task&Code_task='.$prec_et_suiv['suiv']['Code_task'];
            $suiv['title'] = htmlspecialchars(get_titre_ligne_table('task', $prec_et_suiv['suiv']));
        }
        $trans['{pager_task}'] = get_code_pager($prec, $suiv);
    } else {
        $trans['{pager_task}'] = '';
    }

    /* task_Name */
        if ($mf_droits_defaut['api_modifier__task_Name']) {
            $trans['{task_Name}'] = ajouter_champ_modifiable_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Name', 'valeur_initiale' => $task['task_Name']]);
        } else {
            $trans['{task_Name}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Name', 'valeur_initiale' => $task['task_Name']]);
        }

    /* task_Date_creation */
        if ($mf_droits_defaut['api_modifier__task_Date_creation']) {
            $trans['{task_Date_creation}'] = ajouter_champ_modifiable_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Date_creation', 'valeur_initiale' => $task['task_Date_creation']]);
        } else {
            $trans['{task_Date_creation}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Date_creation', 'valeur_initiale' => $task['task_Date_creation']]);
        }

    /* task_Description */
        if ($mf_droits_defaut['api_modifier__task_Description']) {
            $trans['{task_Description}'] = ajouter_champ_modifiable_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Description', 'valeur_initiale' => $task['task_Description']]);
        } else {
            $trans['{task_Description}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Description', 'valeur_initiale' => $task['task_Description'], 'class' => 'text' ]);
        }

    /* task_Workflow */
        if ($mf_droits_defaut['api_modifier__task_Workflow']) {
            // en fonction des possibilités, liste choix possibles
            $liste = liste_union_A_et_B([$task['task_Workflow']], Hook_task::workflow__task_Workflow($task['task_Workflow']));
            foreach ($lang_standard['task_Workflow_'] as $key => $value) {
                if (! in_array($key, $liste) && $key != $task['task_Workflow']) {
                    unset($lang_standard['task_Workflow_'][$key]);
                }
            }
            // champ modifiable
            $trans['{task_Workflow}'] = ajouter_champ_modifiable_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Workflow', 'valeur_initiale' => $task['task_Workflow']]);
        } else {
            $trans['{task_Workflow}'] = get_valeur_html_maj_auto_interface(['liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'task_Workflow', 'valeur_initiale' => $task['task_Workflow']]);
        }

    /* Code_user */
        if ($mf_droits_defaut['api_modifier_ref__task__Code_user']) {
            $trans['{Code_user}'] = ajouter_champ_modifiable_interface([ 'liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'Code_user' , 'valeur_initiale' => $task['Code_user'] , 'nom_table' => 'task' ]);
        } else {
            $trans['{Code_user}'] = get_valeur_html_maj_auto_interface([ 'liste_valeurs_cle_table' => ['Code_task' => $task['Code_task']], 'DB_name' => 'Code_user' , 'valeur_initiale' => $task['Code_user'] , 'nom_table' => 'task' ]);
        }

