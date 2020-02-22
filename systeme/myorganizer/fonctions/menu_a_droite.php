<?php

class Menu_a_droite
{

    // partie privee
    private $racine;

    private $page;

    private $liste_titres;

    private $texte_bouton_deconnexion = 'Déconnexion';

    // partie publique
    function __construct()
    {
        $racine = (HTTPS_ON ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
        $page = $racine;
        $p = 0;
        $i = 0;
        while ($i = stripos($racine, FIN_ADRESSE_RACINE . '/', $i + 1)) {
            $p = $i;
        }
        $this->racine = substr($racine, 0, $p + strlen(FIN_ADRESSE_RACINE . '/'));
        $this->liste_titres = array();

        $i = stripos($racine, '.php', $p);
        $this->page = substr($page, 0, $i + 4);
    }

    function ajouter_bouton($titre, $lien, $type = 'lien', $id = '', $parametres = array())
    { // ex : $parametres = array('onload'=>'do domething', ...);
        $this->liste_titres[] = array(
            'type' => $type,
            'text' => $titre,
            'link' => $lien,
            'id' => $id,
            'parametres' => $parametres
        );
    }

    function ajouter_bouton_page_tableau($num_page, $courante = false)
    {
        $this->liste_titres[] = array(
            'type' => 'page',
            'text' => '<span class="mini">Page </span>' . $num_page,
            'link' => '?page=' . $num_page,
            'courante' => $courante
        );
    }

    function ajouter_bouton_formulaire($titre)
    {
        $this->liste_titres[] = array(
            'type' => 'formulaire',
            'text' => $titre
        );
    }

    function ajouter_bouton_deconnexion()
    {
        $this->liste_titres[] = array(
            'type' => 'deconnexion'
        );
    }

    function raz_boutons()
    {
        $this->liste_titres = array();
    }

    function generer_code()
    {
        $code = '<nav><ul id="menu_appli">';
        foreach ($this->liste_titres as $titre) {
            $code .= '<li>' . $this->get_code_html($titre, '', false) . '</li>';
        }
        $code .= '</ul></nav>';
        return $code;
    }

    private function get_code_html($titre, $class, $local)
    {
        global $cle_aleatoire;
        $parametres = '';
        if (isset($titre['parametres'])) {
            foreach ($titre['parametres'] as $param => $doing) {
                $parametres .= ' ' . $param . '="' . $doing . '"';
            }
        }
        $add = '';
        if (stripos(' ' . $class . ' ', ' btn ') !== false) {
            $add .= ' role="button"';
        }
        if ($titre['type'] == 'lien') {
            $cle = (stripos($titre['link'], '?') !== false ? '&' : '?') . 'secur=' . $cle_aleatoire;
            $href = ($titre['link'] != '#' ? ($local ? '' : $this->racine) . $titre['link'] . $cle : '#');
            if ($href == '#') {
                $href = '#999';
            }
            return '<a class="' . $class . '" href="' . $href . '"' . $parametres . $add . '>' . ($titre['text']) . '</a>';
        } elseif ($titre['type'] == 'js') {
            return '<a class="' . $class . '" href="#999" onclick="' . $titre['link'] . '"' . $parametres . $add . '>' . ($titre['text']) . '</a>';
        } elseif ($titre['type'] == 'formulaire') {
            return '<a class="' . $class . '" href="#999" onclick="document.getElementById(\'myForm\').submit()"' . $add . '>' . ($titre['text']) . '</a>';
        } elseif ($titre['type'] == 'page') {
            return '<a class="' . $class . '" href="' . $this->page . $titre['link'] . '" class="num_page' . ($titre['courante'] ? ' current' : '') . '"' . $add . '>' . $titre['text'] . '</a>';
        } elseif ($titre['type'] == 'deconnexion') {
            return '<a class="' . $class . '" href="?act=deconnexion" class="bouton_deconnexion"' . $add . '>' . $this->texte_bouton_deconnexion . '</a>';
        }
    }

    function generer_code_bouton($id, $class = 'btn btn-primary', $local = false)
    {
        $code = '';
        foreach ($this->liste_titres as $key => $titre) {
            if ($titre['id'] == $id) {
                $code = $this->get_code_html($titre, $class, $local);
                unset($this->liste_titres[$key]);
            }
        }
        return $code;
    }

    function get_racine()
    {
        return $this->racine;
    }

    function set_texte_bouton_deconnexion($texte)
    {
        $this->texte_bouton_deconnexion = $texte;
    }
}
