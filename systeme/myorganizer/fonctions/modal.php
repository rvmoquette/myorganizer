<?php

class Modal
{

    // partie privee
    private $libelle_bouton;

    private $titre;

    private $contenu;

    private static $n = 0;

    // partie publique
    function __construct($libelle_bouton, $titre, $contenu)
    {
        $this->libelle_bouton = $libelle_bouton;
        $this->titre = $titre;
        $this->contenu = $contenu;
        self::$n ++;
    }

    function generer_code()
    {
        return $this->generer_code_button() . $this->generer_code_modal();
    }

    function generer_code_modal()
    {
        $code = '';

        $code .= '<div class="modal fade" id="modal' . self::$n . '" role="dialog">';
        $code .= '<div class="modal-dialog">';

        $code .= '<div class="modal-content">';
        $code .= '<div class="modal-header">';
        $code .= '<button type="button" class="close" data-dismiss="modal">&times;</button>';
        $code .= '<h4 class="modal-title">' . htmlspecialchars($this->titre) . '</h4>';
        $code .= '</div>';
        $code .= '<div class="modal-body">';
        $code .= $this->contenu;
        $code .= '</div>';
        $code .= '<div class="modal-footer">';
        $code .= '<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>';
        $code .= '</div>';
        $code .= '</div>';

        $code .= '</div>';
        $code .= '</div>';

        return $code;
    }

    function generer_code_button()
    {
        $code = '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal' . self::$n . '">' . htmlspecialchars($this->libelle_bouton) . '</button>';

        return $code;
    }
}
