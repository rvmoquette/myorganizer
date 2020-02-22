<?php
include __DIR__ . '/dblayer.php';

$mf_connexion = new Mf_Connexion();
$menu_a_droite = new Menu_a_droite();
$fil_ariane = new Fil_Ariane("Accueil", "");
$trans = array();

if (isset($_GET['act'])) {
    $mf_action = $_GET['act'];
} else {
    $mf_action = '';
}

/*
 * +-------------+
 * | deconnexion |
 * +-------------+
 */

if ($mf_action == 'deconnexion') {
    $mf_connexion = new Mf_Connexion();
    if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
        $token = $_SESSION[PREFIXE_SESSION]['token'];
        $mf_connexion->deconnexion($token);
    }
}

/*
 * +-----------------+
 * | test si connect |
 * +-----------------+
 */

if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
    $mf_connexion = new Mf_Connexion();
    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'])) {
        unset($_SESSION[PREFIXE_SESSION]['token']);
    }
}

session_write_close();

if (! Hook_mf_systeme::controle_acces_controller(get_nom_page_courante())) {
    http_response_code(403);
    echo recuperer_gabarit('main/page_Forbidden.html', [
        '{footer}' => recuperer_gabarit('main/footer.html', [])
    ], true);
    fermeture_connexion_db();
    exit();
}

$cache = new Cachehtml((isset($user_courant['Code_user']) ? $user_courant['Code_user'] : 0) . '-' . mf_get_trace_session());
$cache->activer_compression_html();

$menu_a_droite->set_texte_bouton_deconnexion(htmlspecialchars(get_titre_ligne_table('user', $user_courant)) . ', <i>déconnexion</i>');
