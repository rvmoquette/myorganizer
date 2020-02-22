<?php
include __DIR__ . '/../../systeme/myorganizer/dblayer_light.php';
if (isset($_GET['code'])) {
    $ch = curl_init("https://graph.facebook.com/v5.0/oauth/access_token?client_id=" . FACEBOOK_CLIENT_ID . "&redirect_uri=" . urldecode(ADRESSE_SITE . "mf_auth_facebook.php") . "&client_secret=" . FACEBOOK_CLIENT_SECRET . "&code={$_GET['code']}");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $r = json_decode(curl_exec($ch), true);
    curl_close($ch);
    if (isset($r['access_token'])) {
        $access_token = $r['access_token'];
        $ch = curl_init("https://graph.facebook.com/me?fields=email&access_token=$access_token");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $r = json_decode(curl_exec($ch));
        curl_close($ch);
        $email = $r->email;
        $id_facebook = $r->id;
        if (test_email_valide($email)) {
            $mf_connexion = new Mf_Connexion();
            $id = $mf_connexion->rechercher_un_email($email);
            if ($id > 0) {
                if ($token = $mf_connexion->connexion_par_id($id)) {
                    $_SESSION[PREFIXE_SESSION]['token'] = $token;
                }
            } else {
                Hook_mf_systeme::script_inscription_via_compte_oauth2($email, $id_facebook, 2);
                $id = $mf_connexion->rechercher_un_email($email);
                if ($id > 0) {
                    if ($token = $mf_connexion->connexion_par_id($id)) {
                        $_SESSION[PREFIXE_SESSION]['token'] = $token;
                    }
                }
            }
        }
    }
}
header('Location: ' . ADRESSE_SITE);
