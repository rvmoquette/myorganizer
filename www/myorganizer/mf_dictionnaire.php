<?php declare(strict_types=1);

include __DIR__ . '/../../systeme/myorganizer/espace_publique.php';

function mf_documentation_matrice_workflow(string $colonne)
{
    global $lang_standard, $mf_dictionnaire_db;
    // Activation de la fonction ?
    $activation = false;
    foreach ($lang_standard["{$colonne}_"] as $key_1 => $val_1) {
        foreach ($lang_standard["{$colonne}_"] as $key_2 => $val_2) {
            $ok = false;
            eval('$ok = in_array(' . $key_2 . ', Hook_' . $mf_dictionnaire_db[$colonne]['entite'] . '::workflow__' . $colonne . '(' . $key_1 . '));');
            if (! $ok) {
                $activation = true;
            }
        }
    }

    if ($activation) {
        echo '<hr>';
        echo '<div style="background-color: #ffffff;"><table class="table table-bordered" style="font-size: 0.85em;">';
        // première ligne
        echo '<tr>';
        echo '<td style="text-align: center; vertical-align: middle; font-weight: bold;">' . htmlspecialchars($colonne) . '</td>';
        foreach ($lang_standard["{$colonne}_"] as $val) {
            echo '<td style="text-align: center;">' . htmlspecialchars($val) . '</td>';
        }
        echo '</tr>';
        // les lignes suivantes
        foreach ($lang_standard["{$colonne}_"] as $key_1 => $val_1) {
            echo '<tr><td>' . htmlspecialchars($val_1) . '</td>';
            foreach ($lang_standard["{$colonne}_"] as $key_2 => $val_2) {
                if ($key_1 == $key_2) {
                    echo '<td style="background-color: #000000;"></td>';
                }
                else {
                    $ok = false;
                    eval('$ok = in_array(' . $key_2 . ', Hook_' . $mf_dictionnaire_db[$colonne]['entite'] . '::workflow__' . $colonne . '(' . $key_1 . '));');
                    if ($ok) {
                        echo '<td style="text-align: center; vertical-align: middle; background-color: lightgreen; color: black;">x</td>';
                    } else {
                        echo '<td></td>';
                    }
                }
            }
            echo '</tr>';
        }
        echo '</table></div>';
    }
}

?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title>Dictionnaire d'information API</title>
<style>
.table td { border-color: #B0B0B0; }
.table .bg-primary th { color: black; }
</style>
</head>
<body style="background-color: #dddddd;">
<div class="jumbotron jumbotron-fluid">
<div class="container-fluid">
<h1 class="display-4">Dictionnaire des données du projet myorganizer</h1>
<p class="lead">Date : 22/02/2020 12:22:26</p>
</div>
</div>
<div class="container-fluid">
<div style="page-break-inside: avoid;">
<div class="alert alert-primary" role="alert">
<h4 class="alert-heading">Entité "USER"</h4>
<hr>
<table class="table table-sm">
<thead><tr><th>Description</th><th>Type de donnée</th><th>Par défaut</th><th>Nom de la colonne (BD)</th></tr></thead>
<tbody>
<tr scope="row" class="bg-primary" style="color: white;"><th style="width: 25%;">Identifiant : Utilisateur</th><th style="width: 40%;">Entier naturel</th><th style="width: 10%;"></th><th style="width: 25%;"><small>Code_user</small></th></tr>
<tr scope="row"><td>Identifiant</td><td>Chaine jusqu'à 255 caractères</td><td>''</td><td><small>user_Login</small></td></tr>
<tr scope="row"><td>Mot de passe</td><td>Chaine jusqu'à 255 caractères</td><td>''</td><td><small>user_Password</small></td></tr>
<tr scope="row"><td>Email</td><td>Chaine jusqu'à 255 caractères</td><td>''</td><td><small>user_Email</small></td></tr>
</tbody>
</table>
<hr>
<ul>
<li>Nom des valeurs : {user_Login}</li>
<li>Tri des données par défaut : ['user_Login' => 'ASC']</li>
</ul>
</div>

</div>
<div style="page-break-inside: avoid;">
<div class="alert alert-primary" role="alert">
<h4 class="alert-heading">Entité "TASK"</h4>
<hr>
<table class="table table-sm">
<thead><tr><th>Description</th><th>Type de donnée</th><th>Par défaut</th><th>Nom de la colonne (BD)</th></tr></thead>
<tbody>
<tr scope="row" class="bg-primary" style="color: white;"><th style="width: 25%;">Identifiant : Tâches</th><th style="width: 40%;">Entier naturel</th><th style="width: 10%;"></th><th style="width: 25%;"><small>Code_task</small></th></tr>
<tr scope="row"><td>Nom</td><td>Chaine jusqu'à 255 caractères</td><td>''</td><td><small>task_Name</small></td></tr>
<tr scope="row"><td>Créée le</td><td>Date (sans l'heure)</td><td>''</td><td><small>task_Date_creation</small></td></tr>
<tr scope="row"><td>Description</td><td>Texte sur plusieurs lignes</td><td>''</td><td><small>task_Description</small></td></tr>
<tr scope="row"><td>Etat</td><td>Nombre entier<br><span style="font-size: 0.85em; font-style: italic;">[1 => "Créé", 2 => "En cours", 3 => "Validé"]</span></td><td>1</td><td><small>task_Workflow</small></td></tr>
<tr scope="row" class="bg-warning" style="font-style: italic;"><td>Lien vers l'entité : USER</td><td>Référence</td><td></td><td><small>Code_user</small></td></tr>
</tbody>
</table>
<?php mf_documentation_matrice_workflow('task_Workflow');?>
<hr>
<ul>
<li>Nom des valeurs : {task_Name}</li>
<li>Tri des données par défaut : ['task_Name' => 'ASC']</li>
</ul>
</div>

</div>
<div style="page-break-inside: avoid;">
<div class="alert alert-primary" role="alert">
<h4 class="alert-heading">Entité "LABEL"</h4>
<hr>
<table class="table table-sm">
<thead><tr><th>Description</th><th>Type de donnée</th><th>Par défaut</th><th>Nom de la colonne (BD)</th></tr></thead>
<tbody>
<tr scope="row" class="bg-primary" style="color: white;"><th style="width: 25%;">Identifiant : Classement</th><th style="width: 40%;">Entier naturel</th><th style="width: 10%;"></th><th style="width: 25%;"><small>Code_label</small></th></tr>
<tr scope="row"><td>Nom</td><td>Chaine jusqu'à 255 caractères</td><td>''</td><td><small>label_Name</small></td></tr>
</tbody>
</table>
<hr>
<ul>
<li>Nom des valeurs : {label_Name}</li>
<li>Tri des données par défaut : ['label_Name' => 'ASC']</li>
</ul>
</div>

</div>
<div style="page-break-inside: avoid;">
<div class="alert alert-secondary" role="alert">
<h4 class="alert-heading">Association "A_TASK_LABEL"</h4>
<hr>
<table class="table table-sm">
<thead><tr><th>Description</th><th>Type de donnée</th><th>Par défaut</th><th>Nom de la colonne (BD)</th></tr></thead>
<tbody>
<tr scope="row" class="bg-warning" style="font-style: italic;"><th style="width: 25%;">Lien vers l'entité : TASK</th><th style="width: 40%;">Référence</th><th style="width: 10%;"></th><th style="width: 25%;"><small>Code_task</small></th></tr>
<tr scope="row" class="bg-warning" style="font-style: italic;"><th style="width: 25%;">Lien vers l'entité : LABEL</th><th style="width: 40%;">Référence</th><th style="width: 10%;"></th><th style="width: 25%;"><small>Code_label</small></th></tr>
<tr scope="row"><td>a_task_label_Link</td><td>Booléen (oui/non)<br><span style="font-size: 0.85em; font-style: italic;">[1 => "Oui", 0 => "Non"]</span></td><td>0</td><td><small>a_task_label_Link</small></td></tr>
</tbody>
</table>
<hr>
<ul>
<li>Nom des valeurs : {Code_task} - {Code_label}</li>
<li>Tri des données par défaut : ['a_task_label_Link' => 'ASC']</li>
</ul>
</div>

</div>
<div style="page-break-inside: avoid;">
<div class="alert alert-secondary" role="alert">
<h4 class="alert-heading">Association "A_USER_TASK"</h4>
<hr>
<table class="table table-sm">
<thead><tr><th>Description</th><th>Type de donnée</th><th>Par défaut</th><th>Nom de la colonne (BD)</th></tr></thead>
<tbody>
<tr scope="row" class="bg-warning" style="font-style: italic;"><th style="width: 25%;">Lien vers l'entité : USER</th><th style="width: 40%;">Référence</th><th style="width: 10%;"></th><th style="width: 25%;"><small>Code_user</small></th></tr>
<tr scope="row" class="bg-warning" style="font-style: italic;"><th style="width: 25%;">Lien vers l'entité : TASK</th><th style="width: 40%;">Référence</th><th style="width: 10%;"></th><th style="width: 25%;"><small>Code_task</small></th></tr>
<tr scope="row"><td>a_user_task_Link</td><td>Booléen (oui/non)<br><span style="font-size: 0.85em; font-style: italic;">[1 => "Oui", 0 => "Non"]</span></td><td>0</td><td><small>a_user_task_Link</small></td></tr>
</tbody>
</table>
<hr>
<ul>
<li>Nom des valeurs : {Code_user} - {Code_task}</li>
<li>Tri des données par défaut : ['a_user_task_Link' => 'ASC']</li>
</ul>
</div>

</div>
<hr><footer><p>© My Framework 2020</p></footer>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
