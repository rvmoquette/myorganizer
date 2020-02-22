<?php
$desactivation_actualisation_outils_developpeur = true;

include __DIR__ . '/../../systeme/myorganizer/dblayer_light.php';

session_write_close();

$nom_fichier = (isset($_GET['n']) ? $_GET['n'] : '');
$libelle = nom_fichier_formate(isset($_GET['l']) ? $_GET['l'] : 'fichier');
$mode_image = (isset($_GET['img']) ? $_GET['img'] == 1 : true);

if (! Hook_mf_systeme::est_fichier_public($nom_fichier)) {
    if (isset($_GET['mf_token'])) {
        $token = $_GET['mf_token'];
        $mf_connexion = new Mf_Connexion(true);
    } else {
        $token = (isset($_SESSION[PREFIXE_SESSION]['token']) ? $_SESSION[PREFIXE_SESSION]['token'] : '');
        $mf_connexion = new Mf_Connexion();
    }
    if (! $mf_connexion->est_connecte($token)) {
        header('HTTP/1.0 404 Not Found');
        exit();
    }
}

if (! Hook_mf_systeme::controle_acces_fichier($nom_fichier)) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

$fichier = new Fichier();
$ext = $fichier->get_extention($nom_fichier);

if ($mode_image && ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')) { // application/image
    $format_png = (isset($_GET['format_png']) ? round($_GET['format_png']) == 1 : $ext == 'png');
    if (! $format_png && $ext == 'png') { // conversion systématique en jpeg car moins de place
        $ext = 'jpeg';
    }
    // cache client sur une journée
    header('Pragma: public');
    header('Cache-Control: max-age=' . (DUREE_CACHE_NAV_CLIENT_EN_JOURS * 86400));
    // image
    header('Content-Type: image;');
    header('Content-Disposition: inline; filename="' . $libelle . '.' . $ext . '"');
    $cache = new Cachehtml('', 'images');
    if (! $cache->start('', 525600)) // cache sur 365 jours
    {
        $width = (isset($_GET['width']) ? round($_GET['width']) : IMAGES_LARGEUR_MAXI);
        $height = (isset($_GET['height']) ? round($_GET['height']) : IMAGES_HAUTEUR_MAXI);
        $troncage = (isset($_GET['troncage']) ? $_GET['troncage'] == 1 : false);
        $rotate = (isset($_GET['rotate']) ? round($_GET['rotate']) % 360 : 0);
        $zoom = (isset($_GET['zoom']) ? round($_GET['zoom']) : 100);
        $xpos = (isset($_GET['xpos']) ? round($_GET['xpos']) : 50);
        $ypos = (isset($_GET['ypos']) ? round($_GET['ypos']) : 50);
        $quality = 75;
        $erase_image = false;
        $pourcentage_color = (isset($_GET['pourcentage_color']) ? round($_GET['pourcentage_color']) : 100);

        transformer_image($nom_fichier, $format_png, $width, $height, $troncage, $rotate, $zoom, $xpos, $ypos, $quality, $erase_image, $pourcentage_color);

        $cache->end();
    }
} elseif ($f = $fichier->get($nom_fichier)) {
    header('Content-Type: ' . $fichier->get_mine_type($ext));
    header('Content-Disposition: attachment; filename="' . $libelle . '.' . $ext . '"');
    echo $f;
} else {
    header('HTTP/1.0 404 Not Found');
}

fermeture_connexion_db();
