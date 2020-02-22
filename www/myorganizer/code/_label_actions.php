<?php declare(strict_types=1);

    $est_charge['label'] = 1;

/*
    +-----------+
    |  Ajouter  |
    +-----------+
*/
    if ($mf_action == 'ajouter_label' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_add = [];
        if ( isset( $_POST['label_Name'] ) ) { $mf_add['label_Name'] = $_POST['label_Name']; }
        $retour = $table_label->mf_ajouter_2($mf_add);
        if ($retour['code_erreur'] == 0) {
            $mf_action = "apercu_label";
            $Code_label = $retour['Code_label'];
            $mf_contexte['Code_label'] = $retour['Code_label'];
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
    if ($mf_action == 'creer_label') {
        $retour = $table_label->mf_creer();
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_label';
            $Code_label =  $retour['Code_label'];
            $mf_contexte['Code_label'] = $retour['Code_label'];
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
    if ($mf_action == 'modifier_label' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_update = [];
        if (isset($_POST['label_Name'])) { $mf_update['label_Name'] = $_POST['label_Name']; }
        $retour = $table_label->mf_modifier_2( [ $Code_label => $mf_update ] );
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_label';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_label_Name' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $label_Name = $_POST['label_Name'];
        $retour = $table_label->mf_modifier_2([$Code_label => ['label_Name' => $label_Name]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_label';
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
    if ($mf_action == "supprimer_label" && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $Supprimer = intval($_POST["Supprimer"]);
        if ($Supprimer == 1) {
            $retour = $table_label->mf_supprimer($Code_label);
            if ($retour['code_erreur'] == 0) {
                $mf_action = "-";
                $cache->clear();
                $Code_label = 0;
            } else {
                $cache->clear_current_page();
            }
        } else {
            $mf_action = "apercu_label";
            $cache->clear_current_page();
        }
    }
