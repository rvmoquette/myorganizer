<?php declare(strict_types=1);

    $est_charge['a_task_label'] = 1;

    if (! isset($lang_standard['Code_task_'])) {
        $table_task = new task();
        $liste = $db->task()->mf_lister_contexte(null, ['liste_colonnes_a_selectionner' => mf_liste_colonnes_titre('task'), OPTION_LIMIT => [0, NB_ELEM_MAX_LANGUE]]);
        if (count($liste) < NB_ELEM_MAX_LANGUE) {
            $lang_standard['Code_task_'] = [];
            foreach ($liste as $code => $value) {
                $lang_standard['Code_task_'][$code] = get_titre_ligne_table('task', $value);
            }
        }
        unset($liste);
    }
    if (! isset($lang_standard['Code_label_'])) {
        $table_label = new label();
        $liste = $db->label()->mf_lister_contexte(null, ['liste_colonnes_a_selectionner' => mf_liste_colonnes_titre('label'), OPTION_LIMIT => [0, NB_ELEM_MAX_LANGUE]]);
        if (count($liste) < NB_ELEM_MAX_LANGUE) {
            $lang_standard['Code_label_'] = [];
            foreach ($liste as $code => $value) {
                $lang_standard['Code_label_'][$code] = get_titre_ligne_table('label', $value);
            }
        }
        unset($liste);
    }

/*
    +-----------+
    |  Ajouter  |
    +-----------+
*/
    if ( $mf_action=='ajouter_a_task_label' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire']) )
    {
        $mf_add = [];
        $mf_add['Code_task'] = (isset($_POST['Code_task']) ? (int) $_POST['Code_task'] : $Code_task );
        $mf_add['Code_label'] = (isset($_POST['Code_label']) ? (int) $_POST['Code_label'] : $Code_label );
        if ( isset( $_POST['a_task_label_Link'] ) ) { $mf_add['a_task_label_Link'] = $_POST['a_task_label_Link']; }
        $retour = $table_a_task_label->mf_ajouter_2( $mf_add );
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_a_task_label';
            if (! isset($est_charge['task'])) {
                $Code_task = (isset($_POST['Code_task']) ? (int) $_POST['Code_task'] : 0);
            }
            if (! isset($est_charge['label'])) {
                $Code_label = (isset($_POST['Code_label']) ? (int) $_POST['Code_label'] : 0);
            }
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

/*
    +------------+
    |  Modifier  |
    +------------+
*/
    if ($mf_action == 'modifier_a_task_label' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_update = [];
        $mf_update['Code_task'] = $Code_task;
        $mf_update['Code_label'] = $Code_label;
        if (isset($_POST['a_task_label_Link'])) { $mf_update['a_task_label_Link'] = $_POST['a_task_label_Link']; }
        $retour = $table_a_task_label->mf_modifier_2($mf_update);
        if ($retour['code_erreur'] == 0) {
            $mf_action = "apercu_a_task_label";
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_a_task_label_Link' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $a_task_label_Link = $_POST['a_task_label_Link'];
        $retour = $table_a_task_label -> mf_modifier_2([['Code_task' => $Code_task , 'Code_label' => $Code_label , 'a_task_label_Link' => $a_task_label_Link]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_a_task_label';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

/*
    +-------------+
    |  Supprimer  |
    +-------------+
*/
    if ($mf_action == 'supprimer_a_task_label' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $Supprimer = intval($_POST["Supprimer"]);
        if ($Supprimer == 1) {
            $retour = $table_a_task_label->mf_supprimer($Code_task, $Code_label);
            if ($retour['code_erreur'] == 0) {
                $mf_action = "-";
                $cache->clear();
            } else {
                $cache->clear_current_page();
            }
        } else {
            $mf_action = "apercu_a_task_label";
            $cache->clear_current_page();
        }
    }
