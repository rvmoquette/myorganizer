<?php

class Formulaire
{

    // partie privee
    private $titre;

    private $liste_champs;

    private $message;

    private $action;

    private $libelle_bouton;

    private $activer_fichier;

    private $pleine_largeur;

    private $largeur_p;

    private static $num_id = 0;

    private $type_validation;

    private $inline;

    private $button_class;

    private $button_glyphicon;

    private $button_label;

    private $bouton_fermer_page;

    // partie publique
    function __construct($titre, $message)
    {
        $this->titre = $titre;
        $this->liste_champs = array();
        $this->message = $message;
        $this->action = '';
        $this->libelle_bouton = '';
        $this->activer_fichier = false;
        $this->pleine_largeur = false;
        $this->largeur_p = '';
        $this->type_validation = 'validate';
        $this->inline = true;
        $this->button_class = '';
        $this->button_glyphicon = '';
        $this->bouton_fermer_page = false;
    }

    function ajouter_input($nom_colonne, $valeur, $obligatoire, $type = '', $attributs = array())
    {
        if ($type == 'file') {
            $this->activer_fichier = true;
            $valeur = '';
        }
        $this->liste_champs[] = array(
            'input',
            $type,
            $nom_colonne,
            htmlspecialchars($valeur),
            $obligatoire,
            $attributs
        );
    }

    function ajouter_select($liste, $nom_colonne, $valeur, $obligatoire, $action_js = "")
    {
        $this->liste_champs[] = array(
            'select',
            $liste,
            $nom_colonne,
            htmlspecialchars($valeur),
            $obligatoire,
            $action_js
        );
    }

    function ajouter_select_multiple($liste, $nom_colonne, $valeurs, $obligatoire, $action_js = "")
    {
        $this->liste_champs[] = array(
            'select_multiple',
            $liste,
            $nom_colonne,
            $valeurs,
            $obligatoire,
            $action_js
        );
    }

    function ajouter_select_double($liste_1, $dependances, $nom_colonne_1, $nom_colonne_2, $valeur_2, $obligatoire, $action_js = '', $valeur_1 = 0)
    { // permet l'ajout d'un double champs lie : parent -> enfant et selection sur l'enfant
        $code_js = 'var i = document.getElementById(\'' . $nom_colonne_1 . '\').value;';
        $code_js .= 'var select_elem = document.getElementById(\'' . $nom_colonne_2 . '\');';

        $code_js .= 'while (select_elem.length>1) select_elem.remove(1);';

        foreach ($liste_1 as $code_1) {
            $code_js .= 'if (i==' . $code_1 . ') {';
            if (isset($dependances[$code_1])) {
                foreach ($dependances[$code_1] as $code_2) {
                    $code_js .= 'var option = document.createElement(\'option\');';
                    $code_js .= 'option.text = \'' . str_replace('\'', "\'", htmlspecialchars(get_nom_valeur($nom_colonne_2, $code_2))) . '\';';
                    $code_js .= 'option.value = \'' . $code_2 . '\';';
                    $code_js .= 'select_elem.add(option);';
                }
            }
            $code_js .= '}';
        }

        $liste_2 = array();
        if ($valeur_1 == 0) {
            foreach ($dependances as $code_1 => $liste_code_2) {
                foreach ($liste_code_2 as $code_2) {
                    if ($code_2 == $valeur_2) {
                        $liste_2 = $liste_code_2;
                        $valeur_1 = $code_1;
                    }
                }
            }
        } else {
            foreach ($dependances as $code_1 => $liste_code_2) {
                if ($code_1 == $valeur_1) {
                    $liste_2 = $liste_code_2;
                }
            }
        }

        $this->ajouter_select($liste_1, $nom_colonne_1, $valeur_1, $obligatoire, $code_js);
        $this->ajouter_select($liste_2, $nom_colonne_2, $valeur_2, $obligatoire, $action_js);
    }

    function ajouter_textarea($nom_colonne, $valeur, $obligatoire, $class = '')
    {
        $this->liste_champs[] = array(
            'textarea',
            '',
            $nom_colonne,
            htmlspecialchars($valeur),
            $obligatoire,
            '',
            $class
        );
    }

    function ajouter_infos($legende, $infos_text)
    {
        $this->liste_champs[] = array(
            'infos_texte',
            '',
            $legende,
            $infos_text,
            '',
            '',
            ''
        );
    }

    function set_action($action)
    {
        $this->action = $action;
    }

    function set_libelle_bouton($libelle_bouton)
    {
        $this->libelle_bouton = $libelle_bouton; // htmlspecialchars est utilise plus tard
    }

    function mode_pleine_largeur()
    {
        $this->pleine_largeur = true;
    }

    function set_largeur_personnalise($largeur_p)
    {
        $this->largeur_p = $largeur_p;
    }

    function activer_picto_suppression()
    {
        $this->type_validation = 'delete';
    }

    function activer_picto_connexion()
    {
        $this->type_validation = 'login';
    }

    function activer_picto_forget_password()
    {
        $this->type_validation = 'forget_password';
    }

    function desactiver_le_mode_inline()
    {
        $this->inline = false;
    }

    function bouton_fermer_page()
    {
        $this->bouton_fermer_page = true;
    }

    function personalisation_button($button_class, $button_glyphicon, $button_label)
    {
        $this->button_class = $button_class;
        $this->button_glyphicon = $button_glyphicon;
        $this->libelle_bouton = $button_label;
    }

    function generer_code()
    {
        if (USE_BOOTSTRAP) {
            if (VERSION_BOOTSTRAP == 3) {
                return $this->generer_BS_Forms();
            } elseif (VERSION_BOOTSTRAP == 4) {
                return $this->generer_BS4_Forms();
            } else {
                return 'Version non supportée';
            }
        }

        global $menu_a_droite, $mf_dictionnaire_db;
        $code = '<form id="myForm"' . ($this->largeur_p != '' ? ' style="width:' . $this->largeur_p . ';"' : '') . ' class="stdform' . ($this->pleine_largeur ? ' large' : '') . '" method="post"';
        if ($this->action != '')
            $code .= ' action="' . $this->action . '"';
        if ($this->activer_fichier)
            $code .= ' enctype="multipart/form-data"';
        $code .= '><fieldset>';
        if ($this->titre != '')
            $code .= '<legend>' . $this->titre . '</legend>';
        if ($this->message != '')
            $code .= '<p class="message_formulaire">' . $this->message . '</p>';
        $code .= '<ul>';
        foreach ($this->liste_champs as $value) {
            if ($value[0] == 'input') {
                $type = $value[1];
                $nom_colonne = $value[2];
                if ($type == '')
                    $type = $this->type_sql_vers_type_input($mf_dictionnaire_db[$nom_colonne]['type']);
                $valeur = $value[3];
                if ($type == 'datetime-local')
                    $valeur = format_datetime_local($valeur);
                $required = $value[4];
                $attributs = $value[5];
                $attributs_str = '';
                foreach ($attributs as $cle_atribut => $val_atribut) {
                    $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
                }
                $code = $code . '<li' . ($type == 'hidden' ? ' style="display: none;"' : '') . "><label class=\"sous_titre\" for=\"$nom_colonne\">" . htmlspecialchars(get_nom_colonne($nom_colonne)) . ($required ? " <em>*</em>" : "") . "</label><input type=\"$type\"" . ($type != "number" ? " placeholder=\"" . htmlspecialchars(get_nom_colonne($nom_colonne)) . "\"" : "") . " id=\"$nom_colonne\" name=\"$nom_colonne\"" . ($required ? " required=\"required\"" : "") . " value=\"$valeur\"$attributs_str></li>";
            } elseif ($value[0] == "select") {
                $code .= "<li><label class=\"sous_titre\" for=\"{$value[2]}\">" . htmlspecialchars(get_nom_colonne($value[2])) . ($value[4] ? " <em>*</em>" : "") . "</label><select id=\"{$value[2]}\" name=\"{$value[2]}\"" . ($value[4] ? " required=\"required\"" : "") . ($value[5] != "" ? " onchange=\"{$value[5]}\"" : "") . "><option></option>";
                foreach ($value[1] as $val) {
                    $code .= "<option value=\"$val\"" . ($val == $value[3] ? ' selected="selected"' : "") . ">" . htmlspecialchars(get_nom_valeur($value[2], $val)) . "</option>";
                }
                $code .= "</select>";
            } elseif ($value[0] == "textarea") {
                $code = $code . "<li><label class=\"sous_titre\" for=\"{$value[2]}\">" . htmlspecialchars(get_nom_colonne($value[2])) . ($value[4] ? " <em>*</em>" : "") . "</label><textarea placeholder=\"" . htmlspecialchars(get_nom_colonne($value[2])) . "\" id=\"{$value[2]}\" name=\"{$value[2]}\"" . ($value[4] ? " required=\"required\"" : "") . " class=\"{$value[6]}\">{$value[3]}</textarea></li>";
            } elseif ($value[0] == "infos_texte") {
                $code = $code . "<li><label class=\"sous_titre\">" . htmlspecialchars($value[2]) . "</label><div>{$value[3]}</div></li>";
            }
        }
        $code .= "</ul>";
        if (BOUTON_VALIDATION_SOUS_FORMULAIRE) {
            $code .= "<div class=\"button_container\"><input value=\"" . htmlspecialchars($this->libelle_bouton == "" ? "Valider" : $this->libelle_bouton) . "\" type=\"submit\"></div>";
        } else {
            $menu_a_droite->ajouter_bouton_formulaire(htmlspecialchars($this->libelle_bouton == "" ? "Valider" : $this->libelle_bouton));
        }
        $code .= "</fieldset><input type=\"hidden\" name=\"validation_formulaire\" value=\"" . mf_cle_aleatoire() . "\"></form>";
        return $code;
    }

    function generer_avec_gabarit(string $filename, array $trans = []): string
    {
        global $mf_dictionnaire_db;

        self::$num_id ++;

        $trans['{title}'] = htmlspecialchars($this->titre);
        $trans['{message}'] = ($this->message != '' ? get_code_alert_warning($this->message) : '');
        $trans['{action}'] = $this->action != '' ? ' action="' . $this->action . '"' : '';
        $trans['{enctype}'] = $this->activer_fichier ? ' enctype="multipart/form-data"' : '';

        foreach ($this->liste_champs as $value) {
            if ($value[0] == 'input') {
                $type = $value[1];
                $nom_colonne = $value[2];
                if ($type == '') {
                    $type = $this->type_sql_vers_type_input($mf_dictionnaire_db[$nom_colonne]['type']);
                }
                $valeur = $value[3];
                if ($type == 'datetime-local') {
                    $valeur = format_datetime_local($valeur);
                }
                $required = $value[4];
                $attributs = $value[5];
                $attributs_str = '';
                $mode_multiple = false;
                foreach ($attributs as $cle_atribut => $val_atribut) {
                    if ($val_atribut != '') {
                        $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
                    } else {
                        $attributs_str .= ' ' . $cle_atribut;
                        if ($cle_atribut == 'multiple') {
                            $mode_multiple = true;
                        }
                    }
                }

                $trans['{input_' . $nom_colonne . '}'] = '<input type="' . $type . '" class="form-control" id="' . $nom_colonne . '" name="' . $nom_colonne . ($mode_multiple ? '[]' : '') . '" placeholder="' . htmlspecialchars(get_nom_colonne($nom_colonne)) . '" value="' . $valeur . '" ' . ($required ? ' required="required"' : '') . ' ' . ($this->action != '' ? ' action="' . $this->action . '"' : '') . ' ' . $attributs_str . '>';
            } elseif ($value[0] == 'select') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $required = $value[4];
                $onchange = $value[5];

                $trans['{select_' . $nom_colonne . '}'] = '<select class="form-control" id="' . $nom_colonne . '" name="' . $nom_colonne . '" value="' . $valeur . '" ' . ($required ? ' required="required"' : '') . ($onchange != '' ? ' onchange="' . $onchange . '"' : '') . '>';
                $trans['{select_' . $nom_colonne . '}'] .= '<option></option>';
                foreach ($value[1] as $val) {
                    $trans['{select_' . $nom_colonne . '}'] .= '<option value="' . $val . '"' . ($val == $valeur ? ' selected="selected"' : '') . '>' . htmlspecialchars(get_nom_valeur($value[2], $val)) . '</option>';
                }
                $trans['{select_' . $nom_colonne . '}'] .= '</select>';
            } elseif ($value[0] == 'textarea') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $required = $value[4];
                $class = $value[6];

                $trans['{textarea_' . $nom_colonne . '}'] = '<textarea class="form-control ' . $class . '" id="' . $nom_colonne . '" name="' . $nom_colonne . '" placeholder="' . htmlspecialchars(get_nom_colonne($nom_colonne)) . '" ' . ($required ? ' required="required"' : '') . ' rows="5">' . $valeur . '</textarea>';
            } elseif ($value[0] == 'infos_texte') {
                $nom_colonne = $value[2];
                $valeur = $value[3];

                $trans['{infos_texte_' . $nom_colonne . '}'] = '<div class="">' . $valeur . '</div>';
            }
        }

        $trans['{input_hidden_cle_validation_formulaire}'] = '<input type="hidden" name="validation_formulaire" value="' . mf_cle_aleatoire() . '">';

        return recuperer_gabarit($filename, $trans);
    }

    /*
     * $options['class_container_bouton']
     * $options['class_bouton_submit']
     */
    function generer_BS4_Forms($options = array())
    {
        global $mf_dictionnaire_db;

        self::$num_id ++;

        $code = '';

        if ($this->titre != '')
            $code .= '<h2>' . $this->titre . '</h2>';

        if ($this->message != '') {
            $code .= get_code_alert_warning($this->message);
        }

        $code .= '<form id="my_form_auto_' . self::$num_id . '" method="post"' . ($this->activer_fichier ? ' enctype="multipart/form-data"' : '') . ($this->action != '' ? ' action="' . $this->action . '"' : '') . '>';

        if ($this->type_validation == 'delete') {
            $required_html = '<span class="glyphicon glyphicon-hand-right text-danger"></span>&nbsp;';
        } elseif ($this->type_validation == 'validate') {
            $required_html = '<span class="glyphicon glyphicon-hand-right text-primary"></span>&nbsp;';
        } else {
            $required_html = '';
        }

        foreach ($this->liste_champs as $value) {
            if ($value[0] == 'input') {
                $type = $value[1];
                $nom_colonne = $value[2];
                if ($type == '') {
                    $type = $this->type_sql_vers_type_input($mf_dictionnaire_db[$nom_colonne]['type']);
                }
                $valeur = $value[3];
                if ($type == 'datetime-local') {
                    $valeur = format_datetime_local($valeur);
                }
                $required = $value[4];
                $attributs = $value[5];
                $attributs_str = '';
                $mode_multiple = false;
                foreach ($attributs as $cle_atribut => $val_atribut) {
                    if ($val_atribut != '') {
                        $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
                    } else {
                        $attributs_str .= ' ' . $cle_atribut;
                        if ($cle_atribut == 'multiple') {
                            $mode_multiple = true;
                        }
                    }
                }
                if ($type != 'hidden') {
                    $code .= '<div class="form-group ' . ($this->inline ? ' row' : '') . '">';
                    $code .= '<label class="' . ($this->inline ? 'col-sm-3 col-form-label' : '') . '" for="' . $nom_colonne . '">' . ($required ? $required_html : '') . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                    $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                }
                $code .= '<input type="' . $type . '" class="form-control" id="' . $nom_colonne . '" name="' . $nom_colonne . ($mode_multiple ? '[]' : '') . '" placeholder="' . htmlspecialchars(get_nom_colonne($nom_colonne)) . '" value="' . $valeur . '" ' . ($required ? ' required="required"' : '') . ' ' . ($this->action != '' ? ' action="' . $this->action . '"' : '') . ' ' . $attributs_str . '>';
                if ($type != 'hidden') {
                    $code .= '</div>';
                    $code .= '</div>';
                }
            } elseif ($value[0] == 'select') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $required = $value[4];
                $onchange = $value[5];
                $code .= '<div class="form-group' . ($this->inline ? ' row' : '') . '">';
                $code .= '<label class="' . ($this->inline ? 'col-sm-3 col-form-label' : '') . '" for="' . $nom_colonne . '">' . ($required ? $required_html : '') . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                $code .= '<select class="form-control" id="' . $nom_colonne . '" name="' . $nom_colonne . '" value="' . $valeur . '" ' . ($required ? ' required="required"' : '') . ($onchange != '' ? ' onchange="' . $onchange . '"' : '') . '>';
                $code .= '<option></option>';
                foreach ($value[1] as $val) {
                    $code .= '<option value="' . $val . '"' . ($val == $valeur ? ' selected="selected"' : '') . '>' . htmlspecialchars(get_nom_valeur($value[2], $val)) . '</option>';
                }
                $code .= '</select>';
                $code .= '</div>';
                $code .= '</div>';
            } elseif ($value[0] == 'select_multiple') {
                $nom_colonne = $value[2];
                $valeurs = $value[3];
                $required = $value[4];
                $onchange = $value[5];
                $code .= '<div class="form-group' . ($this->inline ? ' row' : '') . '">';
                $code .= '<label class="' . ($this->inline ? 'col-sm-3 col-form-label' : '') . '" for="' . $nom_colonne . '">' . ($required ? $required_html : '') . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                $code .= '<select multiple size="9" class="form-control" id="' . $nom_colonne . '" name="' . $nom_colonne . '[]" ' . ($required ? ' required="required"' : '') . ($onchange != '' ? ' onchange="' . $onchange . '"' : '') . '>';
                foreach ($value[1] as $val) {
                    $sel = false;
                    foreach ($valeurs as $v) {
                        if ($val == $v) {
                            $sel = true;
                        }
                    }
                    $code .= '<option value="' . $val . '"' . ($sel ? ' selected="selected"' : '') . '>' . htmlspecialchars(get_nom_valeur($value[2], $val)) . '</option>';
                }
                $code .= '</select>';
                $code .= '</div>';
                $code .= '</div>';
            } elseif ($value[0] == 'textarea') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $required = $value[4];
                $class = $value[6];
                $code .= '<div class="form-group' . ($this->inline ? ' row' : '') . '">';
                $code .= '<label class="' . ($this->inline ? 'col-sm-3 col-form-label' : '') . '" for="' . $nom_colonne . '">' . ($required ? $required_html : '') . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                $code .= '<textarea class="form-control ' . $class . '" id="' . $nom_colonne . '" name="' . $nom_colonne . '" placeholder="' . htmlspecialchars(get_nom_colonne($nom_colonne)) . '" ' . ($required ? ' required="required"' : '') . ' rows="5">' . $valeur . '</textarea>';
                $code .= '</div>';
                $code .= '</div>';
            } elseif ($value[0] == 'infos_texte') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $code .= '<div class="form-group' . ($this->inline ? ' row' : '') . '">';
                $code .= '<label class="' . ($this->inline ? 'col-sm-3 col-form-label' : '') . '">' . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                $code .= '<div class="">' . $valeur . '</div>';
                $code .= '</div>';
                $code .= '</div>';
            }
        }

        // bouton validé
        $code .= '<div class="form-group' . ($this->inline ? ' row' : '') . '">';
        if ($this->inline) {
            $code .= '<div class="' . ($this->inline ? 'col-sm-3' : '') . '"></div>';
        }
        $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';

        if ($this->type_validation == 'validate') {
            $class = 'btn btn-primary';
            $glyphicon = 'glyphicon glyphicon-ok';
        } elseif ($this->type_validation == 'delete') {
            $class = 'btn btn-danger';
            $glyphicon = 'glyphicon glyphicon-trash';
        } elseif ($this->type_validation == 'login') {
            $class = 'btn btn-primary';
            $glyphicon = 'glyphicon glyphicon-log-in';
        } elseif ($this->type_validation == 'forget_password') {
            $class = 'btn btn-primary';
            $glyphicon = 'glyphicon glyphicon-send';
        }

        if ($this->button_class != '' || $this->button_glyphicon != '') {
            $class = $this->button_class;
            $glyphicon = $this->button_glyphicon;
        }

        if ($this->bouton_fermer_page) {
            // Fermeture de la page
            $code .= '<button type="button" class="btn btn-danger" onclick="window.close();">Fermer la page</button>';
        } else {
            // Envoi du formulaire
            $code .= '<div class="' . (isset($options['class_container_bouton']) ? $options['class_container_bouton'] : '') . '"><button type="submit" class="' . (isset($options['class_bouton_submit']) ? $options['class_bouton_submit'] : $class) . '"><span class="' . $glyphicon . '"></span> ' . htmlspecialchars($this->libelle_bouton == '' ? get_nom_colonne('mf_validation') : $this->libelle_bouton) . '</button><span class="maj_dyn_etat" style="margin-top: 4px; margin-left: 4px; position: absolute;"></span></div>';
        }

        $code .= '</div>';
        $code .= '</div>';

        if ($this->activer_fichier) {
            $max_size = min(array(
                ini_get('post_max_size'),
                ini_get('upload_max_filesize')
            ));
            $code .= '<div class="form-group' . ($this->inline ? ' row' : '') . '">';
            if ($this->inline) {
                $code .= '<div class="' . ($this->inline ? 'col-sm-3' : '') . '"></div>';
            }
            $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
            $code .= get_code_alert_info('<strong>Pour info&nbsp;:</strong> Taille maximale acceptée à l\'envoi du formulaire&nbsp;: ' . $max_size);
            $code .= '</div></div>';
        }

        $code .= '<input type="hidden" name="validation_formulaire" value="' . mf_cle_aleatoire() . '">';

        $code .= '</form>';

        // empecher une double validation au niveau js
        mf_injection_js('$("#my_form_auto_' . self::$num_id . '").submit(function(e){ e.preventDefault(); $("form .maj_dyn_etat").addClass("on"); $("form button[type=submit]").prop("disabled", true); $(this).unbind("submit").submit(); });');

        return $code;
    }

    function generer_BS_Forms()
    {
        global $mf_dictionnaire_db;

        self::$num_id ++;

        $code = '';

        if ($this->titre != '')
            $code .= '<h2>' . $this->titre . '</h2>';

        if ($this->message != '')
            $code .= '<div class="alert alert-info">' . $this->message . '</div>';

        $code .= '<form id="my_form_auto_' . self::$num_id . '" class="' . ($this->inline ? 'form-horizontal' : '') . '" method="post"' . ($this->activer_fichier ? ' enctype="multipart/form-data"' : '') . ($this->action != '' ? ' action="' . $this->action . '"' : '') . '>';

        if ($this->type_validation == 'delete') {
            $required_html = '<span class="glyphicon glyphicon-hand-right text-danger"></span>&nbsp;';
        } elseif ($this->type_validation == 'validate') {
            $required_html = '<span class="glyphicon glyphicon-hand-right text-primary"></span>&nbsp;';
        } else {
            $required_html = '';
        }

        $i = 0;
        foreach ($this->liste_champs as $value) {
            if ($value[0] == 'input') {
                $type = $value[1];
                $nom_colonne = $value[2];
                if ($type == '') {
                    $type = $this->type_sql_vers_type_input($mf_dictionnaire_db[$nom_colonne]['type']);
                }
                $valeur = $value[3];
                if ($type == 'datetime-local') {
                    $valeur = format_datetime_local($valeur);
                }
                $required = $value[4];
                $attributs = $value[5];
                $attributs_str = '';
                $mode_multiple = false;
                foreach ($attributs as $cle_atribut => $val_atribut) {
                    if ($val_atribut != '') {
                        $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
                    } else {
                        $attributs_str .= ' ' . $cle_atribut;
                        if ($cle_atribut == 'multiple') {
                            $mode_multiple = true;
                        }
                    }
                }
                if ($type != 'hidden') {
                    $code .= '<div class="form-group">';
                    $code .= '<label class="control-label' . ($this->inline ? ' col-sm-3' : '') . '" for="' . $nom_colonne . '">' . ($required ? $required_html : '') . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                    $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                }
                $code .= '<input type="' . $type . '" class="form-control" id="' . $nom_colonne . '" name="' . $nom_colonne . ($mode_multiple ? '[]' : '') . '" placeholder="' . htmlspecialchars(get_nom_colonne($nom_colonne)) . '" value="' . $valeur . '" ' . ($required ? ' required="required"' : '') . ' ' . ($this->action != '' ? ' action="' . $this->action . '"' : '') . ' ' . $attributs_str . '>';
                if ($type != 'hidden') {
                    $code .= '</div>';
                    $code .= '</div>';
                }
            } elseif ($value[0] == 'select') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $required = $value[4];
                $onchange = $value[5];
                $code .= '<div class="form-group">';
                $code .= '<label class="control-label' . ($this->inline ? ' col-sm-3' : '') . '" for="' . $nom_colonne . '">' . ($required ? $required_html : '') . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                $code .= '<select class="form-control" id="' . $nom_colonne . '" name="' . $nom_colonne . '" value="' . $valeur . '" ' . ($required ? ' required="required"' : '') . ($onchange != '' ? ' onchange="' . $onchange . '"' : '') . '>';
                $code .= '<option></option>';
                foreach ($value[1] as $val) {
                    $code .= '<option value="' . $val . '"' . ($val == $valeur ? ' selected="selected"' : '') . '>' . htmlspecialchars(get_nom_valeur($value[2], $val)) . '</option>';
                }
                $code .= '</select>';
                $code .= '</div>';
                $code .= '</div>';
            } elseif ($value[0] == 'textarea') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $required = $value[4];
                $class = $value[6];
                $code .= '<div class="form-group">';
                $code .= '<label class="control-label' . ($this->inline ? ' col-sm-3' : '') . '" for="' . $nom_colonne . '">' . ($required ? $required_html : '') . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                $code .= '<textarea class="form-control ' . $class . '" id="' . $nom_colonne . '" name="' . $nom_colonne . '" placeholder="' . htmlspecialchars(get_nom_colonne($nom_colonne)) . '" ' . ($required ? ' required="required"' : '') . ' rows="5">' . $valeur . '</textarea>';
                $code .= '</div>';
                $code .= '</div>';
            } elseif ($value[0] == 'infos_texte') {
                $nom_colonne = $value[2];
                $valeur = $value[3];
                $code .= '<div class="form-group">';
                $code .= '<label class="control-label' . ($this->inline ? ' col-sm-3' : '') . '">' . (get_nom_colonne($nom_colonne) != '' ? htmlspecialchars(get_nom_colonne($nom_colonne)) . '&nbsp;:' : '') . '</label>';
                $code .= '<div class="' . ($this->inline ? 'col-sm-9' : '') . '">';
                $code .= '<div class="">' . $valeur . '</div>';
                $code .= '</div>';
                $code .= '</div>';
            }
            if ($value[0] != 'infos_texte') {
                if ($i == 0) {
                    mf_injection_js('setTimeout(function(){ $("#' . $nom_colonne . '").focus(); }, 200);');
                }
                $i ++;
            }
        }

        // bouton validé
        $code .= '<div class="form-group">';
        $code .= '<div class="' . ($this->inline ? 'col-sm-offset-3 col-sm-9' : '') . '">';

        if ($this->type_validation == 'validate') {
            $class = 'btn btn-primary';
            $glyphicon = 'glyphicon glyphicon-ok';
        } elseif ($this->type_validation == 'delete') {
            $class = 'btn btn-danger';
            $glyphicon = 'glyphicon glyphicon-trash';
        } elseif ($this->type_validation == 'login') {
            $class = 'btn btn-primary';
            $glyphicon = 'glyphicon glyphicon-log-in';
        } elseif ($this->type_validation == 'forget_password') {
            $class = 'btn btn-primary';
            $glyphicon = 'glyphicon glyphicon-send';
        }

        if ($this->button_class != '' || $this->button_glyphicon != '') {
            $class = $this->button_class;
            $glyphicon = $this->button_glyphicon;
        }

        $code .= '<button type="submit" class="' . $class . '"><span class="' . $glyphicon . '"></span> ' . htmlspecialchars($this->libelle_bouton == '' ? get_nom_colonne('mf_validation') : $this->libelle_bouton) . '</button><span class="maj_dyn_etat" style="margin-top: 4px; margin-left: 4px; position: absolute;"></span>';
        $code .= '</div>';
        $code .= '</div>';

        if ($this->activer_fichier) {
            $max_size = min(array(
                ini_get('post_max_size'),
                ini_get('upload_max_filesize')
            ));
            $code .= '<div class="form-group">';
            $code .= '<div class="' . ($this->inline ? 'col-sm-offset-3 col-sm-9' : '') . '"><div class="alert alert-info">
            <strong>Pour info&nbsp;:</strong> Taille maximale acceptée à l\'envoi du formulaire&nbsp;: ' . $max_size . '
            </div></div>';
            $code .= '</div>';
        }

        $code .= '<input type="hidden" name="validation_formulaire" value="' . mf_cle_aleatoire() . '">';

        $code .= '</form>';

        // empecher une double validation au niveau js
        mf_injection_js('$("#my_form_auto_' . self::$num_id . '").submit(function(e){ e.preventDefault(); $("form .maj_dyn_etat").addClass("on"); $("form button[type=submit]").prop("disabled", true); $(this).unbind("submit").submit(); });');

        return $code;
    }

    private function type_sql_vers_type_input($type_sql)
    {
        switch ($type_sql) {
            case "BOOL":
                $type = "checkbox";
                break;
            case "DATE":
                $type = "date";
                break;
            case "DATETIME":
                $type = "datetime-local";
                break;
            case "TIMESTAMP":
                $type = "datetime-local";
                break;
            case "DOUBLE":
                $type = "text";
                break;
            case "INT":
                $type = "number";
                break;
            case "VARCHAR":
                $type = "text";
                break;
            case "TIME":
                $type = "time";
                break;
            default:
                $type = "text";
                break;
        }
        return $type;
    }
}
