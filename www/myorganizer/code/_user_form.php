<?php declare(strict_types=1);

/** @var string $mf_action */
/** @var string $mess */

    if ($mf_action == 'apercu_user' || $mf_action <> '' && mf_Code_user() != 0) {

        $user = $table_user->mf_get(mf_Code_user(), ['autocompletion' => true]);

        if (isset($user['Code_user'])) {

            $fil_ariane->ajouter_titre(get_titre_ligne_table('user', $user), get_nom_page_courante().'?act=apercu_user&Code_user=' . $user['Code_user']);

            $menu_a_droite->raz_boutons();

            if ( ! MULTI_BLOCS ) { $code_html = ''; }

            include __DIR__ . '/_user_get.php';

            $code_html .= recuperer_gabarit('main/section.html', [
                '{fonction}'  => 'apercu',
                '{nom_table}' => 'user',
                '{titre}'     => strip_tags(htmlspecialchars_decode(get_titre_ligne_table('user', $user)), ''),
                '{contenu}'   => recuperer_gabarit('user/bloc_apercu.html', $trans),
            ]);

        }

    } else {

        include __DIR__ . '/_user_list.php';

        $code_html .= recuperer_gabarit('main/section.html', [
            '{fonction}'  => 'lister',
            '{nom_table}' => 'user',
            '{titre}'     => htmlspecialchars(get_nom_colonne('libelle_liste_user')),
            '{contenu}'   => recuperer_gabarit('user/bloc_lister.html', $trans),
        ]);

    }

    if ($mf_action == "ajouter_user") {

        $form = new Formulaire('', $mess);
        $form->ajouter_input("user_Login", ( isset($_POST['user_Login']) ? $_POST['user_Login'] : $mf_initialisation['user_Login'] ), true);
        $form->ajouter_input("user_Password", ( isset($_POST['user_Password']) ? $_POST['user_Password'] : $mf_initialisation['user_Password'] ), true, 'password');
        $form->ajouter_input("user_Email", ( isset($_POST['user_Email']) ? $_POST['user_Email'] : $mf_initialisation['user_Email'] ), true);

        $code_html .= recuperer_gabarit('user/form_add_user.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_add_user')], false, true);

    } elseif ($mf_action == "modifier_user") {

        $user = $table_user->mf_get(mf_Code_user(), ['autocompletion' => true]);
        if (isset($user['Code_user'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("user_Login", ( isset($_POST['user_Login']) ? $_POST['user_Login'] : $user['user_Login'] ), true);
            // $form->ajouter_input("user_Password", "", true);
            $form->ajouter_input("user_Email", ( isset($_POST['user_Email']) ? $_POST['user_Email'] : $user['user_Email'] ), true);

            $code_html .= recuperer_gabarit('user/form_edit_user.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_user')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_user_Login') {

        $user = $table_user->mf_get(mf_Code_user(), ['autocompletion' => true]);
        if (isset($user['Code_user'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("user_Login", ( isset($_POST['user_Login']) ? $_POST['user_Login'] : $user['user_Login'] ), true);

            $code_html .= recuperer_gabarit('user/form_edit_user_Login.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_user_Login')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_user_Password') {

        $user = $table_user->mf_get(mf_Code_user(), ['autocompletion' => true]);
        if (isset($user['Code_user'])) {

            $form = new Formulaire('', $mess);
            // $form->ajouter_input("user_Password", "", true);

            $code_html .= recuperer_gabarit('user/form_edit_user_Password.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_user_Password')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_user_Email') {

        $user = $table_user->mf_get(mf_Code_user(), ['autocompletion' => true]);
        if (isset($user['Code_user'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("user_Email", ( isset($_POST['user_Email']) ? $_POST['user_Email'] : $user['user_Email'] ), true);

            $code_html .= recuperer_gabarit('user/form_edit_user_Email.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_user_Email')], false, true);

        }

    }
    elseif ($mf_action == 'modpwd') {

        $user = $table_user->mf_get(mf_Code_user());
        if (isset($user['Code_user'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("user_Password_old", "", true, "password");
            $form->ajouter_input("user_Password_new", "", true, "password");
            $form->ajouter_input("user_Password_verif", "", true, "password");

            $code_html .= recuperer_gabarit('user/new_password_user.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('formulaire_modpwd_user')], false, true);

        }

    }
    elseif ($mf_action == 'supprimer_user') {

        $user = $table_user->mf_get(mf_Code_user(), ['autocompletion' => true]);
        if (isset($user['Code_user'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_select([0, 1], 'Supprimer', FORM_SUPPR_DEFAUT, true);
            $form->activer_picto_suppression();

            $code_html .= recuperer_gabarit('user/form_delete_user.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_delete_user')], false, true);

        }

    }

