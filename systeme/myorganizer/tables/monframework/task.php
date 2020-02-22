<?php declare(strict_types=1);

/*
    +------------------------------+
    |  NE PAS MODIFIER CE FICHIER  |
    +------------------------------+
*/

class task_monframework extends entite_monframework
{

    private static $initialisation = true;
    private static $auto_completion = 0;
    private static $actualisation_en_cours = false;
    private static $cache_db;
    private static $maj_droits_ajouter_en_cours = false;
    private static $maj_droits_modifier_en_cours = false;
    private static $maj_droits_supprimer_en_cours = false;
    private static $lock = [];

    public function __construct()
    {
        if (self::$initialisation)
        {
            include_once __DIR__ . '/../../erreurs/erreurs__task.php';
            self::$initialisation = false;
            Hook_task::initialisation();
            self::$cache_db = new Mf_Cachedb('task');
        }
        if (!self::$actualisation_en_cours)
        {
            self::$actualisation_en_cours=true;
            Hook_task::actualisation();
            self::$actualisation_en_cours=false;
        }
    }

    public static function mf_raz_instance()
    {
        self::$initialisation = true;
    }

    public static function initialiser_structure()
    {
        global $mf_initialisation;

        if (! test_si_table_existe(inst('task'))) {
            executer_requete_mysql('CREATE TABLE '.inst('task').'(Code_task BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (Code_task)) ENGINE=MyISAM;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $liste_colonnes = lister_les_colonnes(inst('task'));

        if (isset($liste_colonnes['task_Name'])) {
            if (typeMyql2Sql($liste_colonnes['task_Name']['Type'])!='VARCHAR') {
                executer_requete_mysql('ALTER TABLE '.inst('task').' CHANGE task_Name task_Name VARCHAR(255);', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['task_Name']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD task_Name VARCHAR(255);', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('task').' SET task_Name=' . format_sql('task_Name', $mf_initialisation['task_Name']) . ';', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        if (isset($liste_colonnes['task_Date_creation'])) {
            if (typeMyql2Sql($liste_colonnes['task_Date_creation']['Type'])!='DATE') {
                executer_requete_mysql('ALTER TABLE '.inst('task').' CHANGE task_Date_creation task_Date_creation DATE;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['task_Date_creation']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD task_Date_creation DATE;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('task').' SET task_Date_creation=' . format_sql('task_Date_creation', $mf_initialisation['task_Date_creation']) . ';', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        if (isset($liste_colonnes['task_Description'])) {
            if (typeMyql2Sql($liste_colonnes['task_Description']['Type'])!='TEXT') {
                executer_requete_mysql('ALTER TABLE '.inst('task').' CHANGE task_Description task_Description TEXT;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['task_Description']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD task_Description TEXT;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('task').' SET task_Description=' . format_sql('task_Description', $mf_initialisation['task_Description']) . ';', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        if (isset($liste_colonnes['task_Workflow'])) {
            if (typeMyql2Sql($liste_colonnes['task_Workflow']['Type'])!='INT') {
                executer_requete_mysql('ALTER TABLE '.inst('task').' CHANGE task_Workflow task_Workflow INT;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['task_Workflow']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD task_Workflow INT;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('task').' SET task_Workflow=' . format_sql('task_Workflow', $mf_initialisation['task_Workflow']) . ';', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $liste_colonnes_a_indexer = [];

        if (isset($liste_colonnes['Code_user'])) {
            unset($liste_colonnes['Code_user']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD Code_user BIGINT UNSIGNED NOT NULL;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['Code_user'] = 'Code_user';

        if (isset($liste_colonnes['mf_signature'])) {
            unset($liste_colonnes['mf_signature']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD mf_signature VARCHAR(255);', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_signature'] = 'mf_signature';

        if (isset($liste_colonnes['mf_cle_unique'])) {
            unset($liste_colonnes['mf_cle_unique']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD mf_cle_unique VARCHAR(255);', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_cle_unique'] = 'mf_cle_unique';

        if (isset($liste_colonnes['mf_date_creation'])) {
            unset($liste_colonnes['mf_date_creation']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD mf_date_creation DATETIME;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_date_creation'] = 'mf_date_creation';

        if (isset($liste_colonnes['mf_date_modification'])) {
            unset($liste_colonnes['mf_date_modification']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('task').' ADD mf_date_modification DATETIME;', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_date_modification'] = 'mf_date_modification';

        unset($liste_colonnes['Code_task']);

        foreach ($liste_colonnes as $field => $value) {
            executer_requete_mysql('ALTER TABLE '.inst('task').' DROP COLUMN '.$field.';', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `' . inst('task') . '`;', false);
        $mf_liste_requete_index = [];
        while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
            $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
        }
        mysqli_free_result($res_requete_index);
        foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
            if (isset($liste_colonnes_a_indexer[$mf_colonne_indexee])) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
        }
        foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
            executer_requete_mysql('ALTER TABLE `' . inst('task') . '` ADD INDEX(`' . $colonnes_a_indexer . '`);', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
    }

    public function mf_ajouter(string $task_Name, string $task_Date_creation, string $task_Description, ?int $task_Workflow, int $Code_user, ?bool $force = false)
    {
        if ($force === null) {
            $force = false;
        }
        $Code_task = 0;
        $code_erreur = 0;
        // Typage
        $task_Name = (string) $task_Name;
        $task_Date_creation = format_date($task_Date_creation);
        $task_Description = (string) $task_Description;
        $task_Workflow = is_null($task_Workflow) || $task_Workflow === '' ? null : (int) $task_Workflow;
        // Fin typage
        Hook_task::pre_controller($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user);
        if (!$force)
        {
            if (!self::$maj_droits_ajouter_en_cours)
            {
                self::$maj_droits_ajouter_en_cours = true;
                Hook_task::hook_actualiser_les_droits_ajouter($Code_user);
                self::$maj_droits_ajouter_en_cours = false;
            }
        }
        if ( !$force && !mf_matrice_droits(['task__AJOUTER']) ) $code_erreur = REFUS_TASK__AJOUTER;
        elseif ( !$this->mf_tester_existance_Code_user($Code_user) ) $code_erreur = ERR_TASK__AJOUTER__CODE_USER_INEXISTANT;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user)) $code_erreur = ACCES_CODE_USER_REFUSE;
        elseif (! Hook_task::autorisation_ajout($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user) ) $code_erreur = REFUS_TASK__AJOUT_BLOQUEE;
        elseif (! controle_parametre("task_Workflow", $task_Workflow) ) $code_erreur = ERR_TASK__AJOUTER__TASK_WORKFLOW_NON_VALIDE;
        else {
            Hook_task::data_controller($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user);
            $mf_signature = text_sql(Hook_task::calcul_signature($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user));
            $mf_cle_unique = text_sql(Hook_task::calcul_cle_unique($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user));
            $task_Name = text_sql($task_Name);
            $task_Date_creation = format_date($task_Date_creation);
            $task_Description = text_sql($task_Description);
            $task_Workflow = is_null($task_Workflow) ? 'NULL' : (int) $task_Workflow;
            $requete = "INSERT INTO ".inst('task')." ( mf_signature, mf_cle_unique, mf_date_creation, mf_date_modification, task_Name, task_Date_creation, task_Description, task_Workflow, Code_user ) VALUES ( '$mf_signature', '$mf_cle_unique', '".get_now()."', '".get_now()."', '$task_Name', ".( $task_Date_creation!='' ? "'$task_Date_creation'" : 'NULL' ).", '$task_Description', $task_Workflow, $Code_user );";
            executer_requete_mysql($requete, array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $Code_task = requete_mysql_insert_id();
            if ($Code_task==0)
            {
                $code_erreur = ERR_TASK__AJOUTER__AJOUT_REFUSE;
            } else {
                self::$cache_db->clear();
                Hook_task::ajouter( $Code_task );
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'Code_task' => $Code_task, 'callback' => ( $code_erreur==0 ? Hook_task::callback_post($Code_task) : null )];
    }

    public function mf_creer(int $Code_user, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        global $mf_initialisation;
        $task_Name = $mf_initialisation['task_Name'];
        $task_Date_creation = $mf_initialisation['task_Date_creation'];
        $task_Description = $mf_initialisation['task_Description'];
        $task_Workflow = $mf_initialisation['task_Workflow'];
        // Typage
        $Code_user = (int) $Code_user;
        $task_Name = (string) $task_Name;
        $task_Date_creation = format_date($task_Date_creation);
        $task_Description = (string) $task_Description;
        $task_Workflow = is_null($task_Workflow) || $task_Workflow === '' ? null : (int) $task_Workflow;
        // Fin typage
        return $this->mf_ajouter($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user, $force);
    }

    public function mf_ajouter_2(array $ligne, bool $force = null) // array('colonne1' => 'valeur1',  [...] )
    {
        if ($force === null) {
            $force = false;
        }
        global $mf_initialisation;
        $Code_user = (isset($ligne['Code_user']) ? $ligne['Code_user'] : get_user_courant('Code_user'));
        $task_Name = (isset($ligne['task_Name'])?$ligne['task_Name']:$mf_initialisation['task_Name']);
        $task_Date_creation = (isset($ligne['task_Date_creation'])?$ligne['task_Date_creation']:$mf_initialisation['task_Date_creation']);
        $task_Description = (isset($ligne['task_Description'])?$ligne['task_Description']:$mf_initialisation['task_Description']);
        $task_Workflow = (isset($ligne['task_Workflow'])?$ligne['task_Workflow']:$mf_initialisation['task_Workflow']);
        // Typage
        $Code_user = (int) $Code_user;
        $task_Name = (string) $task_Name;
        $task_Date_creation = format_date($task_Date_creation);
        $task_Description = (string) $task_Description;
        $task_Workflow = is_null($task_Workflow) || $task_Workflow === '' ? null : (int) $task_Workflow;
        // Fin typage
        return $this->mf_ajouter($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user, $force);
    }

    public function mf_ajouter_3(array $lignes) // array( array( 'colonne1' => 'valeur1', 'colonne2' => 'valeur2',  [...] ), [...] )
    {
        global $mf_initialisation;
        $code_erreur = 0;
        $values = '';
        foreach ($lignes as $ligne) {
            $Code_user = (int)(isset($ligne['Code_user']) ? intval($ligne['Code_user']) : 0);
            $task_Name = text_sql(isset($ligne['task_Name'])?$ligne['task_Name']:$mf_initialisation['task_Name']);
            $task_Date_creation = format_date(isset($ligne['task_Date_creation'])?$ligne['task_Date_creation']:$mf_initialisation['task_Date_creation']);
            $task_Description = text_sql(isset($ligne['task_Description'])?$ligne['task_Description']:$mf_initialisation['task_Description']);
            $task_Workflow = is_null(isset($ligne['task_Workflow'])?$ligne['task_Workflow']:$mf_initialisation['task_Workflow']) ? 'NULL' : (int) isset($ligne['task_Workflow'])?$ligne['task_Workflow']:$mf_initialisation['task_Workflow'];
            if ($Code_user != 0)
            {
                $values .= ($values!="" ? "," : "")."('$task_Name', ".( $task_Date_creation!='' ? "'$task_Date_creation'" : 'NULL' ).", '$task_Description', $task_Workflow, $Code_user)";
            }
        }
        if ($values!='')
        {
            $requete = "INSERT INTO ".inst('task')." ( task_Name, task_Date_creation, task_Description, task_Workflow, Code_user ) VALUES $values;";
            executer_requete_mysql( $requete , array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $n = requete_mysqli_affected_rows();
            if ($n < count($lignes))
            {
                $code_erreur = ERR_TASK__AJOUTER_3__ECHEC_AJOUT;
            }
            if ($n > 0) {
                self::$cache_db->clear();
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_actualiser_signature(int $Code_task)
    {
        $task = $this->mf_get_2($Code_task, ['autocompletion' => false]);
        $mf_signature = text_sql(Hook_task::calcul_signature($task['task_Name'], $task['task_Date_creation'], $task['task_Description'], $task['task_Workflow'], $task['Code_user']));
        $mf_cle_unique = text_sql(Hook_task::calcul_cle_unique($task['task_Name'], $task['task_Date_creation'], $task['task_Description'], $task['task_Workflow'], $task['Code_user']));
        $table = inst('task');
        executer_requete_mysql("UPDATE $table SET mf_signature='$mf_signature', mf_cle_unique='$mf_cle_unique' WHERE Code_task=$Code_task;", array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        if (requete_mysqli_affected_rows() == 1) {
            self::$cache_db->clear();
        }
    }

    public function mf_modifier( int $Code_task, string $task_Name, string $task_Date_creation, string $task_Description, ?int $task_Workflow, ?int $Code_user = null, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        // Typage
        $task_Name = (string) $task_Name;
        $task_Date_creation = format_date($task_Date_creation);
        $task_Description = (string) $task_Description;
        $task_Workflow = is_null($task_Workflow) || $task_Workflow === '' ? null : (int) $task_Workflow;
        // Fin typage
        Hook_task::pre_controller($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user, $Code_task);
        if (! $force) {
            if (! self::$maj_droits_modifier_en_cours) {
                self::$maj_droits_modifier_en_cours = true;
                Hook_task::hook_actualiser_les_droits_modifier($Code_task);
                self::$maj_droits_modifier_en_cours = false;
            }
        }
        $task = $this->mf_get_2( $Code_task, ['autocompletion' => false, 'masquer_mdp' => false]);
        if ( !$force && !mf_matrice_droits(['task__MODIFIER']) ) $code_erreur = REFUS_TASK__MODIFIER;
        elseif ( !$this->mf_tester_existance_Code_task($Code_task) ) $code_erreur = ERR_TASK__MODIFIER__CODE_TASK_INEXISTANT;
        elseif ($Code_user != 0 && ! $this->mf_tester_existance_Code_user($Code_user)) $code_erreur = ERR_TASK__MODIFIER__CODE_USER_INEXISTANT;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task)) $code_erreur = ACCES_CODE_TASK_REFUSE;
        elseif ($Code_user != 0 && CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user)) $code_erreur = ACCES_CODE_USER_REFUSE;
        elseif ( !Hook_task::autorisation_modification($Code_task, $task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user) ) $code_erreur = REFUS_TASK__MODIFICATION_BLOQUEE;
        elseif (! in_array($task_Workflow, liste_union_A_et_B([$task_Workflow], Hook_task::workflow__task_Workflow($task['task_Workflow'])))) $code_erreur = ERR_TASK__MODIFIER__TASK_WORKFLOW__HORS_WORKFLOW;
        elseif (! controle_parametre("task_Workflow", $task_Workflow)) $code_erreur = ERR_TASK__MODIFIER__TASK_WORKFLOW_NON_VALIDE;
        else {
            if (! isset(self::$lock[$Code_task])) {
                self::$lock[$Code_task] = 0;
            }
            if (self::$lock[$Code_task] == 0) {
                self::$cache_db->add_lock((string) $Code_task);
            }
            self::$lock[$Code_task]++;
            Hook_task::data_controller($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user, $Code_task);
            $mf_colonnes_a_modifier=[];
            $bool__task_Name = false; if ($task_Name !== $task['task_Name']) {Hook_task::data_controller__task_Name($task['task_Name'], $task_Name, $Code_task); if ( $task_Name !== $task['task_Name'] ) { $mf_colonnes_a_modifier[] = 'task_Name=' . format_sql('task_Name', $task_Name); $bool__task_Name = true;}}
            $bool__task_Date_creation = false; if ($task_Date_creation !== $task['task_Date_creation']) {Hook_task::data_controller__task_Date_creation($task['task_Date_creation'], $task_Date_creation, $Code_task); if ( $task_Date_creation !== $task['task_Date_creation'] ) { $mf_colonnes_a_modifier[] = 'task_Date_creation=' . format_sql('task_Date_creation', $task_Date_creation); $bool__task_Date_creation = true;}}
            $bool__task_Description = false; if ($task_Description !== $task['task_Description']) {Hook_task::data_controller__task_Description($task['task_Description'], $task_Description, $Code_task); if ( $task_Description !== $task['task_Description'] ) { $mf_colonnes_a_modifier[] = 'task_Description=' . format_sql('task_Description', $task_Description); $bool__task_Description = true;}}
            $bool__task_Workflow = false; if ($task_Workflow !== $task['task_Workflow']) {Hook_task::data_controller__task_Workflow($task['task_Workflow'], $task_Workflow, $Code_task); if ( $task_Workflow !== $task['task_Workflow'] ) { $mf_colonnes_a_modifier[] = 'task_Workflow=' . format_sql('task_Workflow', $task_Workflow); $bool__task_Workflow = true;}}
            $bool__Code_user = false; if ($Code_user != 0 && $Code_user != $task['Code_user'] ) { Hook_task::data_controller__Code_user($task['Code_user'], $Code_user, $Code_task); if ( $Code_user != 0 && $Code_user != $task['Code_user'] ) { $mf_colonnes_a_modifier[] = 'Code_user = ' . $Code_user; $bool__Code_user = true; } }
            if (count($mf_colonnes_a_modifier) > 0) {
                $mf_signature = text_sql(Hook_task::calcul_signature($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user));
                $mf_cle_unique = text_sql(Hook_task::calcul_cle_unique($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user));
                $mf_colonnes_a_modifier[] = 'mf_signature=\'' . $mf_signature . '\'';
                $mf_colonnes_a_modifier[] = 'mf_cle_unique=\'' . $mf_cle_unique . '\'';
                $mf_colonnes_a_modifier[] = 'mf_date_modification=\'' . get_now() . '\'';
                $requete = 'UPDATE '.inst('task').' SET ' . enumeration($mf_colonnes_a_modifier) . ' WHERE Code_task = ' . $Code_task . ';';
                executer_requete_mysql($requete, array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if (requete_mysqli_affected_rows() == 0) {
                    $code_erreur = ERR_TASK__MODIFIER__AUCUN_CHANGEMENT;
                } else {
                    self::$cache_db->clear();
                    Hook_task::modifier($Code_task, $bool__task_Name, $bool__task_Date_creation, $bool__task_Description, $bool__task_Workflow, $bool__Code_user);
                }
            } else {
                $code_erreur = ERR_TASK__MODIFIER__AUCUN_CHANGEMENT;
            }
            self::$lock[$Code_task]--;
            if (self::$lock[$Code_task] == 0) {
                self::$cache_db->release_lock((string) $Code_task);
                unset(self::$lock[$Code_task]);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'callback' => ($code_erreur == 0 ? Hook_task::callback_put($Code_task) : null)];
    }

    public function mf_modifier_2(array $lignes, ?bool $force = null) // array( $Code_task => array('colonne1' => 'valeur1',  [...] ) )
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        foreach ($lignes as $Code_task => $colonnes) {
            if ($code_erreur == 0) {
                $Code_task = intval($Code_task);
                $task = $this->mf_get_2($Code_task, ['autocompletion' => false]);
                if (! $force) {
                    if (! self::$maj_droits_modifier_en_cours) {
                        self::$maj_droits_modifier_en_cours = true;
                        Hook_task::hook_actualiser_les_droits_modifier($Code_task);
                        self::$maj_droits_modifier_en_cours = false;
                    }
                }
                $Code_user = ( isset($colonnes['Code_user']) && ( $force || mf_matrice_droits(['api_modifier_ref__task__Code_user', 'task__MODIFIER']) ) ? $colonnes['Code_user'] : (isset($task['Code_user']) ? $task['Code_user'] : 0 ));
                $task_Name = ( isset($colonnes['task_Name']) && ( $force || mf_matrice_droits(['api_modifier__task_Name', 'task__MODIFIER']) ) ? $colonnes['task_Name'] : ( isset($task['task_Name']) ? $task['task_Name'] : '' ) );
                $task_Date_creation = ( isset($colonnes['task_Date_creation']) && ( $force || mf_matrice_droits(['api_modifier__task_Date_creation', 'task__MODIFIER']) ) ? $colonnes['task_Date_creation'] : ( isset($task['task_Date_creation']) ? $task['task_Date_creation'] : '' ) );
                $task_Description = ( isset($colonnes['task_Description']) && ( $force || mf_matrice_droits(['api_modifier__task_Description', 'task__MODIFIER']) ) ? $colonnes['task_Description'] : ( isset($task['task_Description']) ? $task['task_Description'] : '' ) );
                $task_Workflow = ( isset($colonnes['task_Workflow']) && ( $force || mf_matrice_droits(['api_modifier__task_Workflow', 'task__MODIFIER']) ) ? $colonnes['task_Workflow'] : ( isset($task['task_Workflow']) ? $task['task_Workflow'] : '' ) );
                // Typage
                $Code_user = (int) $Code_user;
                $task_Name = (string) $task_Name;
                $task_Date_creation = format_date($task_Date_creation);
                $task_Description = (string) $task_Description;
                $task_Workflow = is_null($task_Workflow) || $task_Workflow === '' ? null : (int) $task_Workflow;
                // Fin typage
                $retour = $this->mf_modifier($Code_task, $task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user, true);
                if ($retour['code_erreur'] != 0 && $retour['code_erreur'] != ERR_TASK__MODIFIER__AUCUN_CHANGEMENT) {
                    $code_erreur = $retour['code_erreur'];
                }
                if (count($lignes) == 1) {
                    return $retour;
                }
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_modifier_3(array $lignes) // array( $Code_task => array('colonne1' => 'valeur1',  [...] ) )
    {
        $code_erreur = 0;
        $modifs = false;

        // transformation des lignes en colonnes
        $valeurs_en_colonnes=[];
        $indices_par_colonne=[];
        $liste_valeurs_indexees=[];
        foreach ( $lignes as $Code_task => $colonnes )
        {
            foreach ($colonnes as $colonne => $valeur)
            {
                if ( $colonne=='task_Name' || $colonne=='task_Date_creation' || $colonne=='task_Description' || $colonne=='task_Workflow' || $colonne=='Code_user' )
                {
                    $valeurs_en_colonnes[$colonne][$Code_task]=$valeur;
                    $indices_par_colonne[$colonne][]=$Code_task;
                    $liste_valeurs_indexees[$colonne][''.$valeur][]=$Code_task;
                }
            }
        }

        // fabrication des requetes
        foreach ( $valeurs_en_colonnes as $colonne => $valeurs )
        {
            if ( count($liste_valeurs_indexees[$colonne]) > 3 )
            {
                $modification_sql = $colonne . ' = CASE Code_task';
                foreach ( $valeurs as $Code_task => $valeur )
                {
                    $modification_sql .= ' WHEN ' . $Code_task . ' THEN ' . format_sql($colonne, $valeur);
                }
                $modification_sql .= ' END';
                $perimetre = Sql_Format_Liste($indices_par_colonne[$colonne]);
                executer_requete_mysql('UPDATE ' . inst('task') . ' SET ' . $modification_sql . ' WHERE Code_task IN ' . $perimetre . ';', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if ( requete_mysqli_affected_rows()!=0 )
                {
                    $modifs = true;
                }
            }
            else
            {
                foreach ( $liste_valeurs_indexees[$colonne] as $valeur => $indices_par_valeur )
                {
                    $perimetre = Sql_Format_Liste($indices_par_valeur);
                    executer_requete_mysql('UPDATE ' . inst('task') . ' SET ' . $colonne . ' = ' . format_sql($colonne, $valeur) . ' WHERE Code_task IN ' . $perimetre . ';', array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                    if ( requete_mysqli_affected_rows()!=0 )
                    {
                        $modifs = true;
                    }
                }
            }
        }

        if ( ! $modifs && $code_erreur==0 )
        {
            $code_erreur = ERR_TASK__MODIFIER_3__AUCUN_CHANGEMENT;
        }
        if ($modifs) {
            self::$cache_db->clear();
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_modifier_4( int $Code_user, array $data, ?array $options = null /* $options = array( 'cond_mysql' => [], 'limit' => 0 ) */ ) // $data = array('colonne1' => 'valeur1', ... )
    {
        $code_erreur = 0;
        $Code_user = intval($Code_user);
        $mf_colonnes_a_modifier=[];
        if (isset($data['task_Name'])) { $mf_colonnes_a_modifier[] = 'task_Name = ' . format_sql('task_Name', $data['task_Name']); }
        if (isset($data['task_Date_creation'])) { $mf_colonnes_a_modifier[] = 'task_Date_creation = ' . format_sql('task_Date_creation', $data['task_Date_creation']); }
        if (isset($data['task_Description'])) { $mf_colonnes_a_modifier[] = 'task_Description = ' . format_sql('task_Description', $data['task_Description']); }
        if (isset($data['task_Workflow'])) { $mf_colonnes_a_modifier[] = 'task_Workflow = ' . format_sql('task_Workflow', $data['task_Workflow']); }
        if (count($mf_colonnes_a_modifier) > 0) {
            // cond_mysql
            $argument_cond = '';
            if (isset($options['cond_mysql'])) {
                foreach ($options['cond_mysql'] as &$condition) {
                    $argument_cond .= ' AND ('.$condition.')';
                }
                unset($condition);
            }

            // limit
            $limit = 0;
            if (isset($options['limit'])) {
                $limit = intval($options['limit']);
            }

            $requete = 'UPDATE ' . inst('task') . ' SET ' . enumeration($mf_colonnes_a_modifier) . " WHERE 1".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )."$argument_cond" . ( $limit>0 ? ' LIMIT ' . $limit : '' ) . ";";
            executer_requete_mysql( $requete , array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_TASK__MODIFIER_4__AUCUN_CHANGEMENT;
            } else {
                self::$cache_db->clear();
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_supprimer(int $Code_task, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        $Code_task = intval($Code_task);
        if (! $force) {
            if (!self::$maj_droits_supprimer_en_cours)
            {
                self::$maj_droits_supprimer_en_cours = true;
                Hook_task::hook_actualiser_les_droits_supprimer($Code_task);
                self::$maj_droits_supprimer_en_cours = false;
            }
        }
        if ( !$force && !mf_matrice_droits(['task__SUPPRIMER']) ) $code_erreur = REFUS_TASK__SUPPRIMER;
        elseif (! $this->mf_tester_existance_Code_task($Code_task) ) $code_erreur = ERR_TASK__SUPPRIMER_2__CODE_TASK_INEXISTANT;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task)) $code_erreur = ACCES_CODE_TASK_REFUSE;
        elseif ( !Hook_task::autorisation_suppression($Code_task) ) $code_erreur = REFUS_TASK__SUPPRESSION_BLOQUEE;
        else
        {
            $copie__task = $this->mf_get($Code_task, ['autocompletion' => false]);
            $this->supprimer_donnes_en_cascade("task", [$Code_task]);
            $requete = 'DELETE IGNORE FROM ' . inst('task') . ' WHERE Code_task=' . $Code_task . ';';
            executer_requete_mysql($requete, array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_TASK__SUPPRIMER__REFUSEE;
            } else {
                self::$cache_db->clear();
                Hook_task::supprimer($copie__task);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_supprimer_2(array $liste_Code_task, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        $copie__liste_task = $this->mf_lister_2($liste_Code_task, ['autocompletion' => false]);
        $liste_Code_task=[];
        foreach ( $copie__liste_task as $copie__task )
        {
            $Code_task = $copie__task['Code_task'];
            if (!$force)
            {
                if (!self::$maj_droits_supprimer_en_cours)
                {
                    self::$maj_droits_supprimer_en_cours = true;
                    Hook_task::hook_actualiser_les_droits_supprimer($Code_task);
                    self::$maj_droits_supprimer_en_cours = false;
                }
            }
            if ( !$force && !mf_matrice_droits(['task__SUPPRIMER']) ) $code_erreur = REFUS_TASK__SUPPRIMER;
            elseif ( !Hook_task::autorisation_suppression($Code_task) ) $code_erreur = REFUS_TASK__SUPPRESSION_BLOQUEE;
            {
                $liste_Code_task[] = $Code_task;
            }
        }
        if ( $code_erreur==0 && count($liste_Code_task)>0 )
        {
            $this->supprimer_donnes_en_cascade("task", $liste_Code_task);
            $requete = 'DELETE IGNORE FROM ' . inst('task') . ' WHERE Code_task IN ' . Sql_Format_Liste($liste_Code_task) . ';';
            executer_requete_mysql( $requete , array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_TASK__SUPPRIMER_2__REFUSEE;
            } else {
                self::$cache_db->clear();
                Hook_task::supprimer_2($copie__liste_task);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_supprimer_3(array $liste_Code_task)
    {
        $code_erreur = 0;
        if (count($liste_Code_task) > 0) {
            $this->supprimer_donnes_en_cascade("task", $liste_Code_task);
            $requete = 'DELETE IGNORE FROM ' . inst('task') . ' WHERE Code_task IN ' . Sql_Format_Liste($liste_Code_task) . ';';
            executer_requete_mysql( $requete , array_search('task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_TASK__SUPPRIMER_3__REFUSEE;
            } else {
                self::$cache_db->clear();
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_lister_contexte(?bool $contexte_parent = null, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($contexte_parent === null) {
            $contexte_parent = true;
        }
        if ($options === null) {
            $options=[];
        }
        global $mf_contexte, $est_charge;
        if (! $contexte_parent && $mf_contexte['Code_task'] != 0) {
            $task = $this->mf_get( $mf_contexte['Code_task'], $options);
            return [$task['Code_task'] => $task];
        } else {
            return $this->mf_lister(isset($est_charge['user']) ? $mf_contexte['Code_user'] : 0, $options);
        }
    }

    public function mf_lister(?int $Code_user = null, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "task__lister";
        $Code_user = intval($Code_user);
        $cle .= "_{$Code_user}";

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        // tris
        $argument_tris = '';
        if ( ! isset($options['tris']) )
        {
            $options['tris']=[];
        }
        if ( count($options['tris'])==0 )
        {
            global $mf_tri_defaut_table;
            if ( isset($mf_tri_defaut_table['task']) )
            {
                $options['tris'] = $mf_tri_defaut_table['task'];
            }
        }
        foreach ($options['tris'] as $colonne => $tri)
        {
            if ($colonne != 'task_Description') {
                if ( $argument_tris=='' ) { $argument_tris = ' ORDER BY '; } else { $argument_tris .= ', '; }
                if ( $tri!='DESC' ) $tri = 'ASC';
                $argument_tris .= $colonne.' '.$tri;
            }
        }
        $cle .= '_' . $argument_tris;

        // limit
        $argument_limit = '';
        if (isset($options['limit'][0]) && isset($options['limit'][1])) {
            $argument_limit = ' LIMIT ' . $options['limit'][0] . ',' . $options['limit'][1];
        }
        $cle .= '_'.$argument_limit;

        // autocompletion
        $autocompletion = AUTOCOMPLETION_DEFAUT;
        if (isset($options['autocompletion'])) {
            $autocompletion = ($options['autocompletion'] == true);
        }

        // autocompletion_recursive
        $autocompletion_recursive = AUTOCOMPLETION_RECURSIVE_DEFAUT;
        if (isset($options['autocompletion_recursive'])) {
            $autocompletion_recursive = ($options['autocompletion_recursive'] == true);
        }

        // controle_acces_donnees
        $controle_acces_donnees = CONTROLE_ACCES_DONNEES_DEFAUT;
        if (isset($options['controle_acces_donnees'])) {
            $controle_acces_donnees = ($options['controle_acces_donnees'] == true);
        }

        // liste_colonnes_a_selectionner
        $liste_colonnes_a_selectionner = [];
        if (isset($options['liste_colonnes_a_selectionner'])) {
            $liste_colonnes_a_selectionner = $options['liste_colonnes_a_selectionner'];
        }
        $cle .= '_' . enumeration($liste_colonnes_a_selectionner);

        // afficher toutes les colonnes
        $toutes_colonnes = TOUTES_COLONNES_DEFAUT;
        if (count($liste_colonnes_a_selectionner) == 0) {
            if (isset($options['toutes_colonnes'])) {
                $toutes_colonnes = ($options['toutes_colonnes'] == true);
            }
            $cle .= '_' . ($toutes_colonnes ? '1' : '0');
        }

        // maj
        $maj = true;
        if (isset($options['maj'])) {
            $maj = ($options['maj'] == true);
        }
        $cle .= '_'.( $maj ? '1' : '0' );

        $nouvelle_lecture = true;
        $liste = [];
        while ($nouvelle_lecture) {
            $nouvelle_lecture = false;
            $liste_task_pas_a_jour = [];
            if (false === $liste = self::$cache_db->read($cle)) {

                // Indexes
                $liste_colonnes_a_indexer = [];
                if ($argument_cond != '') {
                    if ( strpos($argument_cond, 'task_Name')!==false ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                    if ( strpos($argument_cond, 'task_Date_creation')!==false ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                    if ( strpos($argument_cond, 'task_Workflow')!==false ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
                }
                if (isset($options['tris'])) {
                    if ( isset($options['tris']['task_Name']) ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                    if ( isset($options['tris']['task_Date_creation']) ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                    if ( isset($options['tris']['task_Workflow']) ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    if (false === $mf_liste_requete_index = self::$cache_db->read('task__index')) {
                        $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('task').'`;', false);
                        $mf_liste_requete_index = [];
                        while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                            $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                        }
                        mysqli_free_result($res_requete_index);
                        self::$cache_db->write('task__index', $mf_liste_requete_index);
                    }
                    foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                        if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                    }
                    if (count($liste_colonnes_a_indexer) > 0) {
                        foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                            executer_requete_mysql('ALTER TABLE `'.inst('task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                        }
                        self::$cache_db->clear();
                    }
                }

                if (count($liste_colonnes_a_selectionner) == 0) {
                    if ($toutes_colonnes) {
                        $colonnes = 'Code_task, task_Name, task_Date_creation, task_Description, task_Workflow, Code_user';
                    } else {
                        $colonnes = 'Code_task, task_Name, task_Date_creation, task_Workflow, Code_user';
                    }
                } else {
                    $liste_colonnes_a_selectionner[] = 'Code_task';
                    $colonnes = enumeration($liste_colonnes_a_selectionner, ',');
                }

                $liste = [];
                $liste_task_pas_a_jour = [];
                $res_requete = executer_requete_mysql("SELECT $colonnes FROM " . inst('task') . " WHERE 1{$argument_cond}".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )."{$argument_tris}{$argument_limit};", false);
                while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                    mf_formatage_db_type_php($row_requete);
                    $liste[$row_requete['Code_task']] = $row_requete;
                    if ($maj && ! Hook_task::est_a_jour($row_requete)) {
                        $liste_task_pas_a_jour[$row_requete['Code_task']] = $row_requete;
                        $nouvelle_lecture = true;
                    }
                }
                mysqli_free_result($res_requete);
                if (count($options['tris'])==1 && ! $nouvelle_lecture) {
                    foreach ($options['tris'] as $colonne => $tri) {
                        global $lang_standard;
                        if (isset($lang_standard[$colonne.'_'])) {
                            effectuer_tri_suivant_langue($liste, $colonne, $tri);
                        }
                    }
                }
                if (! $nouvelle_lecture) {
                    self::$cache_db->write($cle, $liste);
                }
            }
            if ($nouvelle_lecture) {
                Hook_task::mettre_a_jour( $liste_task_pas_a_jour );
            }
        }

        foreach ($liste as $elem) {
            if ($controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_task', $elem['Code_task'])) {
                unset($liste[$elem['Code_task']]);
            } else {
                if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                    self::$auto_completion ++;
                    Hook_task::completion($liste[$elem['Code_task']], self::$auto_completion - 1);
                    self::$auto_completion --;
                }
            }
        }

        return $liste;
    }

    public function mf_lister_2(array $liste_Code_task, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        if (count($liste_Code_task) > 0) {
            $cle = "task__mf_lister_2_".Sql_Format_Liste($liste_Code_task);

            // cond_mysql
            $argument_cond = '';
            if (isset($options['cond_mysql'])) {
                foreach ($options['cond_mysql'] as &$condition) {
                    $argument_cond .= " AND ($condition)";
                }
                unset($condition);
            }
            $cle .= "_$argument_cond";

            // tris
            $argument_tris = '';
            if ( ! isset($options['tris']) )
            {
                $options['tris']=[];
            }
            if ( count($options['tris'])==0 )
            {
                global $mf_tri_defaut_table;
                if ( isset($mf_tri_defaut_table['task']) )
                {
                    $options['tris'] = $mf_tri_defaut_table['task'];
                }
            }
            foreach ($options['tris'] as $colonne => $tri)
            {
                if ($colonne != 'task_Description') {
                    if ( $argument_tris=='' ) { $argument_tris = ' ORDER BY '; } else { $argument_tris .= ', '; }
                    if ( $tri!='DESC' ) $tri = 'ASC';
                    $argument_tris .= $colonne.' '.$tri;
                }
            }
            $cle .= '_' . $argument_tris;

            // limit
            $argument_limit = '';
            if (isset($options['limit'][0]) && isset($options['limit'][1])) {
                $argument_limit = ' LIMIT ' . $options['limit'][0] . ',' . $options['limit'][1];
            }
            $cle .= '_'.$argument_limit;

            // autocompletion
            $autocompletion = AUTOCOMPLETION_DEFAUT;
            if (isset($options['autocompletion'])) {
                $autocompletion = ($options['autocompletion'] == true);
            }

            // autocompletion_recursive
            $autocompletion_recursive = AUTOCOMPLETION_RECURSIVE_DEFAUT;
            if (isset($options['autocompletion_recursive'])) {
                $autocompletion_recursive = ($options['autocompletion_recursive'] == true);
            }

            // controle_acces_donnees
            $controle_acces_donnees = CONTROLE_ACCES_DONNEES_DEFAUT;
            if (isset($options['controle_acces_donnees'])) {
                $controle_acces_donnees = ($options['controle_acces_donnees'] == true);
            }

            // liste_colonnes_a_selectionner
            $liste_colonnes_a_selectionner = [];
            if (isset($options['liste_colonnes_a_selectionner'])) {
                $liste_colonnes_a_selectionner = $options['liste_colonnes_a_selectionner'];
            }
            $cle .= '_' . enumeration($liste_colonnes_a_selectionner);

            // afficher toutes les colonnes
            $toutes_colonnes = TOUTES_COLONNES_DEFAUT;
            if (count($liste_colonnes_a_selectionner) == 0) {
                if (isset($options['toutes_colonnes'])) {
                    $toutes_colonnes = ($options['toutes_colonnes'] == true);
                }
                $cle .= '_' . ($toutes_colonnes ? '1' : '0');
            }

            // maj
            $maj = true;
            if (isset($options['maj'])) {
                $maj = ($options['maj'] == true);
            }
            $cle .= '_'.( $maj ? '1' : '0' );

            $nouvelle_lecture = true;
            $liste = [];
            while ($nouvelle_lecture) {
                $nouvelle_lecture = false;
                $liste_task_pas_a_jour = [];
                if (false === $liste = self::$cache_db->read($cle)) {

                    // Indexes
                    $liste_colonnes_a_indexer = [];
                    if ($argument_cond != '') {
                        if ( strpos($argument_cond, 'task_Name')!==false ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                        if ( strpos($argument_cond, 'task_Date_creation')!==false ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                        if ( strpos($argument_cond, 'task_Workflow')!==false ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
                    }
                    if (isset($options['tris'])) {
                        if ( isset($options['tris']['task_Name']) ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                        if ( isset($options['tris']['task_Date_creation']) ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                        if ( isset($options['tris']['task_Workflow']) ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
                    }
                    if (count($liste_colonnes_a_indexer) > 0) {
                        if (false === $mf_liste_requete_index = self::$cache_db->read('task__index')) {
                            $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('task').'`;', false);
                            $mf_liste_requete_index = [];
                            while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                                $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                            }
                            mysqli_free_result($res_requete_index);
                            self::$cache_db->write('task__index', $mf_liste_requete_index);
                        }
                        foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                            if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                        }
                        if (count($liste_colonnes_a_indexer) > 0) {
                            foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                                executer_requete_mysql('ALTER TABLE `'.inst('task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                            }
                            self::$cache_db->clear();
                        }
                    }

                    if (count($liste_colonnes_a_selectionner) == 0) {
                        if ($toutes_colonnes) {
                            $colonnes = 'Code_task, task_Name, task_Date_creation, task_Description, task_Workflow, Code_user';
                        } else {
                            $colonnes = 'Code_task, task_Name, task_Date_creation, task_Workflow, Code_user';
                        }
                    } else {
                        $liste_colonnes_a_selectionner[] = 'Code_task';
                        $colonnes = enumeration($liste_colonnes_a_selectionner, ',');
                    }

                    $liste = [];
                    $liste_task_pas_a_jour = [];
                    $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM ' . inst('task') . " WHERE 1{$argument_cond} AND Code_task IN ".Sql_Format_Liste($liste_Code_task)."{$argument_tris}{$argument_limit};", false);
                    while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                        mf_formatage_db_type_php($row_requete);
                        $liste[$row_requete['Code_task']] = $row_requete;
                        if ($maj && ! Hook_task::est_a_jour($row_requete)) {
                            $liste_task_pas_a_jour[$row_requete['Code_task']] = $row_requete;
                            $nouvelle_lecture = true;
                        }
                    }
                    mysqli_free_result($res_requete);
                    if (count($options['tris']) == 1 && ! $nouvelle_lecture) {
                        foreach ($options['tris'] as $colonne => $tri) {
                            global $lang_standard;
                            if (isset($lang_standard[$colonne.'_'])) {
                                effectuer_tri_suivant_langue($liste, $colonne, $tri);
                            }
                        }
                    }
                    if (! $nouvelle_lecture) {
                        self::$cache_db->write($cle, $liste);
                    }
                }
                if ($nouvelle_lecture) {
                    Hook_task::mettre_a_jour( $liste_task_pas_a_jour );
                }
            }

            foreach ($liste as $elem) {
                if ($controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_task', $elem['Code_task'])) {
                    unset($liste[$elem['Code_task']]);
                } else {
                    if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                        self::$auto_completion ++;
                        Hook_task::completion($liste[$elem['Code_task']], self::$auto_completion - 1);
                        self::$auto_completion --;
                    }
                }
            }

            return $liste;
        } else {
            return [];
        }
    }

    public function mf_lister_3(?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        return $this->mf_lister(null, $options);
    }

    public function mf_get(int $Code_task, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $Code_task = intval($Code_task);
        $retour = [];
        if ( ! CONTROLE_ACCES_DONNEES_DEFAUT || Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task) ) {
            $cle = 'task__get_'.$Code_task;

            // autocompletion
            $autocompletion = AUTOCOMPLETION_DEFAUT;
            if (isset($options['autocompletion'])) {
                $autocompletion = ($options['autocompletion'] == true);
            }

            // autocompletion_recursive
            $autocompletion_recursive = AUTOCOMPLETION_RECURSIVE_DEFAUT;
            if (isset($options['autocompletion_recursive'])) {
                $autocompletion_recursive = ($options['autocompletion_recursive'] == true);
            }

            // afficher toutes les colonnes
            $toutes_colonnes = true;
            if (isset($options['toutes_colonnes'])) {
                $toutes_colonnes = ($options['toutes_colonnes'] == true);
            }
            $cle .= '_' . ($toutes_colonnes ? '1' : '0');

            // maj
            $maj = true;
            if (isset($options['maj'])) {
                $maj = ($options['maj'] == true);
            }
            $cle .= '_' . ($maj ? '1' : '0');

            $nouvelle_lecture = true;
            while ($nouvelle_lecture) {
                $nouvelle_lecture = false;
                if (false === $retour = self::$cache_db->read($cle)) {
                    if ($toutes_colonnes) {
                        $colonnes='Code_task, task_Name, task_Date_creation, task_Description, task_Workflow, Code_user';
                    } else {
                        $colonnes='Code_task, task_Name, task_Date_creation, task_Workflow, Code_user';
                    }
                    $res_requete = executer_requete_mysql("SELECT $colonnes FROM " . inst('task') . ' WHERE Code_task = ' . $Code_task . ';', false);
                    if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                        mf_formatage_db_type_php($row_requete);
                        $retour = $row_requete;
                        if ($maj && ! Hook_task::est_a_jour($row_requete)) {
                            $nouvelle_lecture = true;
                        }
                    } else {
                        $retour = [];
                    }
                    mysqli_free_result($res_requete);
                    if (! $nouvelle_lecture) {
                        self::$cache_db->write($cle, $retour);
                    } else {
                        Hook_task::mettre_a_jour([$row_requete['Code_task'] => $row_requete]);
                    }
                }
            }
            if (isset($retour['Code_task'])) {
                if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                    self::$auto_completion ++;
                    Hook_task::completion($retour, self::$auto_completion - 1);
                    self::$auto_completion --;
                }
            }
        }
        return $retour;
    }

    public function mf_get_last(?int $Code_user = null, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "task__get_last";
        $Code_user = intval($Code_user);
        $cle .= '_' . $Code_user;
        if (false === $retour = self::$cache_db->read($cle)) {
            $Code_task = 0;
            $res_requete = executer_requete_mysql('SELECT Code_task FROM ' . inst('task') . " WHERE 1".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )." ORDER BY mf_date_creation DESC, Code_task DESC LIMIT 0 , 1;", false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_task = intval($row_requete['Code_task']);
            }
            mysqli_free_result($res_requete);
            $retour = $this->mf_get($Code_task, $options);
            self::$cache_db->write($cle, $retour);
        }
        return $retour;
    }

    public function mf_get_2(int $Code_task, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = 'task__get_'.$Code_task;

        // autocompletion
        $autocompletion = AUTOCOMPLETION_DEFAUT;
        if (isset($options['autocompletion'])) {
            $autocompletion = ($options['autocompletion'] == true);
        }

        // autocompletion_recursive
        $autocompletion_recursive = AUTOCOMPLETION_RECURSIVE_DEFAUT;
        if (isset($options['autocompletion_recursive'])) {
            $autocompletion_recursive = ($options['autocompletion_recursive'] == true);
        }

        // afficher toutes les colonnes
        $toutes_colonnes = true;
        if (isset($options['toutes_colonnes'])) {
            $toutes_colonnes = ($options['toutes_colonnes'] == true);
        }
        $cle .= '_' . ($toutes_colonnes ? '1' : '0');

        // maj
        $maj = true;
        if (isset($options['maj'])) {
            $maj = ($options['maj'] == true);
        }
        $cle .= '_' . ($maj ? '1' : '0');

        if (false === $retour = self::$cache_db->read($cle)) {
            if ($toutes_colonnes) {
                $colonnes='Code_task, task_Name, task_Date_creation, task_Description, task_Workflow, Code_user';
            } else {
                $colonnes='Code_task, task_Name, task_Date_creation, task_Workflow, Code_user';
            }
            $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM ' . inst('task') . ' WHERE Code_task = ' . $Code_task . ';', false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                mf_formatage_db_type_php($row_requete);
                $retour = $row_requete;
            } else {
                $retour = [];
            }
            mysqli_free_result($res_requete);
            self::$cache_db->write($cle, $retour);
        }
        if (isset($retour['Code_task'])) {
            if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                self::$auto_completion ++;
                Hook_task::completion($retour, self::$auto_completion - 1);
                self::$auto_completion --;
            }
        }
        return $retour;
    }

    public function mf_prec_et_suiv( int $Code_task, ?int $Code_user = null, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $Code_task = intval($Code_task);
        $liste = $this->mf_lister($Code_user, $options);
        return prec_suiv($liste, $Code_task);
    }

    public function mf_compter(?int $Code_user = null, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = 'task__compter';
        $Code_user = intval($Code_user);
        $cle .= '_{'.$Code_user.'}';

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        if (false === $nb = self::$cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'task_Name')!==false ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                if ( strpos($argument_cond, 'task_Date_creation')!==false ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                if ( strpos($argument_cond, 'task_Workflow')!==false ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = self::$cache_db->read('task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    self::$cache_db->write('task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    self::$cache_db->clear();
                }
            }

            $res_requete = executer_requete_mysql('SELECT count(Code_task) as nb FROM ' . inst('task')." WHERE 1{$argument_cond}".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" ).";", false);
            $row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC);
            mysqli_free_result($res_requete);
            $nb = (int) $row_requete['nb'];
            self::$cache_db->write($cle, $nb);
        }
        return $nb;
    }

    public function mfi_compter( array $interface, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        $Code_user = isset($interface['Code_user']) ? intval($interface['Code_user']) : 0;
        return $this->mf_compter( $Code_user, $options );
    }

    public function mf_liste_Code_task(?int $Code_user = null, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        return $this->get_liste_Code_task($Code_user, $options);
    }

    public function mf_convertir_Code_task_vers_Code_user( int $Code_task )
    {
        return $this->Code_task_vers_Code_user( $Code_task );
    }

    public function mf_liste_Code_user_vers_liste_Code_task( array $liste_Code_user, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        return $this->liste_Code_user_vers_liste_Code_task( $liste_Code_user, $options );
    }

    public function mf_liste_Code_task_vers_liste_Code_user( array $liste_Code_task, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        return $this->task__liste_Code_task_vers_liste_Code_user( $liste_Code_task, $options );
    }

    public function mf_get_liste_tables_enfants()
    {
        return $this->get_liste_tables_enfants( 'task' );
    }

    public function mf_get_liste_tables_parents()
    {
        return ['Code_user'];
    }

    public function mf_search_task_Name(string $task_Name, ?int $Code_user = null): int
    {
        return $this->rechercher_task_Name($task_Name, $Code_user);
    }

    public function mf_search_task_Date_creation(string $task_Date_creation, ?int $Code_user = null): int
    {
        return $this->rechercher_task_Date_creation($task_Date_creation, $Code_user);
    }

    public function mf_search_task_Workflow(int $task_Workflow, ?int $Code_user = null): int
    {
        return $this->rechercher_task_Workflow($task_Workflow, $Code_user);
    }

    /**
     * Trouve le premier "Code_task" rattaché à "Code_user"
     * Si pas de résultat, 0 sera retourné
     * @param int $Code_user
     * @return int
     */
    public function mf_search_Code_user(int $Code_user): int
    {
        return $this->rechercher_task__Code_user($Code_user);
    }

    public function mf_search__colonne(string $colonne_db, $recherche, ?int $Code_user = null): int
    {
        switch ($colonne_db) {
            case 'task_Name': return $this->mf_search_task_Name($recherche, $Code_user); break;
            case 'task_Date_creation': return $this->mf_search_task_Date_creation($recherche, $Code_user); break;
            case 'task_Workflow': return $this->mf_search_task_Workflow($recherche, $Code_user); break;
            default: return 0;
        }
    }

    public function mf_get_next_id(): int
    {
        $res_requete = executer_requete_mysql('SELECT AUTO_INCREMENT as next_id FROM INFORMATION_SCHEMA.TABLES WHERE table_name = \'task\';', false);
        $row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC);
        mysqli_free_result($res_requete);
        return intval($row_requete['next_id']);
    }

    public function mf_search(array $ligne): int // array('colonne1' => 'valeur1',  [...] )
    {
        global $mf_initialisation;
        $Code_user = (isset($ligne['Code_user']) ? intval($ligne['Code_user']) : get_user_courant('Code_user'));
        $task_Name = (isset($ligne['task_Name']) ? $ligne['task_Name'] : $mf_initialisation['task_Name']);
        $task_Date_creation = (isset($ligne['task_Date_creation']) ? $ligne['task_Date_creation'] : $mf_initialisation['task_Date_creation']);
        $task_Description = (isset($ligne['task_Description']) ? $ligne['task_Description'] : $mf_initialisation['task_Description']);
        $task_Workflow = (isset($ligne['task_Workflow']) ? $ligne['task_Workflow'] : $mf_initialisation['task_Workflow']);
        // Typage
        $Code_user = (int) $Code_user;
        $task_Name = (string) $task_Name;
        $task_Date_creation = format_date($task_Date_creation);
        $task_Description = (string) $task_Description;
        $task_Workflow = is_null($task_Workflow) || $task_Workflow === '' ? null : (int) $task_Workflow;
        // Fin typage
        Hook_task::pre_controller($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user);
        $mf_cle_unique = Hook_task::calcul_cle_unique($task_Name, $task_Date_creation, $task_Description, $task_Workflow, $Code_user);
        $res_requete = executer_requete_mysql('SELECT Code_task FROM ' . inst('task') . ' WHERE mf_cle_unique = \'' . $mf_cle_unique . '\'', false);
        if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
            $r = intval($row_requete['Code_task']);
        } else {
            $r = 0;
        }
        mysqli_free_result($res_requete);
        return $r;
    }
}
