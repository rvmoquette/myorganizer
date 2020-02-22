<?php declare(strict_types=1);

$mf_dictionnaire_db['Code_user'] = ['type' => 'INT', 'entite' => 'user'];
$mf_dictionnaire_db['user_Login'] = ['type' => 'VARCHAR', 'entite' => 'user'];
$mf_dictionnaire_db['user_Password'] = ['type' => 'PASSWORD', 'entite' => 'user'];
$mf_dictionnaire_db['user_Email'] = ['type' => 'VARCHAR', 'entite' => 'user'];
$mf_dictionnaire_db['user_Admin'] = ['type' => 'BOOL', 'entite' => 'user'];
$mf_dictionnaire_db['Code_task'] = ['type' => 'INT', 'entite' => 'task'];
$mf_dictionnaire_db['task_Name'] = ['type' => 'VARCHAR', 'entite' => 'task'];
$mf_dictionnaire_db['task_Date_creation'] = ['type' => 'DATE', 'entite' => 'task'];
$mf_dictionnaire_db['task_Description'] = ['type' => 'TEXT', 'entite' => 'task'];
$mf_dictionnaire_db['task_Workflow'] = ['type' => 'INT', 'entite' => 'task'];
$mf_dictionnaire_db['Code_label'] = ['type' => 'INT', 'entite' => 'label'];
$mf_dictionnaire_db['label_Name'] = ['type' => 'VARCHAR', 'entite' => 'label'];
$mf_dictionnaire_db['a_task_label_Link'] = ['type' => 'BOOL', 'entite' => 'a_task_label'];
$mf_dictionnaire_db['a_user_task_Link'] = ['type' => 'BOOL', 'entite' => 'a_user_task'];
