<?php declare(strict_types=1);

    $est_charge['user'] = 1;

/*
    +-----------+
    |  Ajouter  |
    +-----------+
*/
    if ($mf_action == 'ajouter_user' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_add = [];
        if ( isset( $_POST['user_Login'] ) ) { $mf_add['user_Login'] = $_POST['user_Login']; }
        if ( isset( $_POST['user_Password'] ) ) { $mf_add['user_Password'] = $_POST['user_Password']; }
        if ( isset( $_POST['user_Email'] ) ) { $mf_add['user_Email'] = $_POST['user_Email']; }
        $retour = $table_user->mf_ajouter_2($mf_add);
        if ($retour['code_erreur'] == 0) {
            $mf_action = "apercu_user";
            $Code_user = $retour['Code_user'];
            $mf_contexte['Code_user'] = $retour['Code_user'];
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
    if ($mf_action == 'modifier_user' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $mf_update = [];
        if (isset($_POST['user_Login'])) { $mf_update['user_Login'] = $_POST['user_Login']; }
        // if (isset($_POST['user_Password'])) { $mf_update['user_Password'] = $_POST['user_Password']; }
        if (isset($_POST['user_Email'])) { $mf_update['user_Email'] = $_POST['user_Email']; }
        $retour = $table_user->mf_modifier_2( [ $Code_user => $mf_update ] );
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_user';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_user_Login' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $user_Login = $_POST['user_Login'];
        $retour = $table_user->mf_modifier_2([$Code_user => ['user_Login' => $user_Login]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_user';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

    if ($mf_action == 'modifier_user_Email' && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $user_Email = $_POST['user_Email'];
        $retour = $table_user->mf_modifier_2([$Code_user => ['user_Email' => $user_Email]]);
        if ($retour['code_erreur'] == 0) {
            $mf_action = 'apercu_user';
            $cache->clear();
        } else {
            $cache->clear_current_page();
        }
    }

/*
    +----------------------------+
    |  Modifier le mot de passe  |
    +----------------------------+
*/
    if ($mf_action == "modpwd" && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $user_Password_old=$_POST["user_Password_old"];
        $user_Password_new=$_POST["user_Password_new"];
        $user_Password_verif=$_POST["user_Password_verif"];
        $retour = $mf_connexion->changer_mot_de_passe($Code_user, $user_Password_old, $user_Password_new, $user_Password_verif);
        if ($retour['code_erreur'] == 0) {
            $mf_action = "apercu_user";
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
    if ($mf_action == "supprimer_user" && isset($_POST['validation_formulaire']) && formulaire_valide($_POST['validation_formulaire'])) {
        $Supprimer = intval($_POST["Supprimer"]);
        if ($Supprimer == 1) {
            $retour = $table_user->mf_supprimer($Code_user);
            if ($retour['code_erreur'] == 0) {
                $mf_action = "-";
                $cache->clear();
                $Code_user = 0;
            } else {
                $cache->clear_current_page();
            }
        } else {
            $mf_action = "apercu_user";
            $cache->clear_current_page();
        }
    }
