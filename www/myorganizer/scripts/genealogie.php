<?php declare(strict_types=1);

/*
    +------------------------------+
    |  NE PAS MODIFIER CE FICHIER  |
    +------------------------------+
*/

if ($Code_user == 0 && $Code_task != 0 && isset($table_task)) $Code_user = $table_task->mf_convertir_Code_task_vers_Code_user($Code_task);

$mf_contexte = [];
$mf_contexte['Code_user'] = $Code_user;
$mf_contexte['Code_task'] = $Code_task;
$mf_contexte['Code_label'] = $Code_label;
