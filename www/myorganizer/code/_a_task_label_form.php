<?php declare(strict_types=1);

    if ($mf_action == 'apercu_a_task_label' && $_GET['act'] != 'ajouter_a_task_label' && $_GET['act'] != 'supprimer_a_task_label') {

        if (isset($Code_task) && $Code_task!=0 && isset($Code_label) && $Code_label!=0) {
            $a_task_label = $table_a_task_label->mf_get(mf_Code_task(), mf_Code_label(), ['autocompletion' => true]);
        }

        if (isset($a_task_label['Code_task'])) {

            $fil_ariane->ajouter_titre(get_titre_ligne_table('a_task_label', $a_task_label), get_nom_page_courante().'?act=apercu_a_task_label&Code_task='.$Code_task.'&Code_label='.$Code_label.'');

            if ( ! MULTI_BLOCS ) { $code_html = ''; }

            include __DIR__ . '/_a_task_label_get.php';

            $code_html .= recuperer_gabarit('main/section.html', [
                '{fonction}'  => 'apercu',
                '{nom_table}' => 'a_task_label',
                '{titre}'     => htmlspecialchars(get_titre_ligne_table('a_task_label', $a_task_label)),
                '{contenu}'   => recuperer_gabarit('a_task_label/bloc_apercu.html', $trans),
            ]);

        }

    }
    else {

        include __DIR__ . '/_a_task_label_list.php';

        $code_html .= recuperer_gabarit('main/section.html', [
            '{fonction}'  => 'lister',
            '{nom_table}' => 'a_task_label',
            '{titre}'     => htmlspecialchars(get_nom_colonne('libelle_liste_a_task_label')),
            '{contenu}'   => recuperer_gabarit('a_task_label/bloc_lister.html', $trans),
        ]);

    }

    if ($mf_action == "ajouter_a_task_label") {

        $form = new Formulaire('', $mess);
        if (!isset($est_charge['task']))
        {
            $form->ajouter_select(lister_cles($lang_standard['Code_task_']), "Code_task", ( isset($_POST['Code_task']) ? (int) $_POST['Code_task'] : 0 ), true);
        }
        if (!isset($est_charge['label']))
        {
            $form->ajouter_select(lister_cles($lang_standard['Code_label_']), "Code_label", ( isset($_POST['Code_label']) ? (int) $_POST['Code_label'] : 0 ), true);
        }
        $form->ajouter_select(lister_cles($lang_standard['a_task_label_Link_']), "a_task_label_Link", ( isset($_POST['a_task_label_Link']) ? $_POST['a_task_label_Link'] : $mf_initialisation['a_task_label_Link'] ), true);

        $code_html .= recuperer_gabarit('a_task_label/form_add_a_task_label.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_add_a_task_label')], false, true);

    } elseif ($mf_action=="modifier_a_task_label") {

        $a_task_label = $table_a_task_label->mf_get(mf_Code_task(), mf_Code_label(), ['autocompletion' => true]);
        if (isset($a_task_label['Code_task']))
        {

            $form = new Formulaire('', $mess);
            $form->ajouter_select(lister_cles($lang_standard['a_task_label_Link_']), "a_task_label_Link", ( isset($_POST['a_task_label_Link']) ? $_POST['a_task_label_Link'] : $a_task_label['a_task_label_Link'] ), true);

            $code_html .= recuperer_gabarit('a_task_label/form_edit_a_task_label.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_a_task_label')], false, true);

        }
    } elseif ($mf_action=='modifier_a_task_label_Link')
    {

        $a_task_label = $table_a_task_label->mf_get(mf_Code_task(), mf_Code_label(), ['autocompletion' => true]);
        if (isset($a_task_label['Code_task']))
        {

            $form = new Formulaire('', $mess);
            $form->ajouter_select(lister_cles($lang_standard['a_task_label_Link_']), "a_task_label_Link", ( isset($_POST['a_task_label_Link']) ? $_POST['a_task_label_Link'] : $a_task_label['a_task_label_Link'] ), true);

            $code_html .= recuperer_gabarit('a_task_label/form_edit_a_task_label_Link.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_a_task_label_Link')], false, true);

        }
    } elseif ($mf_action=="supprimer_a_task_label")
    {

        $a_task_label = $table_a_task_label->mf_get(mf_Code_task(), mf_Code_label(), ['autocompletion' => true]);
        if ( isset($a_task_label['Code_task']) )
        {

            $form = new Formulaire('', $mess);
            $form->ajouter_select([0, 1], 'Supprimer', FORM_SUPPR_DEFAUT, true);
            $form->activer_picto_suppression();

            $code_html .= recuperer_gabarit('a_task_label/form_delete_a_task_label.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_delete_a_task_label')], false, true);

        }

    }
