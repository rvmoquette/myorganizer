<?php declare(strict_types=1);

    $est_charge['a_user_task'] = 1;

    if (! isset($lang_standard['Code_user_'])) {
        $table_user = new user();
        $liste = $db->user()->mf_lister_contexte(null, ['liste_colonnes_a_selectionner' => mf_liste_colonnes_titre('user'), OPTION_LIMIT => [0, NB_ELEM_MAX_LANGUE]]);
        if (count($liste) < NB_ELEM_MAX_LANGUE) {
            $lang_standard['Code_user_'] = [];
            foreach ($liste as $code => $value) {
                $lang_standard['Code_user_'][$code] = get_titre_ligne_table('user', $value);
            }
        }
        unset($liste);
    }
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

/*
    +-----------+
    |  Ajouter  |
    +-----------+
*/
    if ( $mf_action=='ajouter_a_user_task' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire']) )
    {
        $mf_add = [];
        $mf_add['Code_user'] = (isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : $Code_user );
        $mf_add['Code_task'] = (isset($_POST['Code_task']) ? (int) $_POST['Code_task'] : $Code_task );
        if ( isset( $_POST['a_user_task_Link'] ) ) { $mf_add['a_user_task_Link'] = $_POST['a_user_task_Link']; }
        $retour = $table_a_user_task->mf_ajouter_2( $mf_add );
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_a_user_task';
            if (! isset($est_charge['user'])) {
                $Code_user = (isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : 0);
            }
            if (! isset($est_charge['task'])) {
                $Code_task = (isset($_POST['Code_task']) ? (int) $_POST['Code_task'] : 0);
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
    if ($mf_action == 'modifier_a_user_task' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_update = [];
        $mf_update['Code_user'] = $Code_user;
        $mf_update['Code_task'] = $Code_task;
        if (isset($_POST['a_user_task_Link'])) { $mf_update['a_user_task_Link'] = $_POST['a_user_task_Link']; }
        $retour = $table_a_user_task->mf_modifier_2($mf_update);
        if ($retour['code_erreur'] == 0) {
            $mf_action = "apercu_a_user_task";
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_a_user_task_Link' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $a_user_task_Link = $_POST['a_user_task_Link'];
        $retour = $table_a_user_task -> mf_modifier_2([['Code_user' => $Code_user , 'Code_task' => $Code_task , 'a_user_task_Link' => $a_user_task_Link]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_a_user_task';
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
    if ($mf_action == 'supprimer_a_user_task' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $Supprimer = intval($_POST["Supprimer"]);
        if ($Supprimer == 1) {
            $retour = $table_a_user_task->mf_supprimer($Code_user, $Code_task);
            if ($retour['code_erreur'] == 0) {
                $mf_action = "-";
                $cache->clear();
            } else {
                $cache->clear_current_page();
            }
        } else {
            $mf_action = "apercu_a_user_task";
            $cache->clear_current_page();
        }
    }
