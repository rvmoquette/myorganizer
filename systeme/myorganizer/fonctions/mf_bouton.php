<?php

class MF_Bouton
{

    // partie privee
    private $racine;

    private $page;

    private $liste_titres;

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

    function creer_bouton($titre, $lien, $type = 'absolu')
    {
        return $this->generer_code(array(
            'type' => $type,
            'text' => $titre,
            'link' => $lien
        ));
    }

    function creer_bouton_formulaire($titre)
    {
        return $this->generer_code(array(
            'type' => 'formulaire',
            'text' => $titre
        ));
    }

    private function generer_code($titre)
    {
        global $cle_aleatoire;

        $code = '';

        if ($titre['type'] == 'lien') {
            $cle = (stripos($titre['link'], '?') > 0 ? '&' : '?') . 'secur=' . $cle_aleatoire;
            $code .= '<a class="bouton_mobile" href="' . $this->racine . $titre['link'] . $cle . '">' . $titre['text'] . '</a>';
        } elseif ($titre['type'] == 'absolu') {
            $cle = (stripos($titre['link'], '?') > 0 ? '&' : '?') . 'secur=' . $cle_aleatoire;
            $code .= '<a class="bouton_mobile" href="' . $titre['link'] . $cle . '">' . $titre['text'] . '</a>';
        } elseif ($titre['type'] == 'js') {
            $code .= '<a class="bouton_mobile" onclick="' . $titre['link'] . '">' . $titre['text'] . '</a>';
        } elseif ($titre['type'] == 'formulaire') {
            $code .= '<a class="bouton_mobile" href="#" class="current" onclick="document.getElementById(\'myForm\').submit()">' . $titre['text'] . '</a>';
        }

        return $code;
    }
}
