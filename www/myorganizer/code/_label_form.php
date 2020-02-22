<?php declare(strict_types=1);

/** @var string $mf_action */
/** @var string $mess */

    if ($mf_action == 'apercu_label' || $mf_action <> '' && mf_Code_label() != 0) {

        $label = $table_label->mf_get(mf_Code_label(), ['autocompletion' => true]);

        if (isset($label['Code_label'])) {

            $fil_ariane->ajouter_titre(get_titre_ligne_table('label', $label), get_nom_page_courante().'?act=apercu_label&Code_label=' . $label['Code_label']);

            $menu_a_droite->raz_boutons();

            if ( ! MULTI_BLOCS ) { $code_html = ''; }

            include __DIR__ . '/_label_get.php';

            $code_html .= recuperer_gabarit('main/section.html', [
                '{fonction}'  => 'apercu',
                '{nom_table}' => 'label',
                '{titre}'     => strip_tags(htmlspecialchars_decode(get_titre_ligne_table('label', $label)), ''),
                '{contenu}'   => recuperer_gabarit('label/bloc_apercu.html', $trans),
            ]);

        }

    } else {

        include __DIR__ . '/_label_list.php';

        $code_html .= recuperer_gabarit('main/section.html', [
            '{fonction}'  => 'lister',
            '{nom_table}' => 'label',
            '{titre}'     => htmlspecialchars(get_nom_colonne('libelle_liste_label')),
            '{contenu}'   => recuperer_gabarit('label/bloc_lister.html', $trans),
        ]);

    }

    if ($mf_action == "ajouter_label") {

        $form = new Formulaire('', $mess);
        $form->ajouter_input("label_Name", ( isset($_POST['label_Name']) ? $_POST['label_Name'] : $mf_initialisation['label_Name'] ), true);

        $code_html .= recuperer_gabarit('label/form_add_label.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_add_label')], false, true);

    } elseif ($mf_action == "modifier_label") {

        $label = $table_label->mf_get(mf_Code_label(), ['autocompletion' => true]);
        if (isset($label['Code_label'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("label_Name", ( isset($_POST['label_Name']) ? $_POST['label_Name'] : $label['label_Name'] ), true);

            $code_html .= recuperer_gabarit('label/form_edit_label.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_label')], false, true);

        }

    }
    elseif ($mf_action == 'modifier_label_Name') {

        $label = $table_label->mf_get(mf_Code_label(), ['autocompletion' => true]);
        if (isset($label['Code_label'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_input("label_Name", ( isset($_POST['label_Name']) ? $_POST['label_Name'] : $label['label_Name'] ), true);

            $code_html .= recuperer_gabarit('label/form_edit_label_Name.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_edit_label_Name')], false, true);

        }

    }
    elseif ($mf_action == 'supprimer_label') {

        $label = $table_label->mf_get(mf_Code_label(), ['autocompletion' => true]);
        if (isset($label['Code_label'])) {

            $form = new Formulaire('', $mess);
            $form->ajouter_select([0, 1], 'Supprimer', FORM_SUPPR_DEFAUT, true);
            $form->activer_picto_suppression();

            $code_html .= recuperer_gabarit('label/form_delete_label.html', ['{form}' => $form->generer_code(), '{title}' => get_nom_colonne('form_delete_label')], false, true);

        }

    }

