<?php

include __DIR__ . '/../../systeme/myorganizer/espace_publique.php';

if ( isset($_GET['cle']) ) { $cle = $_GET['cle']; } else { $cle = ''; }

$Cache = new Cache();

$code_html = $Cache->read($cle, 60);

echo recuperer_gabarit('main/page.html', array(
        '{titre_page}' => 'Impression',
        '{css}' => '',
        '{js}' => '',
        '{menu_principal}' => '',
        '{fil_ariane}' => '',
        '{sections}' => $code_html,
        '{menu_secondaire}' => '',
        '{script_end}' => '<script>$(function(){window.print();});</script>',
        '{header}' => '',
        '{footer}' => ''
));
