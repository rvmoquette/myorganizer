<?php

$time_start = microtime(true);

include __DIR__ . '/../../../systeme/myorganizer/api_espace_publique.php';

$mf_login = lecture_parametre_api('mf_login', '');
$mf_pwd = lecture_parametre_api('mf_pwd', '');

$mf_connexion = new Mf_Connexion(true);
if ( $mf_token = $mf_connexion->connexion($mf_login, $mf_pwd) )
{
    $retour_json['mf_token'] = $mf_token;
}
else
{
    $retour_json['code_erreur'] = 2;
}

$time_end = microtime(true);
$retour_json['duree'] = round( $time_end-$time_start, 4 );

vue_api_echo( $retour_json );
