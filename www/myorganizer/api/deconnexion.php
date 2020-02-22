<?php

$time_start = microtime(true);

include __DIR__ . '/../../../systeme/myorganizer/api_espace_privee.php';

$mf_connexion->deconnexion($mf_token);

$time_end = microtime(true);
$retour_json['duree'] = round( $time_end-$time_start, 4 );

vue_api_echo( $retour_json );
