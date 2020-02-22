<?php declare(strict_types=1);

    $est_charge['task'] = 1;

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

/*
    +-----------+
    |  Ajouter  |
    +-----------+
*/
    if ($mf_action == 'ajouter_task' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_add = [];
        if ( isset( $_POST['task_Name'] ) ) { $mf_add['task_Name'] = $_POST['task_Name']; }
        if ( isset( $_POST['task_Date_creation'] ) ) { $mf_add['task_Date_creation'] = $_POST['task_Date_creation']; }
        if ( isset( $_POST['task_Description'] ) ) { $mf_add['task_Description'] = $_POST['task_Description']; }
        if ( isset( $_POST['task_Workflow'] ) ) { $mf_add['task_Workflow'] = $_POST['task_Workflow']; }
        $mf_add['Code_user'] = ( isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : $Code_user );
        $retour = $table_task->mf_ajouter_2($mf_add);
        if ($retour['code_erreur'] == 0) {
            $mf_action = "apercu_task";
            $Code_task = $retour['Code_task'];
            $mf_contexte['Code_task'] = $retour['Code_task'];
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

/*
    +---------+
    |  Creer  |
    +---------+
*/
    if ($mf_action == 'creer_task') {
        $retour = $table_task->mf_creer($Code_user);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_task';
            $Code_task =  $retour['Code_task'];
            $mf_contexte['Code_task'] = $retour['Code_task'];
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
    if ($mf_action == 'modifier_task' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_update = [];
        if (isset($_POST['task_Name'])) { $mf_update['task_Name'] = $_POST['task_Name']; }
        if (isset($_POST['task_Date_creation'])) { $mf_update['task_Date_creation'] = $_POST['task_Date_creation']; }
        if (isset($_POST['task_Description'])) { $mf_update['task_Description'] = $_POST['task_Description']; }
        if (isset($_POST['task_Workflow'])) { $mf_update['task_Workflow'] = $_POST['task_Workflow']; }
        if (isset($_POST['Code_user'])) { $mf_update['Code_user'] = (int) $_POST['Code_user']; }
        $retour = $table_task->mf_modifier_2( [ $Code_task => $mf_update ] );
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_task';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_task_Name' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $task_Name = $_POST['task_Name'];
        $retour = $table_task->mf_modifier_2([$Code_task => ['task_Name' => $task_Name]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_task';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_task_Date_creation' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $task_Date_creation = $_POST['task_Date_creation'];
        $retour = $table_task->mf_modifier_2([$Code_task => ['task_Date_creation' => $task_Date_creation]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_task';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_task_Description' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $task_Description = $_POST['task_Description'];
        $retour = $table_task->mf_modifier_2([$Code_task => ['task_Description' => $task_Description]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_task';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_task_Workflow' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $task_Workflow = $_POST['task_Workflow'];
        $retour = $table_task->mf_modifier_2([$Code_task => ['task_Workflow' => $task_Workflow]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_task';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_task__Code_user' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $retour = $table_task->mf_modifier_2([$Code_task => ['Code_user' => (isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : $Code_user)]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_task';
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
    if ($mf_action == "supprimer_task" && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $Supprimer = intval($_POST["Supprimer"]);
        if ($Supprimer == 1) {
            $retour = $table_task->mf_supprimer($Code_task);
            if ($retour['code_erreur'] == 0) {
                $mf_action = "-";
                $cache->clear();
                $Code_task = 0;
            } else {
                $cache->clear_current_page();
            }
        } else {
            $mf_action = "apercu_task";
            $cache->clear_current_page();
        }
    }
