<?php

class Calendrier
{

    // partie privee
    private $donnees;

    private $colonne_texte;

    private $valeur_modifiable;

    private $act;

    private $liste_Codes_ref;

    private $autre_page;

    private $autre_page_nouvel_onglet;

    private $activer_pagination;

    private $colonne_heure_debut;

    // pour planning semaine
    private $colonne_heure_fin;

    private $colonne_class;

    private $colonne_href;

    private $donnees_2;

    private $libelle_colonne_date = "";

    private $heure_start = '';

    private $heure_end = '';

    private $colonne_api_rest = '';

    private $blocage_y = false;

    // static
    static function chevauchement($plage_1_start, $plage_1_end, $plage_2_start, $plage_2_end, $mode_limite = false)
    {
        return $plage_1_start < $plage_2_start && $plage_2_start < $plage_1_end || $plage_1_start < $plage_2_end && $plage_2_end < $plage_1_end || $plage_2_start < $plage_1_start && $plage_1_start < $plage_2_end || $plage_2_start < $plage_1_end && $plage_1_end < $plage_2_end || $plage_1_start == $plage_2_start && $plage_1_end == $plage_2_end || $mode_limite && ($plage_1_end == $plage_2_start || $plage_2_end == $plage_1_start);
    }

    // partie publique
    function __construct($donnees, $colonne_date, $colonne_texte, $colonne_heure_debut = '', $colonne_heure_fin = '', $colonne_class = '', $colonne_href = '', $colonne_groupe = '', $colonne_api_rest = '')
    {

        // changement de repère
        $this->donnees = array();
        foreach ($donnees as $value) {
            $this->donnees[$value[$colonne_date]] = $value;
        }

        $this->colonne_texte = $colonne_texte;
        $this->valeur_modifiable = false;

        $this->act = 'apercu';
        $this->liste_Codes_ref = array();

        $this->autre_page = '';
        $this->autre_page_nouvel_onglet = false;

        $this->activer_pagination = true;

        $this->colonne_heure_debut = $colonne_heure_debut;
        $this->colonne_heure_fin = $colonne_heure_fin;
        $this->colonne_class = $colonne_class;
        $this->colonne_href = $colonne_href;
        $this->donnees_2 = array();
        foreach ($donnees as $value) {
            $this->donnees_2[$value[$colonne_date]][] = $value;
        }

        // traitement des chevauchements
        if ($colonne_heure_debut != '' && $colonne_heure_fin != '') {

            // tri des données
            foreach ($this->donnees_2 as &$journee) {
                // tri bulle
                $nb = count($journee);
                $tri = true;
                while ($tri) {
                    $tri = false;
                    for ($i = 0; $i < $nb - 1; $i ++) {
                        $j = $i + 1;
                        if ($journee[$i][$colonne_heure_debut] > $journee[$j][$colonne_heure_debut] || $journee[$i][$colonne_heure_debut] == $journee[$j][$colonne_heure_debut] && $journee[$i][$colonne_heure_fin] < $journee[$j][$colonne_heure_fin]) {
                            $t = $journee[$i];
                            $journee[$i] = $journee[$j];
                            $journee[$j] = $t;
                            $tri = true;
                        }
                    }
                }
            }

            // définition des groupes si ceuc-ci n'existent pas ou indice dans l'ordre
            if ($colonne_groupe == '') {
                $colonne_groupe = 'mf_groupe';
                foreach ($this->donnees_2 as &$journee) {
                    $i = 1;
                    foreach ($journee as $ii => &$plage) {
                        $plage[$colonne_groupe] = $i;
                        $i ++;
                    }
                }
            } else {
                foreach ($this->donnees_2 as &$journee) {
                    $temp = array();
                    $i = 0;
                    foreach ($journee as &$plage) {
                        if (! isset($temp[$plage[$colonne_groupe]])) {
                            $temp[$plage[$colonne_groupe]] = $i;
                            $i ++;
                        }
                        $plage[$colonne_groupe] = $temp[$plage[$colonne_groupe]];
                    }
                }
            }

            // journée par journée
            foreach ($this->donnees_2 as &$journee) {
                $nb = count($journee);

                $zindex = 1;

                // créarion des groupes
                $journee_groupes = array();
                foreach ($journee as &$plage) {
                    $groupe = $plage[$colonne_groupe];
                    if (isset($journee_groupes[$groupe])) {
                        $journee_groupes[$groupe][$colonne_heure_debut] = ($journee_groupes[$groupe][$colonne_heure_debut] > $plage[$colonne_heure_debut] ? $plage[$colonne_heure_debut] : $journee_groupes[$groupe][$colonne_heure_debut]);
                        $journee_groupes[$groupe][$colonne_heure_fin] = ($journee_groupes[$groupe][$colonne_heure_fin] < $plage[$colonne_heure_fin] ? $plage[$colonne_heure_fin] : $journee_groupes[$groupe][$colonne_heure_fin]);
                        $journee_groupes[$groupe]['mf_liste'][] = $plage;
                    } else {
                        $journee_groupes[$groupe][$colonne_heure_debut] = $plage[$colonne_heure_debut];
                        $journee_groupes[$groupe][$colonne_heure_fin] = $plage[$colonne_heure_fin];
                        $journee_groupes[$groupe]['mf_liste'][] = $plage;
                        $journee_groupes[$groupe]['decalage_planning'] = 0;
                        $journee_groupes[$groupe]['decalage_planning_width'] = 0;
                        $journee_groupes[$groupe]['num_ref_association'] = 0;
                        $journee_groupes[$groupe]['z-index'] = $zindex;
                        $zindex ++;
                    }
                }

                // décalage
                $num_ref_association_chrono = 0;
                $largeur_num = array();
                foreach ($journee_groupes as $i => &$groupe_i) {
                    foreach ($journee_groupes as $j => &$groupe_j) {
                        if ($j > $i) {
                            if ($groupe_j[$colonne_heure_debut] < $groupe_i[$colonne_heure_fin]) // si chevauchement alors décalage
                            {
                                $groupe_j['decalage_planning'] = $groupe_i['decalage_planning'] + 20;
                                if ($groupe_i['num_ref_association'] == 0 && $groupe_j['num_ref_association'] == 0) {
                                    $num_ref_association_chrono ++;
                                    $groupe_i['num_ref_association'] = $num_ref_association_chrono;
                                    $groupe_j['num_ref_association'] = $num_ref_association_chrono;
                                    $largeur_num[$num_ref_association_chrono] = $groupe_j['decalage_planning'];
                                } else {
                                    if ($groupe_i['num_ref_association'] != 0) {
                                        $groupe_j['num_ref_association'] = $groupe_i['num_ref_association'];
                                    } else {
                                        $groupe_i['num_ref_association'] = $groupe_j['num_ref_association'];
                                    }
                                    if ($largeur_num[$num_ref_association_chrono] < $groupe_j['decalage_planning']) {
                                        $largeur_num[$num_ref_association_chrono] = $groupe_j['decalage_planning'];
                                    }
                                }
                            }
                        }
                    }
                }

                // restitution des décalages sur les plages :
                foreach ($journee as &$plage) {
                    $groupe = $plage[$colonne_groupe];

                    $plage['decalage_planning'] = $journee_groupes[$groupe]['decalage_planning'];
                    $plage['decalage_planning_width'] = $journee_groupes[$groupe]['decalage_planning_width'];
                    $plage['num_ref_association'] = $journee_groupes[$groupe]['num_ref_association'];
                    $plage['z-index'] = $journee_groupes[$groupe]['z-index'];
                }

                for ($num_ref_association = 1; $num_ref_association <= $num_ref_association_chrono; $num_ref_association ++) {
                    for ($i = 0; $i < $nb; $i ++) {
                        if ($journee[$i]['num_ref_association'] == $num_ref_association) {
                            $journee[$i]['decalage_planning_width'] = $largeur_num[$num_ref_association];
                        }
                    }
                }

                // min et max
                for ($i = 0; $i < $nb; $i ++) {
                    if ($this->heure_start == '') {
                        $this->heure_start = $journee[$i][$colonne_heure_debut];
                    } elseif ($this->heure_start > $journee[$i][$colonne_heure_debut]) {
                        $this->heure_start = $journee[$i][$colonne_heure_debut];
                    }
                    if ($this->heure_end == '') {
                        $this->heure_end = $journee[$i][$colonne_heure_fin];
                    } elseif ($this->heure_end < $journee[$i][$colonne_heure_fin]) {
                        $this->heure_end = $journee[$i][$colonne_heure_fin];
                    }
                }
            }
        }

        $this->libelle_colonne_date = $colonne_date;

        $this->colonne_api_rest = $colonne_api_rest;
    }

    function bloquer_y()
    {
        $this->blocage_y = true;
    }

    function ajouter_ref_Colonne_Code($libelle_Code_colonne)
    {
        $this->liste_Codes_ref[] = $libelle_Code_colonne;
    }

    function modifier_page_destination($autre_page, $nouvel_onglet = false)
    {
        $this->autre_page = $autre_page;
        $this->autre_page_nouvel_onglet = $nouvel_onglet;
    }

    function modifier_code_action($act)
    {
        $this->act = $act;
    }

    function rentre_valeur_modifiable()
    {
        $this->valeur_modifiable = true;
    }

    private $initialisation_get_contenu = false;

    private $mode_liste = false;

    private function get_contenu(&$ligne)
    {
        global $lang_standard;
        $contenu = '';
        if ($this->colonne_texte != '') {
            if ($this->valeur_modifiable) {
                $liste_valeurs_cle_table = array();
                foreach ($this->liste_Codes_ref as $value) {
                    $liste_valeurs_cle_table[$value] = $ligne[$value];
                }
                $contenu = ajouter_champ_modifiable($liste_valeurs_cle_table, $this->colonne_texte, $ligne[$this->colonne_texte]);
            } else {
                if (! $this->initialisation_get_contenu) {
                    $this->mode_liste = (isset($lang_standard[$this->colonne_texte . '_']));
                    $this->initialisation_get_contenu = true;
                }
                if ($this->mode_liste) {
                    $contenu = htmlspecialchars(get_nom_valeur($this->colonne_texte, $ligne[$this->colonne_texte]));
                } else {
                    $contenu = get_valeur_formate($this->colonne_texte, $ligne[$this->colonne_texte]);
                }
            }
        }
        if ($contenu == '') {
            $contenu = '&nbsp;';
        }
        return $contenu;
    }

    private function conversion_heure_en_secondes($heure_str)
    {
        $h = round(substr($heure_str, 0, 2));
        $m = round(substr($heure_str, 3, 2));
        $s = round(substr($heure_str, 6, 2));
        return ($h * 3600 + $m * 60 + $s);
    }

    function generer_code_1($date_start, $date_end, $adresse_specifique = '', $date_a_afficher = '', $jours = array(1, 2, 3, 4, 5, 6, 7), $clique_date_jour = false)
    {
        global $now;

        $code = '';
        $code_js = '';

        $date_a_afficher = format_date($date_a_afficher);
        if ($date_a_afficher == '') {
            $date_a_afficher = format_date($now);
        }
        $mois_a_afficher = substr($date_a_afficher, 0, 7);

        $date_start = format_date($date_start);
        $date_end = format_date($date_end);

        $date_start_local = $date_start;
        $date_end_local = date_fin_mois($date_start);

        $diff1Day = new DateInterval('P1D');

        $lib_mois = array(
            'Janvier',
            'F&eacute;vrier',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Ao&ucirc;t',
            'Septembre',
            'Octobre',
            'Novembre',
            'D&eacute;cembre'
        );
        $lib_jour = array(
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche'
        );

        $first = true;
        $compteur_calendrier = 0;

        $date_aujourdhui = format_date($now);

        $selection_jours = array();
        $dernier_jour = 1;
        foreach ($jours as $i) {
            $selection_jours[round($i - 1)] = 1;
            if ($i > $dernier_jour) {
                $dernier_jour = $i;
            }
        }

        while ($date_end_local <= $date_end) {

            $last = ($date_end_local == $date_end);

            if ($date_start_local != '' && $date_end_local != '') {

                $compteur_calendrier ++;

                $date = new DateTime($date_start_local);

                $code .= '<div id="calendrier_' . $compteur_calendrier . '" class="' . ($mois_a_afficher == $date->format('Y-m') ? '' : 'masquer') . "\"><div class=\"contour_calendrier calendrier_1\"><table class=\"calendrier_structure\"><thead><tr><th style=\"text-align: left;\">" . ($first ? "" : "<button onclick=\"document.getElementById('calendrier_$compteur_calendrier').className = 'masquer';document.getElementById('calendrier_" . ($compteur_calendrier - 1) . "').className = '';\">prec</button> ") . "</th><th colspan=\"" . (count($jours) - 2) . "\">{$lib_mois[$date->format('n')-1]} {$date->format('Y')}</th><th style=\"text-align: right;\">" . ($last ? "" : " <button onclick=\"document.getElementById('calendrier_$compteur_calendrier').className = 'masquer';document.getElementById('calendrier_" . ($compteur_calendrier + 1) . "').className = '';\">suiv</button>") . "</th></tr><tr>";
                foreach ($lib_jour as $key => $jour) {
                    if (isset($selection_jours[$key])) {
                        $code .= '<th>' . $jour . '<br>&nbsp;</th>';
                    }
                }
                $code .= '</tr></thead><tbody><tr>';
                $first = false;

                while ($date->format('w') != 1) {
                    $date->sub($diff1Day);
                }

                $date_str = $date->format('Y-m-d');

                while ($date_str <= $date_end_local || $date->format('w') != 1) {
                    $p = $date->format('w') - 1;
                    if ($p == - 1)
                        $p = 6; // décalage pour le dimanche
                    if (isset($selection_jours[$p])) {

                        $class_jour = ($date_aujourdhui == $date_str ? ' courant' : '') . ($date_aujourdhui > $date_str ? ' passe' : '') . ($date_aujourdhui < $date_str ? ' futur' : '');

                        if ($date->format('w') == 1) {
                            $code .= '</tr><tr>';
                        }
                        if ($date_start_local <= $date_str && $date_str <= $date_end_local) {

                            if (isset($this->donnees_2[$date_str])) {

                                trier_tableau_suivant_plusieurs_colonnes($this->donnees_2[$date_str], [
                                    $this->colonne_heure_debut => 'ASC',
                                    $this->colonne_class => 'ASC'
                                ]);

                                $contenu = '<span class="calendrier_jour_contenu">';

                                foreach ($this->donnees_2[$date_str] as $ligne) {

                                    $lien_debut = '';
                                    $lien_fin = '';

                                    $class = ($this->colonne_class != '' ? ' ' . $ligne[$this->colonne_class] : '');

                                    if ($this->colonne_api_rest == '') {
                                        $lien_debut = (($this->colonne_href != '') ? '<a href="' . ($ligne[$this->colonne_href] != '' ? $ligne[$this->colonne_href] : '#999') . '" class="' . $class . '">' : '');
                                        $lien_fin = (($this->colonne_href != '') ? '</a>' : '');
                                    } else {
                                        if ($this->colonne_href != '' && $ligne[$this->colonne_href] != '') {
                                            $lien_debut = '<div ondblclick="location.href=\'' . $ligne[$this->colonne_href] . '\'" class="' . $class . '">';
                                            $lien_fin = '</div>';
                                        }
                                    }

                                    $contenu .= '' . $lien_debut . '<span class="zone_cliquable_calendrier"' . ($this->colonne_api_rest != '' && isset($ligne[$this->colonne_api_rest]) && $ligne[$this->colonne_api_rest] != '' ? ' code_js_api="' . $ligne[$this->colonne_api_rest] . '"' : '') . '>' . $ligne[$this->colonne_texte] . '</span>' . $lien_fin . '';
                                }

                                $contenu .= '</span>';

                                if ($clique_date_jour) {
                                    $contenu .= '<a href="?jour_planning=' . $date_str . '"><span class="calendrier_jour_legende">' . $date->format('j') . '</span></a>';
                                } else {
                                    $contenu .= '<span class="calendrier_jour_legende">' . $date->format('j') . '</span>';
                                }
                            } else {
                                if ($clique_date_jour) {
                                    $contenu = '<span class="calendrier_jour_contenu">&nbsp;</span><a href="?jour_planning=' . $date_str . '"><span class="calendrier_jour_legende">' . $date->format('j') . '</span></a>';
                                } else {
                                    $contenu = '<span class="calendrier_jour_contenu">&nbsp;</span><span class="calendrier_jour_legende">' . $date->format('j') . '</span>';
                                }
                            }

                            $code .= "<td class=\"calendrier_jour_structure{$class_jour}\" info=\"" . $date_str . "\" >$contenu</td>";
                        } else {
                            $code .= '<td></td>';
                        }
                    }

                    $date->add($diff1Day);
                    $date_str = $date->format('Y-m-d');
                }

                $code .= '</tr></tbody></table></div></div>';
            }

            $code_js .= '$(function(){';

            // draggable
            $code_js .= '$(".zone_cliquable_calendrier").draggable({';

            $code_js .= 'drag: function( event, ui ) {';

            $code_js .= '$( this ).addClass("devant");';

            $code_js .= '},';

            $code_js .= '});';

            $code_js .= '$(".calendrier_jour_structure").droppable({';

            $code_js .= 'drop: function( event, ui ) {';

            $code_js .= 'var new_date = $( this ).attr("info");';

            $code_js .= 'var adresse_api = ui.helper[0].attributes[1].nodeValue;';

            $code_js .= 'console.log(adresse_api);';
            $code_js .= '$.ajax({url: adresse_api + "?auth=main",';
            $code_js .= 'type: "PUT",';
            $code_js .= 'dataType: "json",';
            $code_js .= 'contentType: "application/json",';
            $code_js .= 'processData: true,';
            $code_js .= 'headers : {"Content-Type" : "application/x-www-form-urlencoded; charset=UTF-8"},';
            $code_js .= 'data: JSON.stringify( { new_date: new_date } ),';
            $code_js .= 'success: function(data){ actualiser_les_champs(); },';
            $code_js .= 'error: function(){ actualiser_les_champs(); }});';

            $code_js .= '}';

            $code_js .= '});';

            $code_js .= '});';

            $date = new DateTime($date_end_local);
            $date->add($diff1Day);
            $date_start_local = $date->format('Y-m') . '-01';
            $date_end_local = date_fin_mois($date_start_local);
        }

        if ($this->colonne_api_rest != '') {
            if (function_exists('mf_injection_js')) {
                mf_injection_js($code_js);
            }
            $code_js_ecapsule = '<input type="hidden" id="calendrier_code_js" value=\'' . $code_js . '\'>';
        } else {
            $code_js_ecapsule = '';
        }

        return $code . $code_js_ecapsule;
    }

    function generer_code_2($date_start, $date_end, $nb_mois_par_ligne = 99)
    {
        $i_colonne = 1;
        $lignes[$i_colonne] = "";
        $compteur_colonnes = 0;

        $date_start = format_date($date_start);
        $date_end = format_date($date_end);
        $diff1Day = new DateInterval('P1D');

        $lib_jour = array(
            "Lu",
            "Ma",
            "Me",
            "Je",
            "Ve",
            "Sa",
            "Di"
        );
        $lib_mois = array(
            "Janvier",
            "F&eacute;vrier",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Ao&ucirc;t",
            "Septembre",
            "Octobre",
            "Novembre",
            "D&eacute;cembre"
        );

        if ($date_start != "" && $date_end != "") {

            $date = new DateTime($date_start);

            $n_temp = - 1;
            $W_temp = - 1;

            while ($date->format("Y-m-d") <= $date_end) {
                $date_str = $date->format("Y-m-d");

                $Y = $date->format("Y");

                if ($date->format("n") != $n_temp) {
                    $n_temp = $date->format("n"); // num de mois
                    if ($lignes[$i_colonne] != "")
                        $lignes[$i_colonne] .= "</tbody></table></td>";

                    $compteur_colonnes ++;
                    if ($compteur_colonnes > $nb_mois_par_ligne) {
                        $i_colonne ++;
                        $lignes[$i_colonne] = "";
                        $compteur_colonnes = 1;
                    }

                    $lignes[$i_colonne] .= "<td class=\"calendrier_mois_colonne_mois\"><table class=\"calendrier_mois\"><thead><tr><th colspan=\"4\">{$lib_mois[$n_temp-1]} $Y</th></tr></thead><tbody>";
                    $W_temp = - 1;
                }

                $j = $date->format("j");
                $w = $date->format("w");
                if ($w == 0)
                    $w = 7;
                $W = $date->format("W"); // num de semaine
                $t = $date->format("t"); // nb de jours dans me mois

                $lien = "";
                $lien_fin = "";
                $contenu = "&nbsp;";

                if (isset($this->donnees[$date_str])) {
                    $ligne = $this->donnees[$date_str];
                    if (count($this->liste_Codes_ref) > 0 && ! $this->valeur_modifiable) {
                        if ($this->autre_page == "")
                            $lien = "<a class='lien' href=\"?act={$this->act}";
                        else
                            $lien = "<a class='lien' " . ($this->autre_page_nouvel_onglet ? "target=\"_blank\"" : "") . " href=\"{$this->autre_page}?act={$this->act}";

                        foreach ($this->liste_Codes_ref as $value) {
                            $lien .= "&$value={$ligne[$value]}";
                        }
                        $lien .= "\">";
                        $lien_fin = "</a>";
                    }
                    $contenu = $this->get_contenu($ligne);
                }

                $lignes[$i_colonne] .= "<tr class=\"j_{$lib_jour[$w-1]}\"><td class=\"calendrier_num_jour\">$lien$j$lien_fin</td><td class=\"calendrier_lib_jour\">$lien{$lib_jour[$w-1]}$lien_fin</td><td class=\"\">$lien$contenu$lien_fin</td>" . ($W != $W_temp ? "<td class=\"calendrier_num_semaine" . (($w == 1 && min($t - $j + 1, 7 - $w + 1) == 7) || $w != 1 ? " fin" : "") . "\" rowspan=" . (min($t - $j + 1, 7 - $w + 1)) . ">$W</td>" : "") . "</tr>";
                $W_temp = $W;

                $date->add($diff1Day);
            }

            $lignes[$i_colonne] .= "</tbody></table></td>";
        }

        $code = "";
        foreach ($lignes as $ligne) {
            $code .= "<div class=\"contour_calendrier calendrier_2\"><table class=\"calendrier_mois_structure\"><tr>" . $ligne . "</tr></table></div>";
        }
        return $code;
    }

    function generer_code_3($date_start, $date_end, $heure_start = '08:00:00', $heure_end = '20:00:00', $jours = array(1, 2, 3, 4, 5, 6, 7), $nb_pixels_hauteur = 512, $date_a_afficher = '', $grille_cliquable_action = '', $api_mvt = '', $clique_date_jour = false, $precision_mn = 10)
    {
        global $now, $cle_aleatoire;

        $code = '';
        $code_js = '';

        $date_start = format_date($date_start);
        $date_end = date_fin_semaine(format_date($date_end));

        $date_start_local = date_debut_semaine($date_start);
        $date_end_local = date_fin_semaine($date_start);

        $diff1Day = new DateInterval('P1D');

        $lib_mois = array(
            'Janvier',
            'F&eacute;vrier',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Ao&ucirc;t',
            'Septembre',
            'Octobre',
            'Novembre',
            'D&eacute;cembre'
        );
        $lib_jour = array(
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche'
        );

        $first = true;
        $compteur_calendrier = 0;

        $selection_jours = array();
        $dernier_jour = 1;
        foreach ($jours as $i) {
            $selection_jours[round($i - 1)] = 1;
            if ($i > $dernier_jour) {
                $dernier_jour = $i;
            }
        }

        $coef = 86400 / $nb_pixels_hauteur;
        $height_une_heure = 3600 / $coef - 1;

        $h_start = round($this->conversion_heure_en_secondes($heure_start) / 3600 - 0.4999);
        $h_end = round($this->conversion_heure_en_secondes($heure_end) / 3600 - 0.4999);

        $heure_start = $this->conversion_heure_en_secondes($heure_start);
        $heure_end = $this->conversion_heure_en_secondes($heure_end);

        if ($this->heure_start != '') {
            $this->heure_start = $this->conversion_heure_en_secondes($this->heure_start);
            if ($heure_start > $this->heure_start - 180)
                $heure_start = $this->heure_start - 180;
        }

        if ($this->heure_end != '') {
            $this->heure_end = $this->conversion_heure_en_secondes($this->heure_end);
            if ($heure_end < $this->heure_end + 180)
                $heure_end = $this->heure_end + 180;
        }

        $margin_top_corps = - $heure_start / $coef;
        $height_corps = ($heure_end - $heure_start) / $coef;

        $date_a_afficher = format_date($date_a_afficher);
        if ($date_a_afficher == '') {
            $date_a_afficher = format_date($now);
        }
        $date_a_afficher_lundi = date_debut_semaine($date_a_afficher);

        $date_aujourdhui = format_date($now);

        while ($date_end_local <= $date_end) {

            $last = ($date_end_local == $date_end);

            if ($date_start_local != '' && $date_end_local != '') {

                $compteur_calendrier ++;

                $date = new DateTime($date_start_local);

                $code .= "<div id=\"calendrier_jour_$compteur_calendrier\" class=\"" . ($date_a_afficher_lundi == $date_start_local ? "" : "masquer") . "\">
                            <div class=\"contour_calendrier calendrier_3\">
                                <table class=\"calendrier_structure\">
                                    <thead><tr>
                                        <th style=\"text-align: left;\">" . ($first ? '' : "<button onclick=\"document.getElementById('calendrier_jour_$compteur_calendrier').className = 'masquer';document.getElementById('calendrier_jour_" . ($compteur_calendrier - 1) . "').className = '';\">prec</button> ") . "</th>
                                        <th></th>
                                        <th style=\"text-align: right;\">" . ($last ? '' : " <button onclick=\"document.getElementById('calendrier_jour_$compteur_calendrier').className = 'masquer';document.getElementById('calendrier_jour_" . ($compteur_calendrier + 1) . "').className = '';\">suiv</button>") . "</th>
                                    </tr></thead>
                                </table>
                                <table class=\"calendrier_structure\"><tr style=\"vertical-align: top;\">";

                $first = false;

                for ($i = 0; $i < 7; $i ++) {

                    $date_str = $date->format('Y-m-d');
                    $class_jour = ($date_aujourdhui == $date_str ? ' courant' : '') . ($date_aujourdhui > $date_str ? ' passe' : '') . ($date_aujourdhui < $date_str ? ' futur' : '');

                    if (isset($selection_jours[$i])) {
                        $j = $date->format('j');
                        $w = $date->format('w');
                        if ($w == 0)
                            $w = 7;
                        $n = $date->format('n');
                        $Y = $date->format('Y');
                        $code .= "<td class=\"jour_" . ($i + 1) . ($dernier_jour - 1 == $i ? ' last' : '') . "{$class_jour}\">";

                        if ($clique_date_jour) {
                            $code .= '<div class="entete"><a href="?jour_planning=' . $date_str . '">' . $lib_jour[$w - 1] . ' ' . $j . '<br>' . $lib_mois[$n - 1] . ' ' . $Y . '</a></div>';
                        } else {
                            $code .= '<div class="entete">' . $lib_jour[$w - 1] . ' ' . $j . '<br>' . $lib_mois[$n - 1] . ' ' . $Y . '</div>';
                        }

                        $code .= "<div class=\"corps\" style=\"height: {$height_corps}px;\">";

                        // quadrillage toutes les heures et marqueur tout les 4 heures
                        for ($j = 0; $j < 24; $j ++) {
                            if ($h_start <= $j && $j < $h_end) {
                                $marqueur = $j / 4 == round($j / 4) && $j > 0;
                                $code .= "<div class=\"quadrillage_jour_ligne_" . ($marqueur ? '1' : '2') . "\" style=\"height: 1px;" . ($j == 0 ? " margin-top: {$margin_top_corps}px;" : "") . '"></div>';
                                if ($grille_cliquable_action == '') {
                                    $code .= '<div class="quadrillage_jour' . ($marqueur ? ' marqueur' : '') . '" style="height: ' . $height_une_heure . 'px;">' . ($marqueur ? "<span>$j:00</span>" : "") . '</div>';
                                } else {
                                    $code .= '<a class="planning_heure" href="?act=' . $grille_cliquable_action . '&date_planning=' . $date_str . ' ' . $j . ':00:00&secur=' . $cle_aleatoire . "\"><div class=\"quadrillage_jour" . ($marqueur ? " marqueur" : "") . '" style="height: ' . $height_une_heure . 'px;">' . ($marqueur ? "<span>$j:00</span>" : '<span class="marquage_intermediaire">' . $j . ':00</span>') . "</div></a>";
                                }
                            }
                        }

                        if (isset($this->donnees_2[$date_str])) {
                            $h2_prec = 86400 - (24 - $h_end) * 3600; // initialisation quadrillage
                            while (count($this->donnees_2[$date_str]) > 0) {
                                $start = '23:59:59';
                                $ind_ref = 0;
                                foreach ($this->donnees_2[$date_str] as $ind => $plage) {
                                    if ($plage[$this->colonne_heure_debut] <= $start) {
                                        $start = $plage[$this->colonne_heure_debut];
                                        $ind_ref = $ind;
                                    }
                                }
                                $plage_ref = $this->donnees_2[$date_str][$ind_ref];
                                unset($this->donnees_2[$date_str][$ind_ref]);

                                $texte = $plage_ref[$this->colonne_texte];
                                $heure_debut = $plage_ref[$this->colonne_heure_debut];
                                $heure_fin = $plage_ref[$this->colonne_heure_fin];
                                if ($this->colonne_api_rest == '') {
                                    $lien_debut = (($this->colonne_href != '') ? '<a href="' . ($plage_ref[$this->colonne_href] != '' ? $plage_ref[$this->colonne_href] : '#999') . '">' : '');
                                    $lien_fin = (($this->colonne_href != '') ? '</a>' : '');
                                } else {
                                    if ($this->colonne_href != '' && $plage_ref[$this->colonne_href] != '') {
                                        // non comptatible Iphone
                                        // $lien_debut = '<div ondblclick="location.href=\'' . $plage_ref[$this->colonne_href] . '\'">';
                                        // $lien_fin = '</div>';
                                        $lien_debut = '<a href="' . $plage_ref[$this->colonne_href] . '">';
                                        $lien_fin = '</a>';
                                    } else {
                                        $lien_debut = '';
                                        $lien_fin = '';
                                    }
                                }

                                $buttons = '';

                                $class = ($this->colonne_class != '' ? ' ' . $plage_ref[$this->colonne_class] : '');
                                if ($this->colonne_api_rest != '' && $plage_ref[$this->colonne_api_rest] != '') {
                                    $class .= ' resizable draggable';
                                }
                                $decalage_planning = isset($plage_ref['decalage_planning']) ? $plage_ref['decalage_planning'] : 0;
                                $decalage_planning_width = isset($plage_ref['decalage_planning_width']) ? $plage_ref['decalage_planning_width'] : 0;
                                $zindex = $plage_ref['z-index'];

                                // decalage_planning

                                $h1 = $this->conversion_heure_en_secondes($heure_debut);
                                $h2 = $this->conversion_heure_en_secondes($heure_fin);

                                if ($heure_start > $h2)
                                    $h2 = $heure_start;
                                if ($heure_end < $h1)
                                    $h1 = $heure_end;
                                if ($heure_end < $h2)
                                    $h2 = $heure_end;

                                $heure_debut = substr($heure_debut, 0, 5);
                                $heure_fin = substr($heure_fin, 0, 5);

                                $code .= '<div class="position_zone_planning' . $class . '" style="margin-left: ' . ($decalage_planning + 15) . 'px; margin-top: ' . ((($h1 - $h2_prec) / $coef) + 2) . 'px; margin-right: ' . ($decalage_planning_width - $decalage_planning) . 'px;">' . $lien_debut . '<div class="zone_planning" style="height: ' . ((($h2 - $h1) / $coef) - 1) . 'px; z-index: ' . $zindex . ';"' . ($this->colonne_api_rest != '' ? ' code_js_api="' . $plage_ref[$this->colonne_api_rest] . '"' : '') . '><div class="block_planning"><div class="plage_horaire">' . $heure_debut . ' – ' . $heure_fin . '</div><div class="informations">' . $buttons . $texte . '</div></div></div>' . $lien_fin . '</div>';

                                $h2_prec = $h2 + $coef;
                            }
                        }

                        $code .= '</div>';

                        $code .= '</td>';
                    }
                    $date->add($diff1Day);
                }

                $pas_v = round(60 / $precision_mn);

                $code .= '</tr></table></div></div>';

                $code_js .= '$(function(){';

                // resizable
                $code_js .= '$(".resizable .zone_planning").resizable({';

                $code_js .= 'handles: "s",';

                $code_js .= 'resize: function( event, ui ) {';

                $code_js .= 'var nb_sec_par_pixel = 86400 / ' . $nb_pixels_hauteur . ';';
                $code_js .= 'var new_d = Math.round( ( (ui.size.height+1) * nb_sec_par_pixel ) / 3600 * ' . $pas_v . ' ) / ' . $pas_v . ';';
                $code_js .= 'ui.size.height = Math.round( new_d * 3600 / nb_sec_par_pixel )-1;';
                $code_js .= 'var margin_bottom = ( ui.originalSize.height - ui.size.height );';
                $code_js .= '$( this ).css("margin-bottom", margin_bottom + "px");';
                $code_js .= '$( this ).addClass("devant");';

                $code_js .= '},';

                $code_js .= 'stop: function( event, ui ) {';

                $code_js .= 'var nb_sec_par_pixel = 86400 / ' . $nb_pixels_hauteur . ';';
                $code_js .= 'var new_d = Math.round( ( (ui.size.height+1) * nb_sec_par_pixel ) / 3600 * ' . $pas_v . ' ) / ' . $pas_v . ';';
                $code_js .= 'var old_d = Math.round( ( (ui.originalSize.height+1) * nb_sec_par_pixel ) / 3600 * ' . $pas_v . ' ) / ' . $pas_v . ';';
                $code_js .= 'var dif_d = new_d - old_d;';
                $code_js .= 'var adresse_api = ui.helper[0].attributes[2].nodeValue;';
                $code_js .= '$.ajax({url: adresse_api + "?auth=main",';
                $code_js .= 'type: "PUT",';
                $code_js .= 'dataType: "json",';
                $code_js .= 'contentType: "application/json",';
                $code_js .= 'processData: true,';
                $code_js .= 'headers : {"Content-Type" : "application/x-www-form-urlencoded; charset=UTF-8"},';
                $code_js .= 'data: JSON.stringify( { dif_j: 0, dif_h: 0, dif_d: dif_d } ),';
                $code_js .= 'success: function(data){ actualiser_les_champs(); },';
                $code_js .= 'error: function(){ actualiser_les_champs(); }});';

                $code_js .= '}';

                $code_js .= '});';

                // draggable
                $code_js .= '$(".draggable .zone_planning").draggable({';

                $code_js .= 'drag: function( event, ui ) {';

                $code_js .= 'var nb_sec_par_pixel = 86400 / ' . $nb_pixels_hauteur . ';';
                $code_js .= 'var nb_pixel_par_jour = ' . count($jours) . ' / $("#calendrier_jour_' . $compteur_calendrier . '").width();';
                $code_js .= 'var dif_h = Math.round( ( ui.position.top * nb_sec_par_pixel ) / 3600 * ' . $pas_v . ' ) / ' . $pas_v . ';';
                $code_js .= 'var dif_j = Math.round( ui.position.left * nb_pixel_par_jour );';
                $code_js .= 'ui.position.top = Math.round( dif_h * 3600 / nb_sec_par_pixel );';
                $code_js .= '$( this ).addClass("devant");';
                if ($this->blocage_y) {
                    $code_js .= 'ui.position.left = 0;';
                } else {
                    $code_js .= 'ui.position.left = Math.round( dif_j / nb_pixel_par_jour );';
                }

                $code_js .= '},';

                $code_js .= 'stop: function( event, ui ) {';

                $code_js .= 'var nb_sec_par_pixel = 86400 / ' . $nb_pixels_hauteur . ';';
                $code_js .= 'var nb_pixel_par_jour = ' . count($jours) . ' / $("#calendrier_jour_' . $compteur_calendrier . '").width();';
                $code_js .= 'var dif_h = Math.round( ( ui.position.top * nb_sec_par_pixel ) / 3600 * ' . $pas_v . ' ) / ' . $pas_v . ';';
                $code_js .= 'var dif_j = Math.round( ui.position.left * nb_pixel_par_jour );';
                $code_js .= 'var adresse_api = ui.helper[0].attributes[2].nodeValue;';
                $code_js .= '$.ajax({url: adresse_api + "?auth=main",';
                $code_js .= 'type: "PUT",';
                $code_js .= 'dataType: "json",';
                $code_js .= 'contentType: "application/json",';
                $code_js .= 'processData: true,';
                $code_js .= 'headers : {"Content-Type" : "application/x-www-form-urlencoded; charset=UTF-8"},';
                $code_js .= 'data: JSON.stringify( { dif_j: dif_j, dif_h: dif_h, dif_d: 0 } ),';
                $code_js .= 'success: function(data){ actualiser_les_champs(); },';
                $code_js .= 'error: function(){ actualiser_les_champs(); }});';

                $code_js .= '}';

                $code_js .= '});';

                $code_js .= '});';
            }

            $date_start_local = $date->format('Y-m-d');
            $date_end_local = date_fin_semaine($date_start_local);
        }

        if ($this->colonne_api_rest != '') {
            if (function_exists('mf_injection_js')) {
                mf_injection_js($code_js);
            }
            $code_js_ecapsule = '<input type="hidden" id="calendrier_code_js" value=\'' . $code_js . '\'>';
        } else {
            $code_js_ecapsule = '';
        }

        return $code . $code_js_ecapsule;
    }
}
