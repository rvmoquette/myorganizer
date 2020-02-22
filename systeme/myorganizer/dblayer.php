<?php
include __DIR__ . '/dblayer_light.php';

$db = new DB();

if (! MODE_PROD) {
    include __DIR__ . '/fonctions_developpeur.php';
    $mf_liste_gabarits = [];
}

function formulaire_valide($cle_formulaire)
{
    $secur = (isset($_GET['secur']) ? '' . $_GET['secur'] : '');
    if ($secur != '') {
        if (isset($_SESSION[PREFIXE_SESSION]['valid_form'][$secur])) {
            if ($_SESSION[PREFIXE_SESSION]['valid_form'][$secur] == $cle_formulaire) {
                return true;
            }
        }
    }
    sleep(1);
    return false;
}

/* utils */
function cookies(&$var, $nom)
{
    if ($var == '') {
        if (isset($_SESSION[PREFIXE_SESSION][$nom])) {
            $var = $_SESSION[PREFIXE_SESSION][$nom];
        }
    } else {
        $_SESSION[PREFIXE_SESSION][$nom] = $var;
    }
}

function format_datetime_local($datetimelocal)
{
    $p = strrpos($datetimelocal, ' ');
    if ($p == 0)
        $p = strrpos($datetimelocal, 'T');
    if ($p > 0) {
        $date = substr($datetimelocal, 0, $p);
        $time = substr($datetimelocal, $p + 1);
    } else {
        return '';
    }
    $date = format_date($date);
    $time = format_time($time);
    if ($date != '' && $time != '') {
        $time = substr($time, 0, 5);
        return $date . 'T' . $time;
    } else
        return '';
}

function extraction_resultat_requete($resultat, $code_colonne)
{
    $retour = array();
    foreach ($resultat as $key => $value) {
        $retour[$key] = $value[$code_colonne];
    }
    return $retour;
}

function telecharger_fichier_image(&$file)
{
    $photo = '';
    if (substr($file['type'], 0, 6) == 'image/') {
        $extension = substr($file['type'], 6, 10);
        $nom = 'f' . salt(30) . '.' . $extension;
        $cheminlocal = 'images/' . $nom;
        $resultat = move_uploaded_file($file['tmp_name'], $cheminlocal);
        if ($resultat) {
            // $dim = getimagesize($cheminlocal);
            $photo = $nom;
        }
    }
    return $photo;
}

function get_photo($valeur)
{
    if ($valeur != '') {
        return '<img alt="" src="mf_fichier.php?n=' . $valeur . '" class="fichier_photo">';
    } else {
        return '<div class="alert alert-info">Aucune photo.</div>';
    }
}

function get_fichier(string $valeur)
{
    if ($valeur != '') {
        return '<iframe style="border: none; overflow: auto; margin: 0; height: 600px; width: 100%" src="mf_fichier.php?n=' . $valeur . '"></iframe>';
    } else {
        return '<div class="alert alert-info"><i style="opacity: 0.5;">Aucun fichier.</i></div>';
    }
}

$mf_script_end = '';

function mf_injection_js($script_js)
{
    global $mf_script_end;
    $mf_script_end .= '{' . $script_js . '}';
}

$mf_cpt_gabarit = 1;
$mf_cpt_modal = 1;

function mf_modal_get_num()
{
    global $mf_cpt_modal;
    return ($mf_cpt_modal - 1);
}
$mf_script_end_modal = '';

function recuperer_gabarit($filename, $trans, $forcer_mode_prod = false, $mode_modal = false)
{
    if (MODE_PROD || MODE_DESIGN || $forcer_mode_prod) {
        $retour = get_gabarit($filename, $trans);
    } else {
        if (! file_exists('gabarits/' . $filename)) {
            file_put_contents('gabarits/' . $filename, '');
        }
        global $mf_cpt_gabarit, $fil_ariane;
        $txt = file_get_contents('gabarits/' . $filename);
        $liste_cles = '';
        $trans['{ADRESSE_SITE}'] = ADRESSE_SITE;
        foreach ($trans as $key => $value) {
            if (stripos($txt, $key) === false) {
                $liste_cles = $key . '<br>' . $liste_cles;
            }
        }
        $liste_cles .= '<br><br>BOOTSTRAP MEMO<br>--------------<br><br>';
        $liste_cles .= '<div class="well">';
        $liste_cles .= htmlspecialchars('<div class="row">') . '<br>';
        $liste_cles .= '&nbsp;&nbsp;' . htmlspecialchars('<div class="col-sm-6"></div>') . '<br>';
        $liste_cles .= '&nbsp;&nbsp;' . htmlspecialchars('<div class="col-sm-6"></div>') . '<br>';
        $liste_cles .= htmlspecialchars('</div>');
        $liste_cles .= '</div>';
        $liste_cles .= '<div class="well">';
        $liste_cles .= htmlspecialchars('<p class="text-center"></p>');
        $liste_cles .= '</div>';
        $liste_cles .= '<div class="well">';
        $liste_cles .= '<strong>xs</strong><small> ' . htmlspecialchars('(for phones - screens less than 768px wide)') . '</small><br>';
        $liste_cles .= '<strong>sm</strong><small> ' . htmlspecialchars('(for tablets - screens equal to or greater than 768px wide)') . '</small><br>';
        $liste_cles .= '<strong>md</strong><small> ' . htmlspecialchars('(for small laptops - screens equal to or greater than 992px wide)') . '</small><br>';
        $liste_cles .= '<strong>lg</strong><small> ' . htmlspecialchars('(for laptops and desktops - screens equal to or greater than 1200px wide)') . '</small>';
        $liste_cles .= '</div>';
        $liste_cles .= '<div class="well">';
        $liste_cles .= htmlspecialchars('<ul class="nav nav-tabs">') . '<br>';
        $liste_cles .= '&nbsp;&nbsp;' . htmlspecialchars('<li class="active"><a data-toggle="tab" href="#menu1"></a></li>') . '<br>';
        $liste_cles .= '&nbsp;&nbsp;' . htmlspecialchars('<li><a data-toggle="tab" href="#menu2"></a></li>') . '<br>';
        $liste_cles .= htmlspecialchars('</ul>') . '<br>';
        $liste_cles .= htmlspecialchars('<div class="tab-content">') . '<br>';
        $liste_cles .= '&nbsp;&nbsp;' . htmlspecialchars('<div id="menu1" class="tab-pane fade in active"></div>') . '<br>';
        $liste_cles .= '&nbsp;&nbsp;' . htmlspecialchars('<div id="menu2" class="tab-pane fade"></div>') . '<br>';
        $liste_cles .= htmlspecialchars('</div>');
        $liste_cles .= '</div>';
        if (USE_BOOTSTRAP) {
            $retour = '<div style="height: 29px;"><div style="text-align: right; color: #aaa; margin-bottom: 5px; font-style: italic; font-weight: bold; font-family: Consolas,monaco,monospace; z-index: 999999999; position: absolute; right: 10px;">' . htmlspecialchars($filename) . " <button onclick=\"affichage_edition_gabarit_$mf_cpt_gabarit();\" style=\"padding: 2px 4px;\"> &lt; &gt; </button></div></div>";
            $retour .= '<div id="affichage_edition_gabarit_' . $mf_cpt_gabarit . '" style="display: none; position: relative; height: 800px; margin-bottom: 10px; ">';
            $retour .= '<div style="position: absolute; width: 100%; height: 820px; overflow: auto;"><table style="width: 100%; font-family: consolas, monospace; font-size: 16px;"><tr><td style="vertical-align: top; width: 75%;">';
            $retour .= "<textarea style=\"width: 99%; height: 800px; padding: 10px; background-color: #111; color: #fff; white-space: nowrap; font-size: 14px;\" id=\"id_edition_gabarit_$mf_cpt_gabarit\" onchange=\"maj_id_edition_gabarit_$mf_cpt_gabarit();\">" . htmlspecialchars($txt) . "</textarea>";
            $retour .= '</td><td style="vertical-align: top; font-size: 12px; background-color: #353535; color: wheat; padding: 5px;">';
            $retour .= "$liste_cles";
            $retour .= '</td></tr></table></div>';
            $retour .= "</div>";
            $retour .= "<div id=\"affichage_rendu_gabarit_$mf_cpt_gabarit\">" . strtr(file_get_contents("gabarits/" . $filename), $trans) . "</div>";
            $retour .= "<script type=\"text/javascript\">";
            $retour .= "let xhr_id_edition_gabarit_$mf_cpt_gabarit;";
            $retour .= "function maj_id_edition_gabarit_$mf_cpt_gabarit() {";
            $retour .= "let modif = document.getElementById(\"id_edition_gabarit_$mf_cpt_gabarit\").value;";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit = getXMLHttpRequest();";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.open(\"POST\", \"api/mf_gabarits/maj.php\", true);";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.send(\"filename=\"+encodeURIComponent('$filename')+\"&contenu=\"+encodeURIComponent(modif));";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.onreadystatechange = function() {";
            $retour .= "if ( xhr_id_edition_gabarit_$mf_cpt_gabarit.readyState == 4 && (xhr_id_edition_gabarit_$mf_cpt_gabarit.status == 200 || xhr_id_edition_gabarit_$mf_cpt_gabarit.status == 0) ) {";
            $retour .= "let retour = xhr_id_edition_gabarit_$mf_cpt_gabarit.responseText;";
            $retour .= "}";
            $retour .= "};";
            $retour .= "}";
            $retour .= "function affichage_edition_gabarit_$mf_cpt_gabarit() {";
            $retour .= "let div = document.getElementById('affichage_edition_gabarit_$mf_cpt_gabarit');";
            $retour .= "let div_2 = document.getElementById('affichage_rendu_gabarit_$mf_cpt_gabarit');";
            $retour .= "if ( div.style.display == 'none' )";
            $retour .= "{";
            $retour .= "div.style.display = 'block';";
            $retour .= "div_2.style.display = 'none';";
            $retour .= "}";
            $retour .= "else";
            $retour .= "{";
            $retour .= "div.style.display = 'none';";
            $retour .= "div_2.style.display = 'block';";
            $retour .= "document.location.href='" . $fil_ariane->get_last_lien() . "'";
            $retour .= "}";
            $retour .= "}";
            $retour .= "</script>";
        } else {
            $retour = '<div style="height: 29px;"><div style="text-align: right; color: #aaa; margin-bottom: 5px; font-style: italic; font-weight: bold; font-family: Consolas,monaco,monospace; z-index: 999999999; position: absolute; right: 10px;">' . htmlspecialchars($filename) . " <button onclick=\"affichage_edition_gabarit_$mf_cpt_gabarit();\" style=\"padding: 2px 4px;\"> &lt; &gt; </button></div></div>";
            $retour = '<div id="affichage_edition_gabarit_' . $mf_cpt_gabarit . '" style="display: none; position: relative; height: 800px;">';
            $retour = '<div style="position: absolute; width: 100%; height: 810px; overflow: auto;"><table style="width: 100%;"><tr><td style="vertical-align: top;">';
            $retour = "<textarea class=\"edition_gabarit\" id=\"id_edition_gabarit_$mf_cpt_gabarit\">" . htmlspecialchars($txt) . "</textarea>";
            $retour = "</td><td style=\"width: 260px; vertical-align: top;\">";
            $retour = "$liste_cles";
            $retour = "</td></tr></table></div>";
            $retour = "</div>";
            $retour .= "<div id=\"affichage_rendu_gabarit_$mf_cpt_gabarit\">" . strtr(file_get_contents("gabarits/" . $filename), $trans) . "</div>";
            $retour .= "<script type=\"text/javascript\">";
            $retour .= "let xhr_id_edition_gabarit_$mf_cpt_gabarit;";
            $retour .= "function maj_id_edition_gabarit_$mf_cpt_gabarit() {";
            $retour .= "let modif = document.getElementById(\"id_edition_gabarit_$mf_cpt_gabarit\").value;";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit = getXMLHttpRequest();";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.open(\"POST\", \"api/mf_gabarits/maj.php\", true);";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.send(\"filename=\"+encodeURIComponent('$filename')+\"&contenu=\"+encodeURIComponent(modif));";
            $retour .= "xhr_id_edition_gabarit_$mf_cpt_gabarit.onreadystatechange = function() {";
            $retour .= "if ( xhr_id_edition_gabarit_$mf_cpt_gabarit.readyState == 4 && (xhr_id_edition_gabarit_$mf_cpt_gabarit.status == 200 || xhr_id_edition_gabarit_$mf_cpt_gabarit.status == 0) ) {";
            $retour .= "let retour = xhr_id_edition_gabarit_$mf_cpt_gabarit.responseText;";
            $retour .= "}";
            $retour .= ";";
            $retour .= "}";
            $retour .= "function affichage_edition_gabarit_$mf_cpt_gabarit() {";
            $retour .= "let div = document.getElementById('affichage_edition_gabarit_$mf_cpt_gabarit');";
            $retour .= "let div_2 = document.getElementById('affichage_rendu_gabarit_$mf_cpt_gabarit');";
            $retour .= "if ( div.style.display == 'none' ) {";
            $retour .= "div.style.display = 'block';";
            $retour .= "div_2.style.display = 'none';";
            $retour .= "} else {";
            $retour .= "div.style.display = 'none';";
            $retour .= "div_2.style.display = 'block';";
            $retour .= "document.location.href='" . $fil_ariane->get_last_lien() . "'";
            $retour .= "}";
            $retour .= "}";
            $retour .= "</script>";
        }

        $mf_cpt_gabarit ++;
        foreach ($trans as $key => $value) {
            if (stripos($txt, $key) !== false) {
                unset($trans[$key]);
            }
        }
    }

    if ($mode_modal) {

        global $mf_script_end_modal, $mf_cpt_modal;

        $retour_modal = '';

        if (VERSION_BOOTSTRAP == 3) {
            $retour_modal .= '<!-- Modal -->';
            $retour_modal .= '<div id="formModal' . $mf_cpt_modal . '" class="modal fade" role="dialog">';
            $retour_modal .= '<div class="modal-dialog modal-lg">';

            $retour_modal .= '<!-- Modal content-->';
            $retour_modal .= '<div class="modal-content">' . $retour . '</div>';

            $retour_modal .= '</div>';
            $retour_modal .= '</div>';

            $mf_script_end_modal = '$( function() { $("#formModal' . $mf_cpt_modal . '").modal("show"); } );';
        } elseif (VERSION_BOOTSTRAP == 4) {
            $retour_modal .= '<div class="modal fade" id="formModal' . $mf_cpt_modal . '">';
            $retour_modal .= '<div class="modal-dialog modal-dialog-centered modal-lg">';

            $retour_modal .= '<div class="modal-content">' . $retour . '</div>';

            $retour_modal .= '</div>';
            $retour_modal .= '</div>';

            $mf_script_end_modal = '$( function() { $("#formModal' . $mf_cpt_modal . '").modal("show"); } );';
        }

        $retour = $retour_modal;

        $mf_cpt_modal ++;
    }

    if (! MODE_PROD) {
        global $mf_liste_gabarits;
        $infos_gabarit = [];
        $infos_gabarit[] = '    include \'C:\\wamp64\\www\\' . NOM_PROJET . '\\' . REPERTOIRE_WWW . '\\' . NOM_PROJET . '\\gabarits\\' . str_replace('/', '\\', $filename) . '\';';
        $liste_cles = lister_cles($trans);
        $txt = '';
        foreach ($liste_cles as $i => $value) {
            $txt .= ($i > 0 ? '//                  ' : '') . $value . PHP_EOL;
        }
        if ($txt != '') {
            $infos_gabarit[] = '//      Balise(s) : ' . $txt;
        } else {
            $infos_gabarit[] = '//      Sans balise';
        }
        $mf_liste_gabarits[] = $infos_gabarit;
    }

    return $retour;
}

/* Formulaire dynamique */

$num_champs_auto = 1;
$champ_maj_auto = array(); // ici sont les champs actualisés automatiquement.
$num_champs_focus = 0;

function ajouter_champ_modifiable_Bootstrap(&$liste_valeurs_cle_table, &$DB_name, &$valeur_initiale, &$nom_table, &$rafraichissement_page, &$class, &$mode_pourcentage_montant_initiale, &$type_input, &$attributs, &$maj_auto, &$titre, &$mode_formulaire, &$mise_a_jour_silencieuse, &$onchange_js)
{
    // $onchange_js .= ';$(\'.mf_attente_maj_auto\').css(\'opacity\', \'0.1\');';
    if ($rafraichissement_page) { // inutile de mettre à jour tout les champs si un rafraishissement de la page est prévu
        $mise_a_jour_silencieuse = true;
    }

    global $num_champs_auto, $lang_standard, $mf_dictionnaire_db, $fil_ariane, $mf_script_end;

    if ($nom_table == '')
        $nom_table = $mf_dictionnaire_db[$DB_name]['entite'];

    $cles = '';
    foreach ($liste_valeurs_cle_table as $key => $value) {
        if ($cles != '')
            $cles .= '&';
        $cles .= $key . '=' . $value;
    }

    $attributs_str = '';
    foreach ($attributs as $cle_atribut => $val_atribut) {
        $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
    }

    $retour = '';

    $class = ' ' . $class . ' ';
    $format_button = (stripos($class, ' button ') !== false);

    $mode_innerHTML = false;

    if ($mode_formulaire) {
        $retour .= '<div class="form-horizontal">';
        $retour .= '<div class="form-group">';
        if ($titre) {
            $retour .= '<label class="col-sm-3 control-label" for="form_dyn_' . $num_champs_auto . '">' . htmlspecialchars(get_nom_colonne($DB_name)) . '&nbsp;:</label>';
        }
        $retour .= '<div class="col-sm-' . ($titre ? 9 : 12) . '"><div class="col-xs-11" style="padding: 0;">';
    }

    if (isset($lang_standard[$DB_name . '_']) && (($mf_dictionnaire_db[$DB_name]['type'] != 'BOOL' || $format_button) || ($mf_dictionnaire_db[$DB_name]['type'] == 'BOOL' && $mode_formulaire))) // workflow ou bool
    {
        $option_vide = true;
        foreach ($lang_standard[$DB_name . '_'] as $key => $value) {
            if ($valeur_initiale == $key) {
                $option_vide = false;
            }
        }

        $retour .= '<select ' . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . ' onchange="maj_form_dyn_' . $num_champs_auto . '();' . $onchange_js . ';" id="form_dyn_' . $num_champs_auto . '" class="form_dyn_champ_ ' . $class . '"' . $attributs_str . '>' . ($option_vide ? '<option></option>' : '');
        foreach ($lang_standard[$DB_name . "_"] as $key => $value) {
            $retour .= "<option value=\"$key\"" . ($valeur_initiale == $key ? ' selected="selected"' : '') . '>' . htmlspecialchars($value) . '</option>';
        }
        $retour .= '</select>';

        if ($format_button) {
            foreach ($lang_standard[$DB_name . "_"] as $key => $value) {
                $retour .= '<button type="button" class="' . ($valeur_initiale == $key ? 'btn btn-primary' : 'btn btn-default') . '" style="margin-top: 2px; margin-bottom: 2px;" id="bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '" onclick="document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value=\'' . $key . '\'; maj_form_dyn_' . $num_champs_auto . '();' . $onchange_js . '">' . htmlspecialchars($value) . '</button> ';
            }
            $retour .= '<script type="text/javascript">';
            $retour .= 'setInterval(function(){ $v = document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value; ';
            foreach ($lang_standard[$DB_name . '_'] as $key => $value) {
                $retour .= 'if ($v=="' . $key . '") {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="btn btn-primary";} else {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="btn btn-default";}';
            }
            $retour .= ' }, 100);';
            $retour .= '</script>';
            $retour .= '<style type="text/css">' . PHP_EOL;
            $retour .= '#form_dyn_' . $num_champs_auto . ' { display: none }' . PHP_EOL;
            $retour .= '</style>' . PHP_EOL;
        }
    } else {
        if ($mf_dictionnaire_db[$DB_name]['type'] == 'TEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'TINYTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'MEDIUMTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'LONGTEXT') {
            $mode_innerHTML = true;
            $retour .= "<textarea placeholder=\"" . htmlspecialchars(get_nom_colonne($DB_name)) . "\" " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" class=\"form_dyn_champ_ $class\"$attributs_str rows=\"5\">" . htmlspecialchars($valeur_initiale) . "</textarea>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'INT') {
            if ($type_input == '')
                $type_input = 'number';
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'VARCHAR') {
            if ($type_input == '')
                $type_input = 'text';
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DOUBLE') {
            if ($type_input == '')
                $type_input = 'text';
            $retour .= "<table style=\"width: 100%;\"><tr>";
            if ($mode_pourcentage_montant_initiale !== false && $mode_pourcentage_montant_initiale != 0) {
                $retour .= "<td style=\"width: 33%;\"><input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_{$num_champs_auto}_p();\" id=\"form_dyn_{$num_champs_auto}_p\" value=\"" . round(100 * $valeur_initiale / $mode_pourcentage_montant_initiale, 2) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td><td><strong>&nbsp;%&nbsp;&nbsp;&nbsp;</strong></td>";
            }
            $retour .= "<td><input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td>";
            $retour .= "</tr></table>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DATE') {
            if ($type_input == '')
                $type_input = 'date';
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DATETIME' || $mf_dictionnaire_db[$DB_name]['type'] == 'TIMESTAMP') {
            $type_input = 'date';
            $retour .= "<table><tr><td><input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();$onchange_js;\" id=\"form_dyn_{$num_champs_auto}_date\" value=\"" . format_date($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td>";
            $type_input = 'time';
            $retour .= "<td><input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();\" id=\"form_dyn_{$num_champs_auto}_time\" value=\"" . format_time($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td></tr></table>";
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . format_datetime_local($valeur_initiale) . "\" type=\"hidden\">";
            $retour .= "<script type=\"text/javascript\">";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp() {";
            $retour .= "let date_ = document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value;";
            $retour .= "let time_ = document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value;";
            $retour .= "let v = date_ + 'T' + time_;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp_2() {";
            $retour .= "if (document.getElementById(\"form_dyn_{$num_champs_auto}_date\") !== document.activeElement && document.getElementById(\"form_dyn_{$num_champs_auto}_time\") !== document.activeElement)";
            $retour .= "{";
            $retour .= "let date_time = document.getElementById(\"form_dyn_{$num_champs_auto}\").value;";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value = date_time.substring(0, 10).replace(\"null\",\"\");";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value = date_time.substring(11).replace(\"null\",\"\");";
            $retour .= "}";
            $retour .= "}setInterval(maj_form_dyn_{$num_champs_auto}_tmp_2,100);";
            $retour .= "</script>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'TIME') {
            if ($type_input == '')
                $type_input = 'time';
            $retour .= '<input ' . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="form-control mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'BOOL') {
            if ($type_input == '')
                $type_input = 'checkbox';
            $retour .= '<input ' . ($mode_formulaire ? ' class="form-control mf_champ"' : ' class="mf_champ"') . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" " . ($valeur_initiale == 1 ? 'checked ' : '') . "type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
            $mf_dictionnaire_db[$DB_name]['spec']['checkbox'] = true;
        }
    }

    if ($mode_formulaire) {
        $retour .= '</div><div class="col-xs-1" style="padding: 0; text-align: center;"><span id="form_dyn_etat_' . $num_champs_auto . '" class="maj_dyn_etat"></span></div></div>';
        $retour .= '</div>';
        $retour .= '</div>';
    }

    $retour .= "<script type=\"text/javascript\">";
    $retour .= "let xhr_form_dyn_$num_champs_auto;";
    $retour .= "var maj_form_dyn__{$num_champs_auto}_libre=true;";
    $retour .= "var maj_form_dyn__{$num_champs_auto}_synchro_demandee=false;";
    $retour .= "function maj_form_dyn_$num_champs_auto() {";

    $retour .= "if ( maj_form_dyn__{$num_champs_auto}_libre ) {";

    $retour .= "maj_form_dyn__{$num_champs_auto}_libre=false;";
    $retour .= "nb_actions_en_cours++;";

    $retour .= ($rafraichissement_page ? '$(".mf_champ").prop( "disabled", true );' : '');

    if (isset($mf_dictionnaire_db[$DB_name]['spec']['checkbox'])) {
        $retour .= 'var modif = ( document.getElementById("form_dyn_' . $num_champs_auto . '").checked ? 1 : 0);';
        $retour .= 'var e = $(\'label[for="form_dyn_' . $num_champs_auto . '"]\');';
        $retour .= 'if (typeof e[0] != \'undefined\') {';
        $retour .= 'e[0].className=( modif==1 ? "checked" : "unchecked" );';
        $retour .= '}';

        $mf_script_end .= 'var e = $(\'label[for="form_dyn_' . $num_champs_auto . '"]\');';
        $mf_script_end .= 'if (typeof e[0] != \'undefined\') {';
        $mf_script_end .= 'e[0].className="' . ($valeur_initiale == 1 ? 'checked' : 'unchecked') . '";';
        $mf_script_end .= '}';
    } else {
        if ($mode_innerHTML) {
            $retour .= 'var modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        } else {
            $retour .= 'var modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        }
    }

    if ($mode_pourcentage_montant_initiale !== false) {
        $retour .= "var pourcentage = Math.round(10000*modif/$mode_pourcentage_montant_initiale)/100;";
        $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value = pourcentage;";
    }
    if ($mode_formulaire) {
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat on');";
    }
    $retour .= "xhr_form_dyn_$num_champs_auto = getXMLHttpRequest();";
    $retour .= "xhr_form_dyn_$num_champs_auto.open(\"POST\", \"api/$nom_table/modifier.php\", true);";
    $retour .= "xhr_form_dyn_$num_champs_auto.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");";
    $retour .= "xhr_form_dyn_$num_champs_auto.send(\"$cles&$DB_name=\"+encodeURIComponent(modif));";
    $retour .= "xhr_form_dyn_$num_champs_auto.onreadystatechange = function()";
    $retour .= '{';
    $retour .= "if ( xhr_form_dyn_$num_champs_auto.readyState == 4 && (xhr_form_dyn_$num_champs_auto.status == 200 || xhr_form_dyn_$num_champs_auto.status == 0) )";
    $retour .= '{';

    $retour .= 'nb_actions_en_cours--;';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_libre=true;';

    $retour .= 'if ( maj_form_dyn__' . $num_champs_auto . '_synchro_demandee ) {';

    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_synchro_demandee = false;';
    $retour .= 'setTimeout(maj_form_dyn_' . $num_champs_auto . ');';

    $retour .= '} else {';

    $retour .= 'try {';

    $retour .= 'var retour = JSON.parse(xhr_form_dyn_' . $num_champs_auto . '.responseText);';
    $retour .= 'if (retour.code_erreur == 0) {';
    if ($mode_formulaire) {
        $retour .= 'document.getElementById("form_dyn_etat_' . $num_champs_auto . '").setAttribute("class", "maj_dyn_etat");';
        $retour .= 'document.getElementById("form_dyn_etat_' . $num_champs_auto . '").setAttribute("title", "");';
    }
    $retour .= "" . ($rafraichissement_page ? "document.location.href='" . $fil_ariane->get_last_lien() . "'; " : "") . "";
    if (! $mise_a_jour_silencieuse) {
        $retour .= "actualiser_les_champs();";
    }
    $retour .= '} else {';

    $retour .= ($rafraichissement_page ? '$(".mf_champ").prop( "disabled", false );' : '');

    if ($mode_formulaire) {
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat ko');";
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('title', retour.message_erreur);";
    }
    $retour .= '}';

    $retour .= '} catch (e) {';

    $retour .= 'var t = document.getElementById("form_dyn_etat_' . $num_champs_auto . '");';
    $retour .= 'if (t != null) {';
    $retour .= 't.setAttribute("class", "maj_dyn_etat ko");';
    $retour .= 't.setAttribute("title", "Erreur système");';
    $retour .= '}';

    if (! MODE_PROD) {
        $retour .= 'alertModal(xhr_form_dyn_' . $num_champs_auto . '.responseText);';
    }

    $retour .= '}';

    $retour .= '}';
    $retour .= '} else if (xhr_form_dyn_' . $num_champs_auto . '.readyState!=2 && xhr_form_dyn_' . $num_champs_auto . '.readyState!=3) {';

    $retour .= 'nb_actions_en_cours--;';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_libre=true;';
    $retour .= 'console.log("erreur xhr n°" + xhr_form_dyn_' . $num_champs_auto . '.readyState);';
    $retour .= 'mf_maj_en_cours = false;';

    $retour .= '}';

    $retour .= '}';

    $retour .= '} else {';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_synchro_demandee = true;';
    $retour .= '}';

    $retour .= '}';

    if ($maj_auto) {

        global $champ_maj_auto;
        $champ_maj_auto[$nom_table][$cles][$DB_name][] = $num_champs_auto;

        if ($mode_pourcentage_montant_initiale !== false) {
            $retour .= "function maj_form_dyn_{$num_champs_auto}_p() {";
            $retour .= "var pourcentage = document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value;";
            $retour .= "var v = Math.round( $mode_pourcentage_montant_initiale * pourcentage )/100;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
        }
    }

    $retour .= '</script>';

    $num_champs_auto ++;

    return $retour;
}

function ajouter_champ_modifiable_Bootstrap_4(&$liste_valeurs_cle_table, &$DB_name, &$valeur_initiale, &$nom_table, &$rafraichissement_page, &$class, &$mode_pourcentage_montant_initiale, &$type_input, &$attributs, &$maj_auto, &$titre, &$mode_formulaire, &$mise_a_jour_silencieuse, &$onchange_js)
{
    // $onchange_js .= ';$(\'.mf_attente_maj_auto\').css(\'opacity\', \'0.1\');';
    if ($rafraichissement_page) { // inutile de mettre à jour tout les champs si un rafraishissement de la page est prévu
        $mise_a_jour_silencieuse = true;
    }

    global $num_champs_auto, $lang_standard, $mf_dictionnaire_db, $fil_ariane, $mf_script_end;

    if ($nom_table == '') {
        $nom_table = $mf_dictionnaire_db[$DB_name]['entite'];
    }

    $cles = '';
    foreach ($liste_valeurs_cle_table as $key => $value) {
        if ($cles != '')
            $cles .= '&';
        $cles .= $key . '=' . $value;
    }

    $attributs_str = '';
    foreach ($attributs as $cle_atribut => $val_atribut) {
        $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
    }

    $retour = '';

    $class = ' ' . $class . ' ';
    $format_button = (stripos($class, ' button ') !== false);
    $format_button_sm = (stripos($class, ' btn-sm ') !== false);
    $format_button_lg = (stripos($class, ' btn-lg ') !== false);

    $mode_innerHTML = false;

    if ($mode_formulaire) {
        $retour .= '<div>';
        $retour .= '<div class="form-group row">';
        if ($titre) {
            $retour .= '<label class="col-md-3 col-form-label libelle_formulaire" for="form_dyn_' . $num_champs_auto . '">' . htmlspecialchars(get_nom_colonne($DB_name)) . '&nbsp;:</label>';
        }
        $retour .= '<div class="col-md-' . ($titre ? 9 : 12) . '"><div class="row"><div class="col-11">';
    }

    if (isset($lang_standard[$DB_name . '_']) && (($mf_dictionnaire_db[$DB_name]['type'] != 'BOOL' || $format_button) || ($mf_dictionnaire_db[$DB_name]['type'] == 'BOOL' && $mode_formulaire))) // workflow ou bool
    {
        $option_vide = true;
        foreach ($lang_standard[$DB_name . '_'] as $key => $value) {
            if ($valeur_initiale == $key) {
                $option_vide = false;
            }
        }

        $retour .= '<select class="form-control mf_champ form_dyn_champ_ ' . $class . '" onchange="maj_form_dyn_' . $num_champs_auto . '();' . $onchange_js . ';" id="form_dyn_' . $num_champs_auto . '" ' . $attributs_str . '>' . ($option_vide ? '<option></option>' : '');
        foreach ($lang_standard[$DB_name . '_'] as $key => $value) {
            $retour .= "<option value=\"$key\"" . ($valeur_initiale == $key ? ' selected="selected"' : '') . '>' . htmlspecialchars($value) . '</option>';
        }
        $retour .= '</select>';

        if ($format_button) {
            foreach ($lang_standard[$DB_name . "_"] as $key => $value) {
                $retour .= '<button type="button" class="' . ($valeur_initiale == $key ? 'btn btn-primary' : 'btn btn-default') . ($format_button_sm ? ' btn-sm' : '') . ($format_button_lg ? ' btn-lg' : '') . '" style="margin-top: 2px; margin-bottom: 2px;" id="bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '" onclick="document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value=\'' . $key . '\'; maj_form_dyn_' . $num_champs_auto . '();' . $onchange_js . '">' . htmlspecialchars($value) . '</button> ';
            }
            $retour .= '<script type="text/javascript">';
            $retour .= 'setInterval(function(){ $v = document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value; ';
            foreach ($lang_standard[$DB_name . '_'] as $key => $value) {
                $retour .= 'if ($v=="' . $key . '") {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="btn btn-primary' . ($format_button_sm ? ' btn-sm' : '') . ($format_button_lg ? ' btn-lg' : '') . '";} else {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="btn btn-default' . ($format_button_sm ? ' btn-sm' : '') . ($format_button_lg ? ' btn-lg' : '') . '";}';
            }
            $retour .= ' }, 100);';
            $retour .= '</script>';
            $retour .= '<style type="text/css">' . PHP_EOL;
            $retour .= '#form_dyn_' . $num_champs_auto . ' { display: none }' . PHP_EOL;
            $retour .= '</style>' . PHP_EOL;
        }
    } else {
        if ($mf_dictionnaire_db[$DB_name]['type'] == 'TEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'TINYTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'MEDIUMTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'LONGTEXT') {
            $mode_innerHTML = true;
            $retour .= "<textarea placeholder=\"" . htmlspecialchars(get_nom_colonne($DB_name)) . "\" " . ' class="form-control mf_champ form_dyn_champ_ ' . $class . '" ' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" $attributs_str rows=\"5\">" . htmlspecialchars($valeur_initiale) . "</textarea>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'INT') {
            if ($type_input == '')
                $type_input = 'number';
            $retour .= '<input class="form-control mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'VARCHAR') {
            if ($type_input == '')
                $type_input = 'text';
            $retour .= '<input class="form-control mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DOUBLE') {
            if ($mode_pourcentage_montant_initiale !== false && $mode_pourcentage_montant_initiale != 0) {
                if ($type_input == '')
                    $type_input = 'text';
                $retour .= "<table style=\"width: 100%;\"><tr>";
                $retour .= '<td style="width: 33%;"><input class="mf_champ form-control form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_{$num_champs_auto}_p();\" id=\"form_dyn_{$num_champs_auto}_p\" value=\"" . round(100 * $valeur_initiale / $mode_pourcentage_montant_initiale, 2) . "\" type=\"$type_input\" $attributs_str></td><td><strong>&nbsp;%&nbsp;&nbsp;&nbsp;</strong></td>";
                $retour .= '<td><input class="form-control mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str></td>";
                $retour .= "</tr></table>";
            } else {
                $retour .= '<input class="form_dyn_champ_ ' . $class . ' mf_champ form-control"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\"$attributs_str>";
            }
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DATE') {
            if ($type_input == '')
                $type_input = 'date';
            $retour .= '<input class="form-control mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DATETIME' || $mf_dictionnaire_db[$DB_name]['type'] == 'TIMESTAMP') {
            $type_input = 'date';
            $retour .= '<table><tr><td><input class="form-control mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();$onchange_js;\" id=\"form_dyn_{$num_champs_auto}_date\" value=\"" . format_date($valeur_initiale) . "\" type=\"$type_input\" $attributs_str></td>";
            $type_input = 'time';
            $retour .= '<td><input class="form-control mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();\" id=\"form_dyn_{$num_champs_auto}_time\" value=\"" . format_time($valeur_initiale) . "\" type=\"$type_input\" $attributs_str></td></tr></table>";
            $retour .= '<input  class="form-control mf_champ"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . format_datetime_local($valeur_initiale) . "\" type=\"hidden\">";
            $retour .= "<script type=\"text/javascript\">";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp() {";
            $retour .= "let date_ = document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value;";
            $retour .= "let time_ = document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value;";
            $retour .= "let v = date_ + 'T' + time_;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp_2() {";
            $retour .= "if (document.getElementById(\"form_dyn_{$num_champs_auto}_date\") !== document.activeElement && document.getElementById(\"form_dyn_{$num_champs_auto}_time\") !== document.activeElement)";
            $retour .= "{";
            $retour .= "let date_time = document.getElementById(\"form_dyn_{$num_champs_auto}\").value;";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value = date_time.substring(0, 10).replace(\"null\",\"\");";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value = date_time.substring(11).replace(\"null\",\"\");";
            $retour .= "}";
            $retour .= "}setInterval(maj_form_dyn_{$num_champs_auto}_tmp_2,100);";
            $retour .= "</script>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'TIME') {
            if ($type_input == '')
                $type_input = 'time';
            $retour .= '<input class="form-control mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'BOOL') {
            if ($type_input == '')
                $type_input = 'checkbox';
            $retour .= '<input class="' . ($mode_formulaire ? 'form-control ' : '') . 'mf_champ form_dyn_champ_ ' . $class . '"' . " onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" " . ($valeur_initiale == 1 ? 'checked ' : '') . "type=\"$type_input\" $attributs_str>";
            $mf_dictionnaire_db[$DB_name]['spec']['checkbox'] = true;
        }
    }

    if ($mode_formulaire) {
        $retour .= '</div><div class="col-1" style="padding: 0; text-align: center;"><span id="form_dyn_etat_' . $num_champs_auto . '" class="maj_dyn_etat"></span></div></div></div>';
        $retour .= '</div>';
        $retour .= '</div>';
    }

    $retour .= "<script type=\"text/javascript\">";
    $retour .= "let xhr_form_dyn_$num_champs_auto;";
    $retour .= "let maj_form_dyn__{$num_champs_auto}_libre=true;";
    $retour .= "let maj_form_dyn__{$num_champs_auto}_synchro_demandee=false;";
    $retour .= "function maj_form_dyn_$num_champs_auto() {";

    $retour .= "if ( maj_form_dyn__{$num_champs_auto}_libre ) {";

    $retour .= "maj_form_dyn__{$num_champs_auto}_libre=false;";
    $retour .= "nb_actions_en_cours++;";

    $retour .= ($rafraichissement_page ? '$(".mf_champ").prop( "disabled", true );' : '');

    if (isset($mf_dictionnaire_db[$DB_name]['spec']['checkbox'])) {
        $retour .= 'let modif = ( document.getElementById("form_dyn_' . $num_champs_auto . '").checked ? 1 : 0);';
        $retour .= 'let e = $(\'label[for="form_dyn_' . $num_champs_auto . '"]\');';
        $retour .= 'if (typeof e[0] != \'undefined\') {';
        $retour .= 'e[0].className=( modif==1 ? "checked" : "unchecked" );';
        $retour .= '}';

        $mf_script_end .= 'let e = $(\'label[for="form_dyn_' . $num_champs_auto . '"]\');';
        $mf_script_end .= 'if (typeof e[0] != \'undefined\') {';
        $mf_script_end .= 'e[0].className="' . ($valeur_initiale == 1 ? 'checked' : 'unchecked') . '";';
        $mf_script_end .= '}';
    } else {
        if ($mode_innerHTML) {
            $retour .= 'let modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        } else {
            $retour .= 'let modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        }
    }

    if ($mode_pourcentage_montant_initiale !== false) {
        $retour .= "let pourcentage = Math.round(10000*modif/$mode_pourcentage_montant_initiale)/100;
                           document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value = pourcentage;";
    }
    if ($mode_formulaire) {
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat on');";
    }
    $retour .= "xhr_form_dyn_$num_champs_auto = getXMLHttpRequest();";
    $retour .= "xhr_form_dyn_$num_champs_auto.open(\"POST\", \"api/$nom_table/modifier.php\", true);";
    $retour .= "xhr_form_dyn_$num_champs_auto.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");";
    $retour .= "xhr_form_dyn_$num_champs_auto.send(\"$cles&$DB_name=\"+encodeURIComponent(modif));";
    $retour .= "xhr_form_dyn_$num_champs_auto.onreadystatechange = function()";
    $retour .= '{';

    $retour .= "if ( xhr_form_dyn_$num_champs_auto.readyState == 4 && (xhr_form_dyn_$num_champs_auto.status == 200 || xhr_form_dyn_$num_champs_auto.status == 0) )";
    $retour .= '{';

    $retour .= 'nb_actions_en_cours--;';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_libre=true;';

    $retour .= 'if ( maj_form_dyn__' . $num_champs_auto . '_synchro_demandee )';
    $retour .= '{';

    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_synchro_demandee = false;';
    $retour .= 'setTimeout(maj_form_dyn_' . $num_champs_auto . ');';

    $retour .= '}';
    $retour .= 'else';
    $retour .= '{';

    $retour .= 'try {';

    $retour .= 'let retour = JSON.parse(xhr_form_dyn_' . $num_champs_auto . '.responseText);';
    $retour .= 'if ( retour.code_erreur == 0 ) {';
    if ($mode_formulaire) {
        $retour .= 'document.getElementById("form_dyn_etat_' . $num_champs_auto . '").setAttribute("class", "maj_dyn_etat");';
        $retour .= 'document.getElementById("form_dyn_etat_' . $num_champs_auto . '").setAttribute("title", "");';
    }
    $retour .= "" . ($rafraichissement_page ? "document.location.href='" . $fil_ariane->get_last_lien() . "'; " : "") . "";
    if (! $mise_a_jour_silencieuse) {
        $retour .= "actualiser_les_champs();";
    }
    $retour .= '}';
    $retour .= 'else';
    $retour .= '{';

    $retour .= ($rafraichissement_page ? '$(".mf_champ").prop( "disabled", false );' : '');

    if ($mode_formulaire) {
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat ko');";
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('title', retour.message_erreur);";
    }
    $retour .= '}';

    $retour .= '} catch (e) {';

    $retour .= 'let t = document.getElementById("form_dyn_etat_' . $num_champs_auto . '");';
    $retour .= 'if (t!=null) {';
    $retour .= 't.setAttribute("class", "maj_dyn_etat ko");';
    $retour .= 't.setAttribute("title", "Erreur système");';
    $retour .= '}';

    if (! MODE_PROD) {
        $retour .= 'alertModal(xhr_form_dyn_' . $num_champs_auto . '.responseText);';
    }

    $retour .= '}';

    $retour .= '}';

    $retour .= '}';
    $retour .= 'else if (xhr_form_dyn_' . $num_champs_auto . '.readyState!=2 && xhr_form_dyn_' . $num_champs_auto . '.readyState!=3)';
    $retour .= '{';

    $retour .= 'nb_actions_en_cours--;';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_libre=true;';
    $retour .= 'console.log("erreur xhr n°" + xhr_form_dyn_' . $num_champs_auto . '.readyState);';
    $retour .= 'mf_maj_en_cours = false;';

    $retour .= '}';

    $retour .= '}';

    $retour .= '}';
    $retour .= 'else';
    $retour .= '{';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_synchro_demandee = true;';
    $retour .= '}';

    $retour .= '}';

    if ($maj_auto) {

        global $champ_maj_auto;
        $champ_maj_auto[$nom_table][$cles][$DB_name][] = $num_champs_auto;

        if ($mode_pourcentage_montant_initiale !== false) {
            $retour .= "function maj_form_dyn_{$num_champs_auto}_p() {";
            $retour .= "let pourcentage = document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value;";
            $retour .= "let v = Math.round( $mode_pourcentage_montant_initiale * pourcentage )/100;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
        }
    }

    $retour .= '</script>';

    $num_champs_auto ++;

    return $retour;
}

/**
 * Ajout d'un champ modifiable
 *
 * @param
 *            [<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'liste_valeurs_cle_table' => array('Code_...' => $Code..., ...) <br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'DB_name' => « nom de la colonne »<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'valeur_initiale' => « valeur initiale »<br>
 *            // les champs suivant sont optionnels<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'nom_table' => « nom de la table sur lequel la colonne est rattachée »<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'rafraichissement_page' => <b>boolean</b> rafraichissement à la validation du changement de valeur<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'class' => « classes html »<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'mode_pourcentage_montant_initiale' => <b>boolean</b> Active le mode pourcentage <br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'type_input' => Type input HTML<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'attributs' => Attribut HTML array('atr1' => 'val1', ...)<br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'maj_auto' => <b>boolean</b> <br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'titre' => <b></b><br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'mode_formulaire' => <br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'mise_a_jour_silencieuse' => <br>
 *            &nbsp;&nbsp;&nbsp;&nbsp;'onchange_js' => ajout du code JS dans la palise onchange="..."<br>
 *            ]
 * @return string code html à intégrer
 */
function ajouter_champ_modifiable_interface($parametres)
{
    $liste_valeurs_cle_table = $parametres['liste_valeurs_cle_table'];
    $DB_name = $parametres['DB_name'];
    $valeur_initiale = $parametres['valeur_initiale'];

    $nom_table = isset($parametres['nom_table']) ? $parametres['nom_table'] : '';
    $rafraichissement_page = isset($parametres['rafraichissement_page']) ? $parametres['rafraichissement_page'] : false;
    $class = isset($parametres['class']) ? $parametres['class'] : '';
    $mode_pourcentage_montant_initiale = isset($parametres['mode_pourcentage_montant_initiale']) ? $parametres['mode_pourcentage_montant_initiale'] : false;
    $type_input = isset($parametres['type_input']) ? $parametres['type_input'] : '';
    $attributs = isset($parametres['attributs']) ? $parametres['attributs'] : array();
    $maj_auto = isset($parametres['maj_auto']) ? $parametres['maj_auto'] : true;
    $titre = isset($parametres['titre']) ? $parametres['titre'] : true;
    $mode_formulaire = isset($parametres['mode_formulaire']) ? $parametres['mode_formulaire'] : true;
    $mise_a_jour_silencieuse = isset($parametres['mise_a_jour_silencieuse']) ? $parametres['mise_a_jour_silencieuse'] : false;
    $onchange_js = isset($parametres['onchange_js']) ? $parametres['onchange_js'] : '';

    return ajouter_champ_modifiable($liste_valeurs_cle_table, $DB_name, $valeur_initiale, $nom_table, $rafraichissement_page, $class, $mode_pourcentage_montant_initiale, $type_input, $attributs, $maj_auto, $titre, $mode_formulaire, $mise_a_jour_silencieuse, $onchange_js);
}

/**
 *
 * @param array $parametres<br>
 *            Obligatoires :<br>
 *            - name : nom du champ<br>
 *            - valeur_initiale : valeur de chargement<br>
 *            - type : VARCHAR, INT, FLOAT, DOUBLE, ... : type équivelent sql.<br>
 *            Optionnels :<br>
 *            - mode_formulaire : booléen
 *            - maj_auto : booléen
 * @return string
 */
function ajouter_champ_session_modifiable_interface(array $parametres)
{
    global $num_champs_auto, $lang_standard, $mf_dictionnaire_db, $fil_ariane, $mf_script_end;

    $name = $parametres['name'];
    if (! isset($_SESSION[PREFIXE_SESSION]['parametres'][$name])) {
        mf_set_value_session($name, $parametres['valeur_initiale']);
    }
    $valeur_initiale = $_SESSION[PREFIXE_SESSION]['parametres'][$name];
    $mf_dictionnaire_db[$name]['type'] = $parametres['type']; // Eq base de données

    $rafraichissement_page = isset($parametres['rafraichissement_page']) ? $parametres['rafraichissement_page'] : false;
    $class = isset($parametres['class']) ? $parametres['class'] : '';
    $mode_pourcentage_montant_initiale = isset($parametres['mode_pourcentage_montant_initiale']) ? $parametres['mode_pourcentage_montant_initiale'] : false;
    $type_input = isset($parametres['type_input']) ? $parametres['type_input'] : '';
    $attributs = isset($parametres['attributs']) ? $parametres['attributs'] : array();
    $maj_auto = isset($parametres['maj_auto']) ? $parametres['maj_auto'] : true;
    $maj_auto_key = isset($parametres['maj_auto_key']) ? $parametres['maj_auto_key'] : false;
    $onchange_js = isset($parametres['onchange_js']) ? $parametres['onchange_js'] : '';
    $js_before = isset($parametres['js_before']) ? $parametres['js_before'] : true;
    $titre = isset($parametres['titre']) ? $parametres['titre'] : true;
    $mode_formulaire = isset($parametres['mode_formulaire']) ? $parametres['mode_formulaire'] : true;
    $mise_a_jour_silencieuse = isset($parametres['mise_a_jour_silencieuse']) ? $parametres['mise_a_jour_silencieuse'] : false;
    $htmlspecialchars_select = isset($parametres['htmlspecialchars_select']) ? $parametres['htmlspecialchars_select'] : true;

    // $onchange_js .= ';$(\'.mf_attente_maj_auto\').css(\'opacity\', \'0.1\');';

    $cles = 'name=' . $name;

    if ($rafraichissement_page) { // inutile de mettre à jour tout les champs si un rafraishissement de la page est prévu
        $mise_a_jour_silencieuse = true;
    }

    $attributs_str = '';
    foreach ($attributs as $cle_atribut => $val_atribut) {
        $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
    }

    $retour = '';

    $retour .= '<script type="text/javascript">';
    $retour .= 'let maj_form_dyn_add_js_' . $num_champs_auto . ' = "' . $onchange_js . '";';
    $retour .= '</script>';

    $class = ' ' . $class . ' ';
    $format_button = (stripos($class, ' button ') !== false);

    $mode_innerHTML = false;

    if ($mode_formulaire) {
        $retour .= '<div>';
        $retour .= '<div class="form-group row">';
        if ($titre) {
            $retour .= '<label class="col-md-3 col-form-label libelle_formulaire" for="form_dyn_' . $num_champs_auto . '">' . htmlspecialchars(get_nom_colonne($name)) . '&nbsp;:</label>';
        }
        $retour .= '<div class="col-md-' . ($titre ? 9 : 12) . '"><div class="row"><div class="col-11">';
    }

    if (isset($lang_standard[$name . '_']) && (($mf_dictionnaire_db[$name]['type'] != 'BOOL' || $format_button) || ($mf_dictionnaire_db[$name]['type'] == 'BOOL' && $mode_formulaire))) // workflow ou bool
    {
        $option_vide = true;
        foreach ($lang_standard[$name . '_'] as $key => $value) {
            if ($valeur_initiale == $key) {
                $option_vide = false;
            }
        }

        $retour .= '<select ' . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . ' onchange="maj_form_dyn_' . $num_champs_auto . '();" id="form_dyn_' . $num_champs_auto . '" ' . $attributs_str . '>' . ($option_vide ? '<option></option>' : '');
        foreach ($lang_standard[$name . '_'] as $key => $value) {
            $retour .= "<option value=\"$key\"" . ($valeur_initiale == $key ? ' selected="selected"' : '') . '>' . ($htmlspecialchars_select ? htmlspecialchars($value) : $value) . '</option>';
        }
        $retour .= '</select>';

        if ($format_button) {
            foreach ($lang_standard[$name . "_"] as $key => $value) {
                $retour .= '<button type="button" class="' . ($valeur_initiale == $key ? 'btn btn-primary' : 'btn btn-default') . '" style="margin-top: 2px; margin-bottom: 2px;" id="bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '" onclick="document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value=\'' . $key . '\'; maj_form_dyn_' . $num_champs_auto . '();">' . ($htmlspecialchars_select ? htmlspecialchars($value) : $value) . '</button> ';
            }
            $retour .= '<script type="text/javascript">';
            $retour .= 'setInterval(function(){ $v = document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value; ';
            foreach ($lang_standard[$name . '_'] as $key => $value) {
                $retour .= 'if ($v=="' . $key . '") {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="btn btn-primary";} else {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="btn btn-default";}';
            }
            $retour .= ' }, 100);';
            $retour .= '</script>';
            $retour .= '<style type="text/css">' . PHP_EOL;
            $retour .= '#form_dyn_' . $num_champs_auto . ' { display: none }' . PHP_EOL;
            $retour .= '</style>' . PHP_EOL;
        }
    } else {
        $declencheur_maj = ($maj_auto_key ? ' onkeyup="maj_form_dyn_' . $num_champs_auto . '();"' : ' onchange="maj_form_dyn_' . $num_champs_auto . '();"');
        if ($mf_dictionnaire_db[$name]['type'] == 'TEXT' || $mf_dictionnaire_db[$name]['type'] == 'TINYTEXT' || $mf_dictionnaire_db[$name]['type'] == 'MEDIUMTEXT' || $mf_dictionnaire_db[$name]['type'] == 'LONGTEXT') {
            $mode_innerHTML = true;
            $retour .= '<textarea placeholder="' . htmlspecialchars(get_nom_colonne($name)) . '" ' . ($mode_formulaire ? ' class="form-control' . $class . 'mf_champ form_dyn_champ_"' : ' class="form-control' . $class . 'mf_champ form_dyn_champ_"') . $declencheur_maj . ' id="form_dyn_' . $num_champs_auto . '" ' . $attributs_str . ' rows="5">' . htmlspecialchars($valeur_initiale) . '</textarea>';
        } elseif ($mf_dictionnaire_db[$name]['type'] == 'INT') {
            if ($type_input == '')
                $type_input = 'number';
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . "$declencheur_maj id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$name]['type'] == 'VARCHAR') {
            if ($type_input == '')
                $type_input = 'text';
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . "$declencheur_maj id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$name]['type'] == 'DOUBLE') {
            if ($type_input == '')
                $type_input = 'text';
            $retour .= "<table style=\"width: 100%;\"><tr>";
            if ($mode_pourcentage_montant_initiale !== false && $mode_pourcentage_montant_initiale != 0) {
                $retour .= "<td style=\"width: 33%;\"><input " . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . " onchange=\"maj_form_dyn_{$num_champs_auto}_p();\" id=\"form_dyn_{$num_champs_auto}_p\" value=\"" . round(100 * $valeur_initiale / $mode_pourcentage_montant_initiale, 2) . "\" type=\"$type_input\" $attributs_str></td><td><strong>&nbsp;%&nbsp;&nbsp;&nbsp;</strong></td>";
            }
            $retour .= '<td><input ' . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . "$declencheur_maj id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str></td>";
            $retour .= '</tr></table>';
        } elseif ($mf_dictionnaire_db[$name]['type'] == 'DATE') {
            if ($type_input == '')
                $type_input = 'date';
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . "$declencheur_maj id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$name]['type'] == 'DATETIME' || $mf_dictionnaire_db[$name]['type'] == 'TIMESTAMP') {
            $type_input = 'date';
            $retour .= "<table><tr><td><input " . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . " onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();\" id=\"form_dyn_{$num_champs_auto}_date\" value=\"" . format_date($valeur_initiale) . "\" type=\"$type_input\" $attributs_str></td>";
            $type_input = 'time';
            $retour .= "<td><input " . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . " onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();\" id=\"form_dyn_{$num_champs_auto}_time\" value=\"" . format_time($valeur_initiale) . "\" type=\"$type_input\" $attributs_str></td></tr></table>";
            $retour .= "<input " . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ"' : ' class="form-control' . $class . ' mf_champ"') . "$declencheur_maj id=\"form_dyn_$num_champs_auto\" value=\"" . format_datetime_local($valeur_initiale) . "\" type=\"hidden\">";
            $retour .= "<script type=\"text/javascript\">";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp() {";
            $retour .= "var date_ = document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value;";
            $retour .= "var time_ = document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value;";
            $retour .= "var v = date_ + 'T' + time_;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp_2() {";
            $retour .= "if (document.getElementById(\"form_dyn_{$num_champs_auto}_date\") !== document.activeElement && document.getElementById(\"form_dyn_{$num_champs_auto}_time\") !== document.activeElement)";
            $retour .= "{";
            $retour .= "var date_time = document.getElementById(\"form_dyn_{$num_champs_auto}\").value;";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value = date_time.substring(0, 10).replace(\"null\",\"\");";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value = date_time.substring(11).replace(\"null\",\"\");";
            $retour .= "}";
            $retour .= "}setInterval(maj_form_dyn_{$num_champs_auto}_tmp_2,100);";
            $retour .= "</script>";
        } elseif ($mf_dictionnaire_db[$name]['type'] == 'TIME') {
            if ($type_input == '')
                $type_input = 'time';
            $retour .= '<input ' . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="form-control' . $class . ' mf_champ form_dyn_champ_"') . "$declencheur_maj id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" $attributs_str>";
        } elseif ($mf_dictionnaire_db[$name]['type'] == 'BOOL') {
            if ($type_input == '')
                $type_input = 'checkbox';
            $retour .= '<input ' . ($mode_formulaire ? ' class="form-control' . $class . ' mf_champ form_dyn_champ_"' : ' class="' . $class . ' mf_champ form_dyn_champ_"') . "$declencheur_maj id=\"form_dyn_$num_champs_auto\" " . ($valeur_initiale == 1 ? 'checked ' : '') . "type=\"$type_input\" $attributs_str>";
            $mf_dictionnaire_db[$name]['spec']['checkbox'] = true;
        }
    }

    if ($mode_formulaire) {
        $retour .= '</div><div class="col-1" style="padding: 0; text-align: center;"><span id="form_dyn_etat_' . $num_champs_auto . '" class="maj_dyn_etat"></span></div></div></div>';
        $retour .= '</div>';
        $retour .= '</div>';
    }

    $retour .= "<script type=\"text/javascript\">";
    $retour .= "var xhr_form_dyn_$num_champs_auto;";
    $retour .= "var maj_form_dyn__{$num_champs_auto}_libre=true;";
    $retour .= "var maj_form_dyn__{$num_champs_auto}_synchro_demandee=false;";
    $retour .= "function maj_form_dyn_$num_champs_auto() {";

    $retour .= "if (maj_form_dyn__{$num_champs_auto}_libre) {";

    $retour .= "maj_form_dyn__{$num_champs_auto}_libre = false;";
    $retour .= "nb_actions_en_cours ++;" . PHP_EOL;

    if ($js_before) {
        $retour .= 'eval(maj_form_dyn_add_js_' . $num_champs_auto . ');' . PHP_EOL;
    }

    $retour .= ($rafraichissement_page ? '$(".mf_champ").prop("disabled", true);' : '');

    if (isset($mf_dictionnaire_db[$name]['spec']['checkbox'])) {
        $retour .= 'var modif = ( document.getElementById("form_dyn_' . $num_champs_auto . '").checked ? 1 : 0);';
        $retour .= 'var e = $(\'label[for="form_dyn_' . $num_champs_auto . '"]\');';
        $retour .= 'if (typeof e[0] != \'undefined\') {';
        $retour .= 'e[0].className=( modif==1 ? "checked" : "unchecked" );';
        $retour .= '}';

        $mf_script_end .= 'var e = $(\'label[for="form_dyn_' . $num_champs_auto . '"]\');';
        $mf_script_end .= 'if (typeof e[0] != \'undefined\') {';
        $mf_script_end .= 'e[0].className="' . ($valeur_initiale == 1 ? 'checked' : 'unchecked') . '";';
        $mf_script_end .= '}';
    } else {
        if ($mode_innerHTML) {
            $retour .= 'var modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        } else {
            $retour .= 'var modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        }
    }

    if ($mode_pourcentage_montant_initiale !== false) {
        $retour .= "var pourcentage = Math.round(10000*modif/$mode_pourcentage_montant_initiale)/100;
                           document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value = pourcentage;";
    }
    if ($mode_formulaire) {
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat on');";
    }
    $retour .= "xhr_form_dyn_$num_champs_auto = getXMLHttpRequest();";
    $retour .= "xhr_form_dyn_$num_champs_auto.open(\"POST\", \"api/mf_session/modifier.php\", true);";
    $retour .= "xhr_form_dyn_$num_champs_auto.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");";
    $retour .= "xhr_form_dyn_$num_champs_auto.send(\"$cles&$name=\"+encodeURIComponent(modif));";
    $retour .= "xhr_form_dyn_$num_champs_auto.onreadystatechange = function()";
    $retour .= '{';

    $retour .= "if ( xhr_form_dyn_$num_champs_auto.readyState == 4 && (xhr_form_dyn_$num_champs_auto.status == 200 || xhr_form_dyn_$num_champs_auto.status == 0) )";
    $retour .= '{';

    $retour .= 'nb_actions_en_cours--;';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_libre=true;';

    $retour .= 'if ( maj_form_dyn__' . $num_champs_auto . '_synchro_demandee )';
    $retour .= '{';

    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_synchro_demandee = false;';
    $retour .= 'setTimeout(maj_form_dyn_' . $num_champs_auto . ');';

    $retour .= '}';
    $retour .= 'else';
    $retour .= '{';

    $retour .= 'try {';

    $retour .= 'var retour = JSON.parse(xhr_form_dyn_' . $num_champs_auto . '.responseText);';
    $retour .= 'if ( retour.code_erreur == 0 )';
    $retour .= '{';
    if ($mode_formulaire) {
        $retour .= 'document.getElementById("form_dyn_etat_' . $num_champs_auto . '").setAttribute("class", "maj_dyn_etat");';
        $retour .= 'document.getElementById("form_dyn_etat_' . $num_champs_auto . '").setAttribute("title", "");';
    }
    $retour .= ($rafraichissement_page ? "document.location.href='" . $fil_ariane->get_last_lien() . "'; " : "") . PHP_EOL;

    if (! $js_before) {
        $retour .= 'eval(maj_form_dyn_add_js_' . $num_champs_auto . ');' . PHP_EOL;
    }

    if (! $mise_a_jour_silencieuse) {
        $retour .= "actualiser_les_champs();";
    }

    $retour .= '}';
    $retour .= 'else';
    $retour .= '{';

    $retour .= ($rafraichissement_page ? '$(".mf_champ").prop( "disabled", false );' : '');

    if ($mode_formulaire) {
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat ko');";
        $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('title', retour.message_erreur);";
    }
    $retour .= '}';

    $retour .= '} catch (e) {';

    $retour .= 'var t = document.getElementById("form_dyn_etat_' . $num_champs_auto . '");';
    $retour .= 'if (t!=null) {';
    $retour .= 't.setAttribute("class", "maj_dyn_etat ko");';
    $retour .= 't.setAttribute("title", "Erreur système");';
    $retour .= '}';

    if (! MODE_PROD) {
        $retour .= 'alertModal(xhr_form_dyn_' . $num_champs_auto . '.responseText);';
    }

    $retour .= '}';

    $retour .= '}';

    $retour .= '}';
    $retour .= 'else if (xhr_form_dyn_' . $num_champs_auto . '.readyState!=2 && xhr_form_dyn_' . $num_champs_auto . '.readyState!=3)';
    $retour .= '{';

    $retour .= 'nb_actions_en_cours--;';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_libre=true;';
    $retour .= 'console.log("erreur xhr n°" + xhr_form_dyn_' . $num_champs_auto . '.readyState);';
    $retour .= 'mf_maj_en_cours = false;';

    $retour .= '}';

    $retour .= '}';

    $retour .= '}';
    $retour .= 'else';
    $retour .= '{';
    $retour .= 'maj_form_dyn__' . $num_champs_auto . '_synchro_demandee = true;';
    $retour .= '}';

    $retour .= '}';

    if ($maj_auto) {

        global $champ_maj_auto;
        $champ_maj_auto['mf_session'][$cles][$name][] = $num_champs_auto;

        if ($mode_pourcentage_montant_initiale !== false) {
            $retour .= "function maj_form_dyn_{$num_champs_auto}_p() {";
            $retour .= "var pourcentage = document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value;";
            $retour .= "var v = Math.round( $mode_pourcentage_montant_initiale * pourcentage )/100;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
        }
    }

    $retour .= '</script>';

    $num_champs_auto ++;

    return $retour;
}

function ajouter_champ_modifiable($liste_valeurs_cle_table, $DB_name, $valeur_initiale, $nom_table = '', $rafraichissement_page = false, $class = '', $mode_pourcentage_montant_initiale = false, $type_input = '', $attributs = array(), $maj_auto = true, $titre = true, $mode_formulaire = true, $mise_a_jour_silencieuse = false, $onchange_js = '')
{
    if (! MODE_PROD) {
        if ($nom_table == '' && substr($DB_name, 0, 5) == 'Code_') {
            echo '<strong>Attention : </strong> la table devrait être définie pour le champ modifiable "<strong>' . $DB_name . '</strong>"<br>';
        }
    }

    if (USE_BOOTSTRAP) {
        if (VERSION_BOOTSTRAP == 4) {
            return ajouter_champ_modifiable_Bootstrap_4($liste_valeurs_cle_table, $DB_name, $valeur_initiale, $nom_table, $rafraichissement_page, $class, $mode_pourcentage_montant_initiale, $type_input, $attributs, $maj_auto, $titre, $mode_formulaire, $mise_a_jour_silencieuse, $onchange_js);
        } else {
            return ajouter_champ_modifiable_Bootstrap($liste_valeurs_cle_table, $DB_name, $valeur_initiale, $nom_table, $rafraichissement_page, $class, $mode_pourcentage_montant_initiale, $type_input, $attributs, $maj_auto, $titre, $mode_formulaire, $mise_a_jour_silencieuse, $onchange_js);
        }
    }

    global $num_champs_auto, $lang_standard, $mf_dictionnaire_db, $fil_ariane;

    if ($nom_table == '') {
        $nom_table = $mf_dictionnaire_db[$DB_name]['entite'];
    }

    $cles = '';
    foreach ($liste_valeurs_cle_table as $key => $value) {
        if ($cles != '')
            $cles .= '&';
        $cles .= "$key=$value";
    }

    $attributs_str = '';
    foreach ($attributs as $cle_atribut => $val_atribut) {
        $attributs_str .= ' ' . $cle_atribut . '="' . $val_atribut . '"';
    }

    $retour = '<span class="form_dyn_bloc' . ($mf_dictionnaire_db[$DB_name]['type'] == 'TEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'TINYTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'MEDIUMTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'LONGTEXT' ? ' textarea' : '') . ($mf_dictionnaire_db[$DB_name]['type'] == 'DATE' || $mf_dictionnaire_db[$DB_name]['type'] == 'TIME' ? ' largeur_fixe' : '') . '"' . $attributs_str . '><span class="form_dyn_champ">';

    $class = ' ' . $class . ' ';
    $format_button = (stripos($class, ' button ') !== false);

    $mode_innerHTML = false;

    if (isset($lang_standard[$DB_name . "_"]) && ($mf_dictionnaire_db[$DB_name]['type'] != 'BOOL' || $format_button)) // workflow ou bool
    {
        $option_vide = true;
        foreach ($lang_standard[$DB_name . "_"] as $key => $value) {
            if ($valeur_initiale == $key) {
                $option_vide = false;
            }
        }
        $retour .= "<select onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" class=\"form_dyn_champ_ $class\"$attributs_str>" . ($option_vide ? "<option></option>" : "");
        foreach ($lang_standard[$DB_name . "_"] as $key => $value) {
            $retour .= "<option value=\"$key\"" . ($valeur_initiale == $key ? " selected=\"selected\"" : '') . ">" . htmlspecialchars($value) . "</option>";
        }
        $retour .= "</select>";

        if ($format_button) {
            foreach ($lang_standard[$DB_name . "_"] as $key => $value) {
                $retour .= '<button type="button" class="' . ($valeur_initiale == $key ? 'btn btn-primary' : 'btn btn-default') . '" id="bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '" onclick="document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value=\'' . $key . '\'; maj_form_dyn_' . $num_champs_auto . '();' . $onchange_js . '">' . htmlspecialchars($value) . '</button> ';
            }
            $retour .= '<script type="text/javascript">';
            $retour .= 'setInterval(function(){ $v = document.getElementById(\'form_dyn_' . $num_champs_auto . '\').value; ';
            foreach ($lang_standard[$DB_name . '_'] as $key => $value) {
                $retour .= 'if ($v=="' . $key . '") {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="bouton_mobile actif";} else {document.getElementById(\'bouton_mobile_auto_' . $num_champs_auto . '_' . $key . '\').className="bouton_mobile";}';
            }
            $retour .= ' }, 100);';
            $retour .= '</script>';
            $retour .= '<style type="text/css">' . PHP_EOL;
            $retour .= '#form_dyn_' . $num_champs_auto . ' { display: none }' . PHP_EOL;
            $retour .= '</style>' . PHP_EOL;
        }
    } else {
        if ($mf_dictionnaire_db[$DB_name]['type'] == 'TEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'TINYTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'MEDIUMTEXT' || $mf_dictionnaire_db[$DB_name]['type'] == 'LONGTEXT') {
            $mode_innerHTML = true;
            $retour .= "<textarea placeholder=\"" . htmlspecialchars(get_nom_colonne($DB_name)) . "\" onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" class=\"form_dyn_champ_ $class\"$attributs_str>" . htmlspecialchars($valeur_initiale) . "</textarea>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'INT') {
            if ($type_input == '')
                $type_input = 'number';
            $retour .= "<input onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'VARCHAR') {
            if ($type_input == '')
                $type_input = 'text';
            $retour .= "<input onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DOUBLE') {
            if ($type_input == '')
                $type_input = 'text';
            $retour .= "<table style=\"width: 100%;\"><tr>";
            if ($mode_pourcentage_montant_initiale !== false && $mode_pourcentage_montant_initiale != 0) {
                $retour .= "<td style=\"width: 33%;\"><input onchange=\"maj_form_dyn_{$num_champs_auto}_p();\" id=\"form_dyn_{$num_champs_auto}_p\" value=\"" . round(100 * $valeur_initiale / $mode_pourcentage_montant_initiale, 2) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td><td><strong>&nbsp;%&nbsp;&nbsp;&nbsp;</strong></td>";
            }
            $retour .= "<td><input onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td>";
            $retour .= "</tr></table>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DATE') {
            if ($type_input == '')
                $type_input = 'date';
            $retour .= "<input onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'DATETIME' || $mf_dictionnaire_db[$DB_name]['type'] == 'TIMESTAMP') {
            $type_input = 'date';
            $retour .= "<table><tr><td><input onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();\" id=\"form_dyn_{$num_champs_auto}_date\" value=\"" . format_date($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td>";
            $type_input = 'time';
            $retour .= "<td><input onchange=\"maj_form_dyn_{$num_champs_auto}_tmp();\" id=\"form_dyn_{$num_champs_auto}_time\" value=\"" . format_time($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str></td></tr></table>";
            $retour .= "<input onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . format_datetime_local($valeur_initiale) . "\" type=\"hidden\">";
            $retour .= "<script type=\"text/javascript\">";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp() {";
            $retour .= "var date_ = document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value;";
            $retour .= "var time_ = document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value;";
            $retour .= "var v = date_ + 'T' + time_;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
            $retour .= "function maj_form_dyn_{$num_champs_auto}_tmp_2() {";
            $retour .= "if (document.getElementById(\"form_dyn_{$num_champs_auto}_date\") !== document.activeElement && document.getElementById(\"form_dyn_{$num_champs_auto}_time\") !== document.activeElement)";
            $retour .= "{";
            $retour .= "var date_time = document.getElementById(\"form_dyn_{$num_champs_auto}\").value;";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_date\").value = date_time.substring(0, 10);";
            $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_time\").value = date_time.substring(11);";
            $retour .= "}";
            $retour .= "}setInterval(maj_form_dyn_{$num_champs_auto}_tmp_2,100);";
            $retour .= "</script>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'TIME') {
            if ($type_input == '') {
                $type_input = 'time';
            }
            $retour .= "<input onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" value=\"" . htmlspecialchars($valeur_initiale) . "\" type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
        } elseif ($mf_dictionnaire_db[$DB_name]['type'] == 'BOOL') {
            if ($type_input == '') {
                $type_input = 'checkbox';
            }
            $retour .= "<input onchange=\"maj_form_dyn_$num_champs_auto();$onchange_js;\" id=\"form_dyn_$num_champs_auto\" " . ($valeur_initiale == 1 ? 'checked ' : '') . "type=\"$type_input\" class=\"form_dyn_champ_ $class\"$attributs_str>";
            $mf_dictionnaire_db[$DB_name]['spec']['checkbox'] = true;
        }
    }

    $retour .= "</span><span id=\"form_dyn_etat_$num_champs_auto\" class=\"maj_dyn_etat\"></span></span>";

    $retour .= "<script type=\"text/javascript\">";
    $retour .= "var xhr_form_dyn_$num_champs_auto;";
    $retour .= "function maj_form_dyn_$num_champs_auto() {";
    $retour .= "nb_actions_en_cours++;";

    if (isset($mf_dictionnaire_db[$DB_name]['spec']['checkbox'])) {
        $retour .= 'var modif = ( document.getElementById("form_dyn_' . $num_champs_auto . '").checked ? 1 : 0);';
    } else {
        if ($mode_innerHTML) {
            $retour .= 'var modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        } else {
            $retour .= 'var modif = document.getElementById("form_dyn_' . $num_champs_auto . '").value;';
        }
    }

    if ($mode_pourcentage_montant_initiale !== false) {
        $retour .= "var pourcentage = Math.round(10000*modif/$mode_pourcentage_montant_initiale)/100;";
        $retour .= "document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value = pourcentage;";
    }
    $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat on');";
    $retour .= "xhr_form_dyn_$num_champs_auto = getXMLHttpRequest();";
    $retour .= "xhr_form_dyn_$num_champs_auto.open(\"POST\", \"api/$nom_table/modifier.php\", true);";
    $retour .= "xhr_form_dyn_$num_champs_auto.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");";
    $retour .= "xhr_form_dyn_$num_champs_auto.send(\"$cles&$DB_name=\"+encodeURIComponent(modif));";
    $retour .= "xhr_form_dyn_$num_champs_auto.onreadystatechange = function() {";
    $retour .= "if ( xhr_form_dyn_$num_champs_auto.readyState == 4 && (xhr_form_dyn_$num_champs_auto.status == 200 || xhr_form_dyn_$num_champs_auto.status == 0) ) {";
    $retour .= "nb_actions_en_cours--;";
    $retour .= "var retour = JSON.parse(xhr_form_dyn_$num_champs_auto.responseText);";
    $retour .= "if ( retour.code_erreur == 0 ) {";
    $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat');";
    $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('title', '');";
    $retour .= "" . ($rafraichissement_page ? "document.location.href='" . $fil_ariane->get_last_lien() . "'; " : "") . "";
    $retour .= "actualiser_les_champs();";
    $retour .= "}";
    $retour .= "else";
    $retour .= "{";
    $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('class', 'maj_dyn_etat ko');";
    $retour .= "document.getElementById(\"form_dyn_etat_$num_champs_auto\").setAttribute('title', retour.message_erreur);";
    $retour .= "}";
    $retour .= "}";

    // $retour .= 'else if (xhr_form_dyn_'.$num_champs_auto.'.readyState!=2 && xhr_form_dyn_'.$num_champs_auto.'.readyState!=3)';
    // $retour .= '{';

    // $retour .= 'nb_actions_en_cours--;';
    // $retour .= 'console.log("erreur xhr n°" + xhr_form_dyn_'.$num_champs_auto.'.readyState);';
    // $retour .= 'mf_maj_en_cours = false;';

    // $retour .= '}';

    $retour .= '}';
    $retour .= '}';

    if ($maj_auto) {

        global $champ_maj_auto;
        $champ_maj_auto[$nom_table][$cles][$DB_name][] = $num_champs_auto;

        if ($mode_pourcentage_montant_initiale !== false) {
            $retour .= "function maj_form_dyn_{$num_champs_auto}_p() {";
            $retour .= "var pourcentage = document.getElementById(\"form_dyn_{$num_champs_auto}_p\").value;";
            $retour .= "var v = Math.round( $mode_pourcentage_montant_initiale * pourcentage )/100;";
            $retour .= "document.getElementById(\"form_dyn_$num_champs_auto\").value = v;";
            $retour .= "maj_form_dyn_$num_champs_auto();";
            $retour .= "}";
        }
    }

    $retour .= '</script>';

    $num_champs_auto ++;

    return $retour;
}

function set_focus()
{
    global $num_champs_focus, $num_champs_auto;
    $num_champs_focus = $num_champs_auto - 1;
}

function mf_get_last_id_champ()
{
    global $num_champs_auto;
    return $num_champs_auto - 1;
}

function get_valeur_html_maj_auto_interface($parametres)
{
    $liste_valeurs_cle_table = $parametres['liste_valeurs_cle_table'];
    $DB_name = $parametres['DB_name'];
    $valeur_initiale = $parametres['valeur_initiale'];

    $nom_table = isset($parametres['nom_table']) ? $parametres['nom_table'] : '';
    $class = isset($parametres['class']) ? $parametres['class'] : '';
    $maj_auto = isset($parametres['maj_auto']) ? $parametres['maj_auto'] : true;
    $titre = isset($parametres['titre']) ? $parametres['titre'] : true;
    $mode_formulaire = isset($parametres['mode_formulaire']) ? $parametres['mode_formulaire'] : true;
    $exe_js_additionnel = isset($parametres['exe_js_additionnel']) ? $parametres['exe_js_additionnel'] : '';

    return get_valeur_html_maj_auto($liste_valeurs_cle_table, $DB_name, $valeur_initiale, $nom_table, $class, $maj_auto, $titre, $mode_formulaire, $exe_js_additionnel);
}

$gvhma_db_class = array();

function get_valeur_html_maj_auto($liste_valeurs_cle_table, $DB_name, $valeur, $nom_table = '', $class = '', $maj_auto = true, $titre = true, $mode_formulaire = true, $exe_js_additionnel = '')
{
    global $lang_standard, $mf_dictionnaire_db, $champ_maj_auto, $num_champs_auto, $gvhma_db_class;

    if ($titre == false) {
        $mode_formulaire = false;
    }

    if ($nom_table == '') {
        $nom_table = $mf_dictionnaire_db[$DB_name]['entite'];
    }

    $cles = '';
    foreach ($liste_valeurs_cle_table as $key => $value) {
        if ($cles != '')
            $cles .= '&';
        $cles .= $key . '=' . $value;
    }

    $class = ' ' . $class . ' ';
    $format_price = (stripos($class, ' price ') !== false);
    $format_date = (stripos($class, ' date ') !== false);
    $format_date_et_heure = (stripos($class, ' date_heure ') !== false);
    $zerohidden = (stripos($class, ' zerohidden ') !== false);
    $format_color = (stripos($class, ' color ') !== false);
    $format_html = (stripos($class, ' html ') !== false);
    $format_text = (stripos($class, ' text ') !== false);

    if (isset($mf_dictionnaire_db[$DB_name]['type'])) {
        switch ($mf_dictionnaire_db[$DB_name]['type']) {
            case 'DATE':
                $format_date = true;
                break;
            case 'DATETIME':
                $format_date_et_heure = true;
                break;
        }
    }

    $txt = '';
    if ($mode_formulaire) {
        $txt .= '<div class="row">';
        if ($titre) {
            $txt .= '<div class="col-sm-3 libelle_formulaire">';
            $txt .= '<label>' . htmlspecialchars(get_nom_colonne($DB_name)) . '&nbsp;:</label>';
            $txt .= '</div>';
            $txt .= '<div class="col-sm-9">';
        } else {
            $txt .= '<div class="col-sm-12">';
        }
        if (! $format_html && ! $format_text) {
            $txt .= '<p>';
        }
    }

    if (isset($lang_standard[$DB_name . '_'])) {
        $txt .= '<span' . ($maj_auto ? ' id="champ_auto_liste_' . $num_champs_auto . '"' : '') . '>';
        foreach ($lang_standard[$DB_name . '_'] as $key => $value) {
            $txt .= '<span' . ($maj_auto ? ' id="champ_auto_liste_' . $num_champs_auto . '_' . $key . '"' : '') . ' class="' . ($key == $valeur ? '' : 'masquer') . '">' . htmlspecialchars($value) . '</span>';
        }
        $txt .= '</span>';
    } else {
        if ($format_html)
            $txt .= '<div' . ($maj_auto ? ' id="champ_auto_' . $num_champs_auto . '"' : '') . ' class="' . ($maj_auto ? 'mf_attente_maj_auto' : '') . '">' . $valeur . '</div>';
        elseif ($format_date)
            $txt .= '<span' . ($maj_auto ? ' id="champ_auto_' . $num_champs_auto . '"' : '') . '>' . format_date_fr($valeur) . '</span>';
        elseif ($format_date_et_heure)
            $txt .= '<span' . ($maj_auto ? ' id="champ_auto_' . $num_champs_auto . '"' : '') . '>' . format_datetime_fr($valeur) . '</span>';
        elseif ($format_price)
            $txt .= '<span' . ($maj_auto ? ' id="champ_auto_' . $num_champs_auto . '"' : '') . '>' . format_nombre($valeur) . '</span>';
        elseif ($format_text)
            $txt .= '<pre style="white-space: pre-wrap;"><div' . ($maj_auto ? ' id="champ_auto_' . $num_champs_auto . '"' : '') . '>' . htmlspecialchars($valeur) . '</div></pre>';
        else
            $txt .= '<span' . ($maj_auto ? ' id="champ_auto_' . $num_champs_auto . '"' : '') . '>' . htmlspecialchars($valeur) . '</span>';
    }

    if ($mode_formulaire) {
        if (! $format_html && ! $format_text) {
            $txt .= '</p>';
        } else {
            $txt .= '<p></p>';
        }
        $txt .= '</div>';
        $txt .= '</div>';
    }

    if ($maj_auto) {
        $champ_maj_auto[$nom_table][$cles][$DB_name][] = $num_champs_auto;
        $format_text = ! $format_html;
        $gvhma_db_class[$num_champs_auto] = array(
            'format_price' => $format_price,
            'format_date' => $format_date,
            'format_date_et_heure' => $format_date_et_heure,
            'zerohidden' => $zerohidden,
            'format_color' => $format_color,
            'exe_js_additionnel' => $exe_js_additionnel,
            'format_text' => $format_text
        );
        $num_champs_auto ++;
    }

    return $txt;
}

function generer_script_maj_auto()
{
    $num_fonction = 1;
    $retour = '';
    global $champ_maj_auto, $mf_dictionnaire_db, $gvhma_db_class, $num_champs_focus, $mf_script_end, $mf_script_end_modal;
    $nb_fonctions = 0;
    foreach ($champ_maj_auto as $nom_table => $temp_1) {
        foreach ($temp_1 as $cles => $temp_2) {
            $nb_fonctions ++;
        }
    }
    foreach ($champ_maj_auto as $nom_table => $temp_1) {
        foreach ($temp_1 as $cles => $temp_2) {
            // requete
            $retour .= ' var xhr_maj_auto_' . $num_fonction . ';
                            var mf_maj_en_cours = false;
                            function maj_auto_' . $num_fonction . '(num_passe)
                            {
                                if ( mf_num_passe_maj_en_cours == num_passe && ! mf_maj_en_cours )
                                {
                                    mf_maj_en_cours = true;
                                    xhr_maj_auto_' . $num_fonction . ' = getXMLHttpRequest();
                                    xhr_maj_auto_' . $num_fonction . '.open("POST", "api/' . $nom_table . '/get.php", true);
                                    xhr_maj_auto_' . $num_fonction . '.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                    xhr_maj_auto_' . $num_fonction . '.send("' . $cles . '");
                                    xhr_maj_auto_' . $num_fonction . '.onreadystatechange = function()
                                    {
                                        if ( xhr_maj_auto_' . $num_fonction . '.readyState == 4 && (xhr_maj_auto_' . $num_fonction . '.status == 200 || xhr_maj_auto_' . $num_fonction . '.status == 0) )
                                        {
                                            mf_maj_en_cours = false;';

            $retour .= '                 try {';

            $retour .= '                 var retour = JSON.parse(xhr_maj_auto_' . $num_fonction . '.responseText);';

            $retour .= '                 } catch (e) {';

            if (! MODE_PROD) {
                $retour .= 'if (xhr_maj_auto_' . $num_fonction . '.responseText!="") alertModal(xhr_maj_auto_' . $num_fonction . '.responseText);';
            }

            $retour .= '                 }';

            foreach ($temp_2 as $DB_name => $liste_num_champs_auto) {

                foreach ($liste_num_champs_auto as $num_champs_auto) {

                    $retour .= '             if (document.getElementById("form_dyn_' . $num_champs_auto . '") && document.getElementById("form_dyn_' . $num_champs_auto . '") !== document.activeElement)
                                                {';

                    if (isset($mf_dictionnaire_db[$DB_name]['type']) && $mf_dictionnaire_db[$DB_name]['type'] == 'BOOL') {
                        if (isset($mf_dictionnaire_db[$DB_name]['spec']['checkbox'])) {
                            $retour .= '         document.getElementById("form_dyn_' . $num_champs_auto . '").checked = retour.get.' . $DB_name . ';';
                        } else {
                            $retour .= '         document.getElementById("form_dyn_' . $num_champs_auto . '").value = ( retour.get.' . $DB_name . ' ? 1 : 0 );';
                        }
                    } else {
                        $retour .= '             document.getElementById("form_dyn_' . $num_champs_auto . '").value = ( retour.get.' . $DB_name . (isset($mf_dictionnaire_db[$DB_name]['type']) && $mf_dictionnaire_db[$DB_name]['type'] == "DATETIME" ? ' + "" ).toString().replace(" ","T")' : ' )') . ';';
                    }

                    $retour .= '             }';

                    $retour .= '             if (document.getElementById("champ_auto_' . $num_champs_auto . '"))
                                                {';
                    $ouvertures_fonctions = '';
                    $fermetures_fonctions = '';
                    $exe_js_additionnel = '';
                    if (isset($gvhma_db_class[$num_champs_auto])) {
                        if ($gvhma_db_class[$num_champs_auto]['format_text']) {
                            $ouvertures_fonctions .= 'escapeHtml(';
                            $fermetures_fonctions .= ')';
                        }
                        if ($gvhma_db_class[$num_champs_auto]['format_price']) {
                            $ouvertures_fonctions .= 'function_format_price(';
                            $fermetures_fonctions .= ')';
                        }
                        if ($gvhma_db_class[$num_champs_auto]['format_date']) {
                            $ouvertures_fonctions .= 'function_format_date(';
                            $fermetures_fonctions .= ')';
                        }
                        if ($gvhma_db_class[$num_champs_auto]['format_date_et_heure']) {
                            $ouvertures_fonctions .= 'function_format_date_et_heure(';
                            $fermetures_fonctions .= ')';
                        }
                        if ($gvhma_db_class[$num_champs_auto]['exe_js_additionnel'] != '') {
                            $exe_js_additionnel = $gvhma_db_class[$num_champs_auto]['exe_js_additionnel'];
                        }
                    }

                    $retour .= '                 if (retour.code_erreur) { console.log("Erreur n°" + retour.code_erreur); } else { document.getElementById("champ_auto_' . $num_champs_auto . '").innerHTML = ' . $ouvertures_fonctions . 'retour.get.' . $DB_name . $fermetures_fonctions . ';';
                    $retour .= '                 ' . $exe_js_additionnel . ';';
                    $retour .= '                 document.getElementById("champ_auto_' . $num_champs_auto . '").setAttribute("style", ""); }';
                    $retour .= '             }';

                    $retour .= '             if (document.getElementById("champ_auto_liste_' . $num_champs_auto . '"))
                                                {
                                                    if (document.getElementById("champ_auto_liste_' . $num_champs_auto . '").hasChildNodes())
                                                    {
                                                        if (retour.code_erreur) { console.log("Erreur n°" + retour.code_erreur); } else {
                                                            collEnfants = document.getElementById("champ_auto_liste_' . $num_champs_auto . '").children;
                                                            for (var i = 0; i < collEnfants.length; i++)
                                                            {';
                    if (isset($mf_dictionnaire_db[$DB_name]['type']) && $mf_dictionnaire_db[$DB_name]['type'] == 'BOOL') {
                        $retour .= '                         if (collEnfants[i].id=="champ_auto_liste_' . $num_champs_auto . '_" + (retour.get.' . $DB_name . ' ? 1 : 0))';
                    } else {
                        $retour .= '                         if (collEnfants[i].id=="champ_auto_liste_' . $num_champs_auto . '_" + retour.get.' . $DB_name . ')';
                    }
                    $retour .= '                             {
                                                                collEnfants[i].className = "";
                                                                }
                                                                else
                                                                {
                                                                    collEnfants[i].className = "masquer";
                                                                }
                                                            }
                                                        }
                                                    }
                                                }';
                }
            }
            if ($num_fonction < $nb_fonctions) {
                $retour .= '                 setTimeout(maj_auto_' . ($num_fonction + 1) . ',' . DELAI_RAFRAICHISSEMENT_COURT . ',num_passe);';
            } else {
                $retour .= '                 setTimeout(maj_auto_1,' . DELAI_RAFRAICHISSEMENT . ',num_passe);';
            }

            $retour .= '             }
                                        else if (xhr_maj_auto_' . $num_fonction . '.readyState!=2 && xhr_maj_auto_' . $num_fonction . '.readyState!=3)
                                        {
                                            console.log("erreur xhr n°" + xhr_maj_auto_' . $num_fonction . '.readyState);
                                            mf_maj_en_cours = false;
                                        }
                                    }
                                }
                            }';
            $num_fonction ++;
        }
    }
    $retour = $retour != '' ? '
            <script type="text/javascript">' . $retour . '

            let nb_actions_en_cours = 0;
            function myTest_actions_en_cours()
            {
                if (nb_actions_en_cours>0) {
                    return "Des opérations sont en cours d\'enregistrement ...";
                }
            }
            ' . (ALERTE_INFOS_NON_ENREGISTREES ? 'window.onbeforeunload = myTest_actions_en_cours;' : '') . '

            let mf_num_passe_maj_en_cours = 0;
            function actualiser_les_champs()
            {
                mf_num_passe_maj_en_cours++;
                setTimeout(maj_auto_1,' . DELAI_RAFRAICHISSEMENT_COURT . ',mf_num_passe_maj_en_cours);
            }
            setTimeout(actualiser_les_champs,' . DELAI_RAFRAICHISSEMENT . ');

            function function_format_price(val)
            {
                let r = "00" + Math.round(Number( val%1 ).toFixed(2)*100);
                r = r.substring( r.length-2 );
                val = parseInt( val );
                r = (val == 0 ? "0," : ",") + r;
                let milliers=false;
                if ( val<0 ) {
                    val = - val;
                    signe = -1;
                } else {
                    signe = 1;
                }
                while (val != 0) {
                    let t = "" + Math.round(Number( val%1000 ));
                    val = parseInt( val/1000 );
                    if (val != 0) {
                        t = "000" + t;
                    }
                    r = t.substring( t.length-3 ) + ( milliers ? " " : "" ) + r;
                    milliers = true;
                }
                if (signe == -1) {
                    r = "-" + r;
                }
                return r;
            }

            function function_format_date(val)
            {
                let d = "" + val;
                if (d != "" && d != "null") {
                    let annee = d.substring(0,4);
                    let mois = d.substring(5,7);
                    let jour = d.substring(8,10);
                    return jour + "/" + mois + "/" + annee;
                }
                return "";
            }
            function function_format_date_et_heure(val)
            {
                let d = "" + val;
                if (d != "" && d != "null") {
                    let annee = d.substring(0,4);
                    let mois = d.substring(5,7);
                    let jour = d.substring(8,10);
                    let heure = d.substring(11,13);
                    let minute = d.substring(14,16);
                    let seconde = d.substring(17,19);
                    return jour + "/" + mois + "/" + annee + " à " + heure + ":" + minute + ( seconde!=0 ? ":" + seconde : "" );
                }
                return "";
            }
            function escapeHtml(text) {
                if (text === null) {
                    return "";
                } else {
                    let map = {
                        "&": "&amp;",
                        "<": "&lt;",
                        ">": "&gt;",
                        \'"\': "&quot;",
                        "\'": "&#039;"
                    };
                    return text.toString().replace(/[&<>"\']/g, function(m) { return map[m]; });
                }
            }
        </script>' : '<script type="text/javascript">let nb_actions_en_cours=0;function actualiser_les_champs(){};</script>';

    if ($num_champs_focus != 0) {
        $retour .= '<script type="text/javascript">document.getElementById("form_dyn_' . $num_champs_focus . '").select();</script>';
    }

    if ($mf_script_end . $mf_script_end_modal != '') {
        $retour .= '<script type="text/javascript">' . $mf_script_end . $mf_script_end_modal . '</script>';
    }

    if (! MODE_PROD) {
        if (USE_BOOTSTRAP) {
            $retour .= '<!-- Modal -->';
            $retour .= '<div id="debugAlertModal" class="modal" role="dialog">';
            $retour .= '<div class="modal-dialog modal-lg">';

            $retour .= '<!-- Modal content-->';
            $retour .= '<div class="modal-content">';
            $retour .= '<div class="modal-header">';
            $retour .= '<h4 class="modal-title">Debug Alert</h4>';
            $retour .= '<button type="button" class="close" data-dismiss="modal">&times;</button>';
            $retour .= '</div>';
            $retour .= '<div class="modal-body" id="debugAlertModalContent">';
            $retour .= '</div>';
            $retour .= '<div class="modal-footer">';
            $retour .= '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            $retour .= '</div>';
            $retour .= '</div>';

            $retour .= '</div>';
            $retour .= '</div>';
            $retour .= '<script type="text/javascript">function alertModal(contenu) { document.getElementById("debugAlertModalContent").innerHTML = contenu; $("#debugAlertModal").modal("show"); }</script>';
        } else {
            $retour .= '<script type="text/javascript">function alertModal(contenu) { alert(contenu); }</script>';
        }
    }

    $retour .= '<script type="text/javascript">';
    $retour .= 'function mf_getXMLHttpRequest() {';
    $retour .= 'let xhr = null;';
    $retour .= 'if (window.XMLHttpRequest || window.ActiveXObject) {';
    $retour .= 'if (window.ActiveXObject) {';
    $retour .= 'try {';
    $retour .= 'xhr = new ActiveXObject("Msxml2.XMLHTTP");';
    $retour .= '} catch (e) {';
    $retour .= 'xhr = new ActiveXObject("Microsoft.XMLHTTP");';
    $retour .= '}';
    $retour .= '} else {';
    $retour .= 'xhr = new XMLHttpRequest();';
    $retour .= '}';
    $retour .= '} else {';
    $retour .= 'return null;';
    $retour .= '}';
    $retour .= 'return xhr;';
    $retour .= '}';
    $retour .= 'function launch_worker() {mf_worker=mf_getXMLHttpRequest(); mf_worker.open("GET", "' . ADRESSE_SITE . 'mf_worker_run.php", true) ; mf_worker.send(null);}';
    $retour .= 'launch_worker();';
    $retour .= 'setInterval(launch_worker, ' . ((DELAI_EXECUTION_WORKER + 1) * 1000) . ');';
    $retour .= '</script>';

    return $retour;
}
