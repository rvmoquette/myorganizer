<?php declare(strict_types=1);

    if ($mf_action == 'apercu_a_user_task' && $_GET['act'] != 'ajouter_a_user_task' && $_GET['act'] != 'supprimer_a_user_task') {

        if (isset($Code_user) && $Code_user!=0 && isset($Code_task) && $Code_task!=0) {
            $a_user_task = $table_a_user_task->mf_get(mf_Code_user(), mf_Code_task(), ['autocompletion' => true]);
        }

        if (isset($a_user_task['Code_user'])) {

            $fil_ariane->ajouter_titre(get_titre_ligne_table('a_user_task', $a_user_task), get_nom_page_courante().'?act=apercu_a_user_task&Code_user='.$Code_user.'&Code_task='.$Code_task.'');

            if ( ! MULTI_BLOCS ) { $code_html = ''; }

            include __DIR__ . '/_a_user_task_get.php';

            $code_html .= recuperer_gabarit('main/section.html', [
                '{fonction}'  => 'apercu',
                '{nom_table}' => 'a_user_task',
                '{titre}'     => htmlspecialchars(get_titre_ligne_table('a_user_task', $a_user_task)),
                '{contenu}'   => recuperer_gabarit('a_user_task/bloc_apercu.html', $trans),
            ]);

        }

    }
    else {

        include __DIR__ . '/_a_user_task_list.php';

        $code_html .= recuperer_gabarit('main/section.html', [
            '{fonction}'  => 'lister',
            '{nom_table}' => 'a_user_task',
            '{titre}'     => htmlspecialchars(get_nom_colonne('libelle_liste_a_user_task')),
            '{contenu}'   => recuperer_gabarit('a_user_task/bloc_lister.html', $trans),
        ]);

    }

    if ($mf_action == "ajouter_a_user_task") {

        $form = new Formulaire('', $mess);
        if (!isset($est_charge['user']))
        {
            $form->ajouter_select(lister_cles($lang_standard['Code_user_']), "Code_user", ( isset($_POST['Code_user']) ? (int) $_POST['Code_user'] : 0 ), true);
        }
        if (!isset($est_charge['task']))
        {
            $form->ajouter_select(lister_cles($lang_standard['Code_task_']), "Code_task", ( isset($_POST['Code_task']) ? (int) $_POST['Code_task'] : 0 ), true);
        }
        $form->ajouter_select(lister_cles($lang_standard['a_user_task_Link_']), "a_user_task_Link", ( isset($_POST['a_user_task_Link']) ? $_POST['a_user_task_Link'] : $mf_initialisation['a_user_task_Link'] ), true);

        $code_html .= recuperer_gabarit('a_user_task/form_add_a_user_task.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_add_a_user_task')], false, true);

    } elseif ($mf_action=="modifier_a_user_task") {

        $a_user_task = $table_a_user_task->mf_get(mf_Code_user(), mf_Code_task(), ['autocompletion' => true]);
        if (isset($a_user_task['Code_user']))
        {

            $form = new Formulaire('', $mess);
            $form->ajouter_select(lister_cles($lang_standard['a_user_task_Link_']), "a_user_task_Link", ( isset($_POST['a_user_task_Link']) ? $_POST['a_user_task_Link'] : $a_user_task['a_user_task_Link'] ), true);

            $code_html .= recuperer_gabarit('a_user_task/form_edit_a_user_task.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_a_user_task')], false, true);

        }
    } elseif ($mf_action=='modifier_a_user_task_Link')
    {

        $a_user_task = $table_a_user_task->mf_get(mf_Code_user(), mf_Code_task(), ['autocompletion' => true]);
        if (isset($a_user_task['Code_user']))
        {

            $form = new Formulaire('', $mess);
            $form->ajouter_select(lister_cles($lang_standard['a_user_task_Link_']), "a_user_task_Link", ( isset($_POST['a_user_task_Link']) ? $_POST['a_user_task_Link'] : $a_user_task['a_user_task_Link'] ), true);

            $code_html .= recuperer_gabarit('a_user_task/form_edit_a_user_task_Link.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_a_user_task_Link')], false, true);

        }
    } elseif ($mf_action=="supprimer_a_user_task")
    {

        $a_user_task = $table_a_user_task->mf_get(mf_Code_user(), mf_Code_task(), ['autocompletion' => true]);
        if ( isset($a_user_task['Code_user']) )
        {

            $form = new Formulaire('', $mess);
            $form->ajouter_select([0, 1], 'Supprimer', FORM_SUPPR_DEFAUT, true);
            $form->activer_picto_suppression();

            $code_html .= recuperer_gabarit('a_user_task/form_delete_a_user_task.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_delete_a_user_task')], false, true);

        }

    }
