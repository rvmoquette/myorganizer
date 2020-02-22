<?php declare(strict_types=1);

/*
    +------------------------------+
    |  NE PAS MODIFIER CE FICHIER  |
    +------------------------------+
*/

$Code_user = (isset($_GET['Code_user']) ? intval($_GET['Code_user']) : 0);
$Code_task = (isset($_GET['Code_task']) ? intval($_GET['Code_task']) : 0);
$Code_label = (isset($_GET['Code_label']) ? intval($_GET['Code_label']) : 0);

require __DIR__ . '/genealogie.php';

function mf_Code_user(): int { global $mf_contexte; return (int) $mf_contexte['Code_user']; }
function mf_Code_task(): int { global $mf_contexte; return (int) $mf_contexte['Code_task']; }
function mf_Code_label(): int { global $mf_contexte; return (int) $mf_contexte['Code_label']; }
