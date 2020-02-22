<?php

class Fiche
{

    // partie privee
    private $entite;

    // partie publique
    function __construct()
    {
        $this->entites = array();
    }

    function ajouter_liste($legend, $liste, $title, $lien, $message_vide, $class_fieldset = "")
    {
        $this->entites[] = array(
            "type" => "liste",
            "legend" => $legend,
            "liste" => $liste,
            "title" => $title,
            "lien" => $lien,
            "empty_message" => $message_vide,
            "class_fieldset" => $class_fieldset
        );
    }

    function ajouter_texte($legend, $texte, $title, $lien, $message_vide)
    {
        $this->entites[] = array(
            "type" => "texte",
            "legend" => $legend,
            "texte" => $texte,
            "title" => $title,
            "lien" => $lien,
            "empty_message" => $message_vide
        );
    }

    function ajouter_element_html($legend, $element_html, $lien, $title, $empty_message, $class_fieldset = "")
    {
        $this->entites[] = array(
            "type" => "element_html",
            "legend" => $legend,
            "element_html" => $element_html,
            "title" => $title,
            "lien" => $lien,
            "empty_message" => $empty_message,
            "class_fieldset" => $class_fieldset
        );
    }

    function ajouter_tableau($legend, $liste_Code_colonne, $tableau, $title, $lien, $message_vide)
    {
        $this->entites[] = array(
            "type" => "tableau_old",
            "legend" => $legend,
            "liste_Code_colonne" => $liste_Code_colonne,
            "tableau" => $tableau,
            "title" => $title,
            "lien" => $lien,
            "empty_message" => $message_vide
        );
    }

    function generer_code()
    {
        global $lang_standard;
        $code = "<div class='fiche'>";
        foreach ($this->entites as $entite) {
            $code .= "<fieldset" . (isset($entite['class_fieldset']) ? " class=\"{$entite['class_fieldset']}\"" : "") . ">";
            $legend = htmlspecialchars($entite['legend']);
            $lien = htmlspecialchars($entite['lien']);
            $title = htmlspecialchars($entite['title']);
            $empty_message = htmlspecialchars($entite['empty_message']);
            $code .= "<legend class=\"masquer_pour_impression\">$legend</legend>";
            if ($lien != "") {
                $code .= "<a href=\"$lien\" title=\"$title\"><span class='crayon'>&nbsp;</span></a>";
            }
            $vide = true;
            if ($entite['type'] == "liste") {
                $liste = $entite['liste'];
                $code .= "<ul>";
                foreach ($liste as $value) {
                    $code .= "<li>" . htmlspecialchars($value) . "</li>";
                    $vide = false;
                }
                $code .= "</ul>";
            } elseif ($entite['type'] == "texte") {
                $texte = text_html_br($entite['texte']);
                if ($texte != "") {
                    $vide = false;
                    $code .= "<span>$texte</span>";
                }
            } elseif ($entite['type'] == "tableau_old") {

                $liste_Code_colonne = $entite['liste_Code_colonne'];
                $tableau = $entite['tableau'];

                $code .= "<table>";
                $code .= "<tr>";
                foreach ($liste_Code_colonne as $Code_colonne) {
                    $code .= "<th>{$lang_standard[$Code_colonne]}</th>";
                }
                $code .= "</tr>";
                foreach ($tableau as $ligne) {
                    $code .= "<tr>";
                    foreach ($liste_Code_colonne as $Code_colonne) {
                        $code .= "<td>{$ligne[$Code_colonne]}</td>";
                    }
                    $code .= "</tr>";
                    $vide = false;
                }

                $code .= "</table>";
            } elseif ($entite['type'] == "element_html") {

                $code .= $entite['element_html'];
            }
            if ($vide)
                $code .= "<span class='vide'>$empty_message</span>";
            $code .= "</fieldset>";
        }
        $code .= "</div>";
        return $code;
    }
}
