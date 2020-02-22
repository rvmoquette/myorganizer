<?php

include __DIR__ . '/../../systeme/myorganizer/espace_publique.php';

header('Content-type: application/vnd.ms-excel');
header('Content-disposition: attachment; filename="export_csv_'.get_now().'.csv"');

if ( isset($_GET['cle']) ) { $cle = $_GET['cle']; } else { $cle = ''; }

$Cache = new Cache();

echo $Cache->read($cle, 60);
