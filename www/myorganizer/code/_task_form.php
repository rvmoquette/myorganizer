<?php declare(strict_types=1);

/** @var string $mf_action */
/** @var string $mess */

    if ($mf_action == 'apercu_task' || $mf_action <> '' && mf_Code_task() != 0) {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);

        if (isset($task['Code_task'])) {

            $fil_ariane->ajouter_titre(get_titre_ligne_table('task', $task), get_nom_page_courante().'?act=apercu_task&Code_task=' . $task['Code_task']);

            $menu_a_droite->raz_boutons();

            if ( ! MULTI_BLOCS ) { $code_html = ''; }

            include __DIR__ . '/_task_get.php';
            include __DIR__ . '/_a_user_task_list.php';

            $code_html .= recuperer_gabarit('main/section.html', [
                '{fonction}'  => 'apercu',
                '{nom_table}' => 'task',
                '{titre}'     => strip_tags(htmlspecialchars_decode(get_titre_ligne_table('task', $task)), ''),
                '{contenu}'   => recuperer_gabarit('task/bloc_apercu.html', $trans),
            ]);

        }

    } else {

        include __DIR__ . '/_task_list.php';

        $code_html .= recuperer_gabarit('main/section.html', [
            '{fonction}'  => 'lister',
            '{nom_table}' => 'task',
            '{titre}'     => htmlspecialchars(get_nom_colonne('libelle_liste_task')),
            '{contenu}'   => recuperer_gabarit('task/bloc_lister.html', $trans),
        ]);

    }

    if ($mf_action == "ajouter_task") {

        $form = new Formulaire('', $mess);
        /* start */
        $form->ajouter_input("task_Name", ( isset($_POST['task_Name']) ? $_POST['task_Name'] : $mf_initialisation['task_Name'] ), true);
//        $form->ajouter_input("task_Date_creation", ( isset($_POST['task_Date_creation']) ? $_POST['task_Date_creation'] : $mf_initialisation['task_Date_creation'] ), true);
        $form->ajouter_textarea("task_Description", ( isset($_POST['task_Description']) ? $_POST['task_Description'] : $mf_initialisation['task_Description'] ), true);
//        $form->ajouter_select(lister_cles($lang_standard['task_Workflow_']), "task_Workflow", ( isset($_POST['task_Workflow']) ? $_POST['task_Workflow'] : $mf_initialisation['task_Workflow'] ), true);
//        if (! isset($est_charge['user'])) {
//            $form->ajouter_select(lister_cles($lang_standard['Code_user_']), "Code_user", (isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : 0), true);
//        }
        /* end */

        $code_html .= recuperer_gabarit('task/form_add_task.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_add_task')], false, true);

    } elseif ($mf_action == "modifier_task") {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);
        if (isset($task['Code_task'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("task_Name", ( isset($_POST['task_Name']) ? $_POST['task_Name'] : $task['task_Name'] ), true);
            $form->ajouter_input("task_Date_creation", ( isset($_POST['task_Date_creation']) ? $_POST['task_Date_creation'] : $task['task_Date_creation'] ), true);
            $form->ajouter_textarea("task_Description", ( isset($_POST['task_Description']) ? $_POST['task_Description'] : $task['task_Description'] ), true);
            $form->ajouter_select(liste_union_A_et_B([$task['task_Workflow']], Hook_task::workflow__task_Workflow($task['task_Workflow'])), "task_Workflow", ( isset($_POST['task_Workflow']) ? $_POST['task_Workflow'] : $task['task_Workflow'] ), true);
            if (!isset($est_charge['user'])) {
                $form->ajouter_select(lister_cles($lang_standard['Code_user_']), "Code_user", (isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : $task['Code_user']), true);
            }

            $code_html .= recuperer_gabarit('task/form_edit_task.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_task')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_task_Name') {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);
        if (isset($task['Code_task'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("task_Name", ( isset($_POST['task_Name']) ? $_POST['task_Name'] : $task['task_Name'] ), true);

            $code_html .= recuperer_gabarit('task/form_edit_task_Name.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_task_Name')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_task_Date_creation') {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);
        if (isset($task['Code_task'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("task_Date_creation", ( isset($_POST['task_Date_creation']) ? $_POST['task_Date_creation'] : $task['task_Date_creation'] ), true);

            $code_html .= recuperer_gabarit('task/form_edit_task_Date_creation.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_task_Date_creation')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_task_Description') {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);
        if (isset($task['Code_task'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_textarea("task_Description", ( isset($_POST['task_Description']) ? $_POST['task_Description'] : $task['task_Description'] ), true);

            $code_html .= recuperer_gabarit('task/form_edit_task_Description.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_task_Description')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_task_Workflow') {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);
        if (isset($task['Code_task'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_select(liste_union_A_et_B([$task['task_Workflow']], Hook_task::workflow__task_Workflow($task['task_Workflow'])), 'task_Workflow', (isset($_POST['task_Workflow']) ? $_POST['task_Workflow'] : $task['task_Workflow']), true);

            $code_html .= recuperer_gabarit('task/form_edit_task_Workflow.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_task_Workflow')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_task__Code_user') {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);
        if (isset($task['Code_task'])) {

            $form = new Formulaire('', $mess);
            if (! isset($est_charge['user'])) {
                $form->ajouter_select(lister_cles($lang_standard['Code_user_']), "Code_user", (isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : $task['Code_user']), true);
            }

            $code_html .= recuperer_gabarit('task/form_edit__Code_user.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_task__Code_user')], false, true);

        }

    }
    elseif ($mf_action == 'supprimer_task') {

        $task = $table_task->mf_get(mf_Code_task(), ['autocompletion' => true]);
        if (isset($task['Code_task'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_select([0, 1], 'Supprimer', FORM_SUPPR_DEFAUT, true);
            $form->activer_picto_suppression();

            $code_html .= recuperer_gabarit('task/form_delete_task.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_delete_task')], false, true);

        }

    }

