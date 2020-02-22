<?php declare(strict_types=1);

$lang_standard['Code_task'] = 'task';

$lang_standard['task_Name'] = 'task_Name';
$lang_standard['task_Date_creation'] = 'task_Date_creation';
$lang_standard['task_Description'] = 'task_Description';
$lang_standard['task_Workflow'] = 'task_Workflow';
$lang_standard['task_Workflow_'] = [1 => "Etat 1", 2 => "Etat 2", 3 => "Etat 3"];

$lang_standard['bouton_ajouter_task'] = 'Ajouter';
$lang_standard['bouton_creer_task'] = 'Creer';
$lang_standard['bouton_modifier_task'] = 'Modifier';
$lang_standard['bouton_supprimer_task'] = 'Supprimer';
$lang_standard['bouton_modifier_task_Name'] = 'Modifier';
$lang_standard['bouton_modifier_task_Date_creation'] = 'Modifier';
$lang_standard['bouton_modifier_task_Description'] = 'Modifier';
$lang_standard['bouton_modifier_task_Workflow'] = 'Modifier';
$lang_standard['bouton_modifier_task__Code_user'] = 'Modifier';

$lang_standard['form_add_task'] = 'form_add_task';
$lang_standard['form_edit_task'] = 'form_edit_task';
$lang_standard['form_delete_task'] = 'form_delete_task';

$mf_titre_ligne_table['task'] = '{task_Name}';

$mf_tri_defaut_table['task'] = ['task_Name' => 'ASC'];

$lang_standard['libelle_liste_task'] = 'libelle_liste_task';

$mf_initialisation['task_Name'] = '';
$mf_initialisation['task_Date_creation'] = '';
$mf_initialisation['task_Description'] = '';
$mf_initialisation['task_Workflow'] = 1;

// code_erreur

$mf_libelle_erreur[REFUS_TASK__AJOUTER] = 'REFUS_task__AJOUTER';
$mf_libelle_erreur[ERR_TASK__AJOUTER__CODE_USER_INEXISTANT] = 'ERR_task__AJOUTER__Code_user_INEXISTANT';
$mf_libelle_erreur[REFUS_TASK__AJOUT_BLOQUEE] = 'REFUS_task__AJOUT_BLOQUEE';
$mf_libelle_erreur[ERR_TASK__AJOUTER__TASK_WORKFLOW_NON_VALIDE] = 'ERR_task__AJOUTER__task_Workflow_NON_VALIDE';
$mf_libelle_erreur[ERR_TASK__AJOUTER__AJOUT_REFUSE] = 'ERR_task__AJOUTER__AJOUT_REFUSE';
$mf_libelle_erreur[ERR_TASK__AJOUTER_3__ECHEC_AJOUT] = 'ERR_task__AJOUTER_3__ECHEC_AJOUT';
$mf_libelle_erreur[ERR_TASK__MODIFIER__CODE_TASK_INEXISTANT] = 'ERR_task__MODIFIER__Code_task_INEXISTANT';
$mf_libelle_erreur[REFUS_TASK__MODIFIER] = 'REFUS_task__MODIFIER';
$mf_libelle_erreur[ERR_TASK__MODIFIER__CODE_USER_INEXISTANT] = 'ERR_task__MODIFIER__Code_user_INEXISTANT';
$mf_libelle_erreur[ACCES_CODE_TASK_REFUSE] = 'Tentative d\'accès \'Code_task\' non autorisé';
$mf_libelle_erreur[REFUS_TASK__MODIFICATION_BLOQUEE] = 'REFUS_task__MODIFICATION_BLOQUEE';
$mf_libelle_erreur[ERR_TASK__MODIFIER__TASK_WORKFLOW_NON_VALIDE] = 'ERR_task__MODIFIER__task_Workflow_NON_VALIDE';
$mf_libelle_erreur[ERR_TASK__MODIFIER__TASK_WORKFLOW__HORS_WORKFLOW] = 'ERR_task__MODIFIER__task_Workflow__HORS_WORKFLOW';
$mf_libelle_erreur[ERR_TASK__MODIFIER__AUCUN_CHANGEMENT] = 'ERR_task__MODIFIER__AUCUN_CHANGEMENT';
$mf_libelle_erreur[ERR_TASK__MODIFIER_3__AUCUN_CHANGEMENT] = 'ERR_task__MODIFIER_3__AUCUN_CHANGEMENT';
$mf_libelle_erreur[ERR_TASK__MODIFIER_4__AUCUN_CHANGEMENT] = 'ERR_task__MODIFIER_4__AUCUN_CHANGEMENT';
$mf_libelle_erreur[ERR_TASK__SUPPRIMER_2__CODE_TASK_INEXISTANT] = 'ERR_task__SUPPRIMER_2__Code_task_INEXISTANT';
$mf_libelle_erreur[REFUS_TASK__SUPPRIMER] = 'REFUS_task__SUPPRIMER';
$mf_libelle_erreur[REFUS_TASK__SUPPRESSION_BLOQUEE] = 'REFUS_task__SUPPRESSION_BLOQUEE';
$mf_libelle_erreur[ERR_TASK__SUPPRIMER__REFUSEE] = 'ERR_task__SUPPRIMER__REFUSEE';
$mf_libelle_erreur[ERR_TASK__SUPPRIMER_2__REFUSEE] = 'ERR_task__SUPPRIMER_2__REFUSEE';
$mf_libelle_erreur[ERR_TASK__SUPPRIMER_3__REFUSEE] = 'ERR_task__SUPPRIMER_3__REFUSEE';
