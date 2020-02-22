<?php
include __DIR__ . '/../../systeme/myorganizer/dblayer_light.php';
if (isset($_GET['code'])) {
    $ch = curl_init("https://www.googleapis.com/oauth2/v4/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "code={$_GET['code']}&client_id=" . GOOGLE_CLIENT_ID . "&client_secret=" . GOOGLE_CLIENT_SECRET . "&redirect_uri=" . urldecode(ADRESSE_SITE . "mf_auth_google.php") . "&grant_type=authorization_code");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $r = json_decode(curl_exec($ch), true);
    curl_close($ch);
    if (isset($r['access_token'])) {
        $access_token = $r['access_token'];
        $ch = curl_init("https://openidconnect.googleapis.com/v1/userinfo?access_token=$access_token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $r = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $email = $r['email'];
        $email_verified = $r['email_verified'];
        $id_google = $r['sub'];
        if ($email_verified) { // email vérifié
            $mf_connexion = new Mf_Connexion();
            $id = $mf_connexion->rechercher_un_email($email);
            if ($id > 0) {
                if ($token = $mf_connexion->connexion_par_id($id)) {
                    $_SESSION[PREFIXE_SESSION]['token'] = $token;
                }
            } else {
                Hook_mf_systeme::script_inscription_via_compte_oauth2($email, $id_google, 1);
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
