<?php

class colonnes
{

    // partie privée
    private $colonnes;

    // partie publique
    function __construct()
    {
        $this->colonnes = array();
    }

    function ajouter_colonne($code)
    {
        $this->colonnes[] = $code;
    }

    function generer_code($nb_colonne_min = 0)
    {
        $code = "<table class=\"tableau_structure\"><tbody><tr>";
        $first = true;
        $nb_colonne = 0;

        $c = count($this->colonnes);

        foreach ($this->colonnes as $colonne) {
            if ($c <= $nb_colonne_min) {
                $code .= "<td class=\"tableau_structure_colonne" . ($first ? " first" : "") . "\">$colonne</td>";
                $first = false;
                $nb_colonne ++;
            } else {
                $c --;
            }
        }
        while ($nb_colonne < $nb_colonne_min) {
            $code .= "<td class=\"tableau_structure_colonne" . ($first ? " first" : "") . "\"></td>";
            $first = false;
            $nb_colonne ++;
        }
        $code .= "</tbody></tr></table>";
        return $code;
    }
}
