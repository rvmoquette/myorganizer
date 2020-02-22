<?php declare(strict_types=1);

/*
    +------------------------------+
    |  NE PAS MODIFIER CE FICHIER  |
    +------------------------------+
*/

class a_user_task_monframework extends entite_monframework
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
        if (self::$initialisation) {
            include_once __DIR__ . '/../../erreurs/erreurs__a_user_task.php';
            self::$initialisation = false;
            Hook_a_user_task::initialisation();
            self::$cache_db = new Mf_Cachedb('a_user_task');
        }
        if (!self::$actualisation_en_cours)
        {
            self::$actualisation_en_cours=true;
            Hook_a_user_task::actualisation();
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

        if (! test_si_table_existe(inst('a_user_task'))) {
            executer_requete_mysql('CREATE TABLE '.inst('a_user_task').' (Code_user BIGINT UNSIGNED NOT NULL, Code_task BIGINT UNSIGNED NOT NULL, PRIMARY KEY (Code_user, Code_task)) ENGINE=MyISAM;', array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $liste_colonnes = lister_les_colonnes(inst('a_user_task'));

        if (isset($liste_colonnes['a_user_task_Link'])) {
            if (typeMyql2Sql($liste_colonnes['a_user_task_Link']['Type'])!='BOOL') {
                executer_requete_mysql('ALTER TABLE '.inst('a_user_task').' CHANGE a_user_task_Link a_user_task_Link BOOL;', array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['a_user_task_Link']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('a_user_task').' ADD a_user_task_Link BOOL;', array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('a_user_task').' SET a_user_task_Link=' . format_sql('a_user_task_Link', $mf_initialisation['a_user_task_Link']) . ';', array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        unset($liste_colonnes['Code_user']);
        unset($liste_colonnes['Code_task']);

        foreach ($liste_colonnes as $field => $value) {
            executer_requete_mysql('ALTER TABLE '.inst('a_user_task').' DROP COLUMN '.$field.';', array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

    }

    public function mfi_ajouter_auto(array $interface)
    {
        if (isset($interface['Code_user'])) {
            $liste_Code_user = [$interface['Code_user']];
            $liste_Code_user = $this->__get_liste_Code_user([OPTION_COND_MYSQL=>['Code_user IN ' . Sql_Format_Liste($liste_Code_user)]]);
        } elseif (isset($interface['liste_Code_user'])) {
            $liste_Code_user = $interface['liste_Code_user'];
            $liste_Code_user = $this->__get_liste_Code_user([OPTION_COND_MYSQL=>['Code_user IN ' . Sql_Format_Liste($liste_Code_user)]]);
        } else {
            $liste_Code_user = $this->get_liste_Code_user();
        }
        if (isset($interface['Code_task'])) {
            $liste_Code_task = [$interface['Code_task']];
            $liste_Code_task = $this->__get_liste_Code_task([OPTION_COND_MYSQL=>['Code_task IN ' . Sql_Format_Liste($liste_Code_task)]]);
        } elseif (isset($interface['liste_Code_task'])) {
            $liste_Code_task = $interface['liste_Code_task'];
            $liste_Code_task = $this->__get_liste_Code_task([OPTION_COND_MYSQL=>['Code_task IN ' . Sql_Format_Liste($liste_Code_task)]]);
        } else {
            $liste_Code_task = $this->get_liste_Code_task();
        }
        $mf_index = [];
        $res_requete = executer_requete_mysql('SELECT Code_user, Code_task FROM ' . inst('a_user_task') . ' WHERE Code_user IN ' . Sql_Format_Liste($liste_Code_user) . ' AND Code_task IN ' . Sql_Format_Liste($liste_Code_task) . ';', false);
        while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
            $mf_index[(int) $row_requete['Code_user']][(int) $row_requete['Code_task']] = 1;
        }
        mysqli_free_result($res_requete);
        $liste_a_user_task = [];
        foreach ($liste_Code_user as $Code_user) {
            foreach ($liste_Code_task as $Code_task) {
                if (! isset($mf_index[$Code_user][$Code_task])) {
                    $liste_a_user_task[] = ['Code_user'=>$Code_user,'Code_task'=>$Code_task];
                }
            }
        }
        if (isset($interface['a_user_task_Link'])) {
            foreach ($liste_a_user_task as &$a_user_task) {
                $a_user_task['a_user_task_Link'] = $interface['a_user_task_Link'];
            }
            unset($a_user_task);
        }
        return $this->mf_ajouter_3($liste_a_user_task);
    }

    public function mfi_supprimer_auto(array $interface)
    {
        if (isset($interface['Code_user'])) {
            $liste_Code_user = [$interface['Code_user']];
        } elseif (isset($interface['liste_Code_user'])) {
            $liste_Code_user = $interface['liste_Code_user'];
        } else {
            $liste_Code_user = $this->get_liste_Code_user();
        }
        if (isset($interface['Code_task'])) {
            $liste_Code_task = [$interface['Code_task']];
        } elseif (isset($interface['liste_Code_task'])) {
            $liste_Code_task = $interface['liste_Code_task'];
        } else {
            $liste_Code_task = $this->get_liste_Code_task();
        }
        $mf_index = [];
        $res_requete = executer_requete_mysql('SELECT Code_user, Code_task FROM ' . inst('a_user_task') . ' WHERE Code_user IN ' . Sql_Format_Liste($liste_Code_user) . ' AND Code_task IN ' . Sql_Format_Liste($liste_Code_task) . ';', false);
        while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
            $mf_index[(int) $row_requete['Code_user']][(int) $row_requete['Code_task']] = 1;
        }
        mysqli_free_result($res_requete);
        foreach ($liste_Code_user as &$Code_user) {
            if (isset($mf_index[$Code_user])) {
                foreach ($liste_Code_task as &$Code_task) {
                    if (isset($mf_index[$Code_user][$Code_task])) {
                        $this->mf_supprimer_2($Code_user, $Code_task);
                    }
                }
            }
        }
    }

    public function mf_ajouter(int $Code_user, int $Code_task, bool $a_user_task_Link, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        // Typage
        $a_user_task_Link = ($a_user_task_Link == true ? true : false);
        // Fin typage
        Hook_a_user_task::pre_controller($a_user_task_Link, $Code_user, $Code_task, true);
        if (! $force) {
            if (!self::$maj_droits_ajouter_en_cours)
            {
                self::$maj_droits_ajouter_en_cours = true;
                Hook_a_user_task::hook_actualiser_les_droits_ajouter($Code_user, $Code_task);
                self::$maj_droits_ajouter_en_cours = false;
            }
        }
        if ( !$force && !mf_matrice_droits(['a_user_task__AJOUTER']) ) $code_erreur = REFUS_A_USER_TASK__AJOUTER;
        elseif ( !$this->mf_tester_existance_Code_user($Code_user) ) $code_erreur = ERR_A_USER_TASK__AJOUTER__CODE_USER_INEXISTANT;
        elseif ( !$this->mf_tester_existance_Code_task($Code_task) ) $code_erreur = ERR_A_USER_TASK__AJOUTER__CODE_TASK_INEXISTANT;
        elseif ( $this->mf_tester_existance_a_user_task( $Code_user, $Code_task ) ) $code_erreur = ERR_A_USER_TASK__AJOUTER__DOUBLON;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user)) $code_erreur = ACCES_CODE_USER_REFUSE;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task)) $code_erreur = ACCES_CODE_TASK_REFUSE;
        elseif (! Hook_a_user_task::autorisation_ajout($a_user_task_Link, $Code_user, $Code_task) ) $code_erreur = REFUS_A_USER_TASK__AJOUT_BLOQUEE;
        else
        {
            Hook_a_user_task::data_controller($a_user_task_Link, $Code_user, $Code_task, true);
            $a_user_task_Link = ($a_user_task_Link == true ? 1 : 0);
            $requete = 'INSERT INTO '.inst('a_user_task')." ( a_user_task_Link, Code_user, Code_task ) VALUES ( $a_user_task_Link, $Code_user, $Code_task );";
            executer_requete_mysql($requete, array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $n = requete_mysqli_affected_rows();
            if ($n == 0) {
                $code_erreur = ERR_A_USER_TASK__AJOUTER__REFUSE;
            } else {
                self::$cache_db->clear();
                Hook_a_user_task::ajouter($Code_user, $Code_task);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'Code_user' => $Code_user, 'Code_task' => $Code_task, 'callback' => ( $code_erreur==0 ? Hook_a_user_task::callback_post($Code_user, $Code_task) : null)];
    }

    public function mf_ajouter_2(array $ligne, ?bool $force = null) // array('colonne1' => 'valeur1',  [...] )
    {
        if ($force === null) {
            $force = false;
        }
        global $mf_initialisation;
        $Code_user = (isset($ligne['Code_user']) ? intval($ligne['Code_user']) : get_user_courant('Code_user'));
        $Code_task = (isset($ligne['Code_task']) ? intval($ligne['Code_task']) : 0);
        $a_user_task_Link = (isset($ligne['a_user_task_Link'])?$ligne['a_user_task_Link']:$mf_initialisation['a_user_task_Link']);
        // Typage
        $a_user_task_Link = ($a_user_task_Link == true ? true : false);
        // Fin typage
        return $this->mf_ajouter($Code_user, $Code_task, $a_user_task_Link, $force);
    }

    public function mf_ajouter_3(array $lignes) // array( array( 'colonne1' => 'valeur1', 'colonne2' => 'valeur2',  [...] ), [...] )
    {
        global $mf_initialisation;
        $code_erreur = 0;
        $values = '';
        foreach ($lignes as $ligne) {
            $Code_user = (isset($ligne['Code_user']) ? intval($ligne['Code_user']) : 0);
            $Code_task = (isset($ligne['Code_task']) ? intval($ligne['Code_task']) : 0);
            $a_user_task_Link = (isset($ligne['a_user_task_Link'])?$ligne['a_user_task_Link']:$mf_initialisation['a_user_task_Link'] == true ? 1 : 0);
            if ($Code_user != 0) {
                if ($Code_task != 0) {
                    $values .= ($values!='' ? ',' : '')."($a_user_task_Link, $Code_user, $Code_task)";
                }
            }
        }
        if ($values != '') {
            $requete = "INSERT INTO ".inst('a_user_task')." ( a_user_task_Link, Code_user, Code_task ) VALUES $values;";
            executer_requete_mysql($requete, array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $n = requete_mysqli_affected_rows();
            if ($n < count($lignes)) {
                $code_erreur = ERR_A_USER_TASK__AJOUTER_3__ECHEC_AJOUT;
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

    public function mf_modifier(int $Code_user, int $Code_task, bool $a_user_task_Link, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        // Typage
        $a_user_task_Link = ($a_user_task_Link == true ? true : false);
        // Fin typage
        Hook_a_user_task::pre_controller($a_user_task_Link, $Code_user, $Code_task, false);
        if (! $force) {
            if (! self::$maj_droits_modifier_en_cours) {
                self::$maj_droits_modifier_en_cours = true;
                Hook_a_user_task::hook_actualiser_les_droits_modifier($Code_user, $Code_task);
                self::$maj_droits_modifier_en_cours = false;
            }
        }
        $a_user_task = $this->mf_get_2( $Code_user, $Code_task, ['autocompletion' => false]);
        if ( !$force && !mf_matrice_droits(['a_user_task__MODIFIER']) ) $code_erreur = REFUS_A_USER_TASK__MODIFIER;
        elseif ( !$this->mf_tester_existance_Code_user($Code_user) ) $code_erreur = ERR_A_USER_TASK__MODIFIER__CODE_USER_INEXISTANT;
        elseif ( !$this->mf_tester_existance_Code_task($Code_task) ) $code_erreur = ERR_A_USER_TASK__MODIFIER__CODE_TASK_INEXISTANT;
        elseif ( !$this->mf_tester_existance_a_user_task( $Code_user, $Code_task ) ) $code_erreur = ERR_A_USER_TASK__MODIFIER__INEXISTANT;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user)) $code_erreur = ACCES_CODE_USER_REFUSE;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task)) $code_erreur = ACCES_CODE_TASK_REFUSE;
        elseif ( !Hook_a_user_task::autorisation_modification($Code_user, $Code_task, $a_user_task_Link) ) $code_erreur = REFUS_A_USER_TASK__MODIFICATION_BLOQUEE;
        else {
            if (! isset(self::$lock["$Code_user-$Code_task"])) {
                self::$lock["$Code_user-$Code_task"] = 0;
            }
            if (self::$lock["$Code_user-$Code_task"] == 0) {
                self::$cache_db->add_lock("$Code_user-$Code_task");
            }
            self::$lock["$Code_user-$Code_task"]++;
            Hook_a_user_task::data_controller($a_user_task_Link, $Code_user, $Code_task, false);
            $mf_colonnes_a_modifier=[];
            $bool__a_user_task_Link = false; if ($a_user_task_Link !== $a_user_task['a_user_task_Link']) {Hook_a_user_task::data_controller__a_user_task_Link($a_user_task['a_user_task_Link'], $a_user_task_Link, $Code_user, $Code_task); if ( $a_user_task_Link !== $a_user_task['a_user_task_Link'] ) { $mf_colonnes_a_modifier[] = 'a_user_task_Link=' . format_sql('a_user_task_Link', $a_user_task_Link); $bool__a_user_task_Link = true;}}
            if (count($mf_colonnes_a_modifier)>0) {
                $requete = 'UPDATE ' . inst('a_user_task') . ' SET ' . enumeration($mf_colonnes_a_modifier) . " WHERE Code_user=$Code_user AND Code_task=$Code_task;";
                executer_requete_mysql($requete, array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if (requete_mysqli_affected_rows() == 0) {
                    $code_erreur = ERR_A_USER_TASK__MODIFIER__AUCUN_CHANGEMENT;
                } else {
                    self::$cache_db->clear();
                    Hook_a_user_task::modifier($Code_user, $Code_task, $bool__a_user_task_Link);
                }
            } else {
                $code_erreur = ERR_A_USER_TASK__MODIFIER__AUCUN_CHANGEMENT;
            }
            self::$lock["$Code_user-$Code_task"]--;
            if (self::$lock["$Code_user-$Code_task"] == 0) {
                self::$cache_db->release_lock("$Code_user-$Code_task");
                unset(self::$lock["$Code_user-$Code_task"]);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'callback' => ( $code_erreur == 0 ? Hook_a_user_task::callback_put($Code_user, $Code_task) : null )];
    }

    public function mf_modifier_2(array $lignes, ?bool $force = null) // array( array('Code_' => $Code, ..., 'colonne1' => 'valeur1', [...] ), [...] )
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        foreach ($lignes as $colonnes) {
            if ($code_erreur == 0) {
                $Code_user = (int) (isset($colonnes['Code_user']) ? $colonnes['Code_user'] : 0 );
                $Code_task = (int) (isset($colonnes['Code_task']) ? $colonnes['Code_task'] : 0 );
                $a_user_task = $this->mf_get_2($Code_user, $Code_task, ['autocompletion' => false]);
                if (! $force) {
                    if (! self::$maj_droits_modifier_en_cours) {
                        self::$maj_droits_modifier_en_cours = true;
                        Hook_a_user_task::hook_actualiser_les_droits_modifier($Code_user, $Code_task);
                        self::$maj_droits_modifier_en_cours = false;
                    }
                }
                $a_user_task_Link = (bool) ( isset($colonnes['a_user_task_Link']) && ( $force || mf_matrice_droits(['api_modifier__a_user_task_Link', 'a_user_task__MODIFIER']) ) ? $colonnes['a_user_task_Link'] : ( isset($a_user_task['a_user_task_Link']) ? $a_user_task['a_user_task_Link'] : '' ) );
                $retour = $this->mf_modifier($Code_user, $Code_task, $a_user_task_Link, true);
                if ($retour['code_erreur'] != 0 && $retour['code_erreur'] != ERR_A_USER_TASK__MODIFIER__AUCUN_CHANGEMENT) {
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

    public function mf_modifier_3(array $lignes) // array( array('Code_' => $Code, ..., 'colonne1' => 'valeur1', [...] ), [...] )
    {
        $code_erreur = 0;
        $modifs = false;

        // transformation des lignes en colonnes
        $valeurs_en_colonnes=[];
        $liste_valeurs_indexees=[];
        foreach ( $lignes as $colonnes )
        {
            foreach ($colonnes as $colonne => $valeur)
            {
                if ( $colonne=='a_user_task_Link' )
                {
                    if ( isset($colonnes['Code_user']) && isset($colonnes['Code_task']) )
                    {
                        $valeurs_en_colonnes[$colonne]['Code_user='.$colonnes['Code_user'] . ' AND ' . 'Code_task='.$colonnes['Code_task']]=$valeur;
                        $liste_valeurs_indexees[$colonne][''.$valeur][]='Code_user='.$colonnes['Code_user'] . ' AND ' . 'Code_task='.$colonnes['Code_task'];
                    }
                }
            }
        }

        // fabrication des requetes
        foreach ($valeurs_en_colonnes as $colonne => $valeurs) {
            if (count($liste_valeurs_indexees[$colonne]) > 3) {
                $perimetre = '';
                $modification_sql = 'CASE';
                foreach ($valeurs as $conditions => $valeur) {
                    $modification_sql .= ' WHEN ' . $conditions . ' THEN ' . format_sql($colonne, $valeur);
                    $perimetre .= ( $perimetre!='' ? ' OR ' : '' ) . $conditions;
                }
                $modification_sql .= ' END';
                executer_requete_mysql('UPDATE ' . inst('a_user_task') . ' SET ' . $colonne . ' = ' . $modification_sql . ' WHERE ' . $perimetre . ';', array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if (requete_mysqli_affected_rows() != 0) {
                    $modifs = true;
                }
            } else {
                foreach ($liste_valeurs_indexees[$colonne] as $valeur => $indices_par_valeur) {
                    $perimetre = '';
                    foreach ($indices_par_valeur as $conditions) {
                        $perimetre .= ($perimetre!='' ? ' OR ' : '') . $conditions;
                    }
                    executer_requete_mysql('UPDATE ' . inst('a_user_task') . ' SET ' . $colonne . ' = ' . format_sql($colonne, $valeur) . ' WHERE ' . $perimetre . ';', array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                    if (requete_mysqli_affected_rows() != 0) {
                        $modifs = true;
                    }
                }
            }
        }

        if (! $modifs && $code_erreur == 0) {
            $code_erreur = ERR_A_USER_TASK__MODIFIER_3__AUCUN_CHANGEMENT;
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

    public function mf_modifier_4(int $Code_user, int $Code_task, array $data, ?array $options = null ) // $data = array('colonne1' => 'valeur1', ... ) / $options = [ 'cond_mysql' => [], 'limit' => 0 ]
    {
        if ($options === null) {
            $options=[];
        }
        $code_erreur = 0;
        $Code_user = intval($Code_user);
        $Code_task = intval($Code_task);
        $mf_colonnes_a_modifier = [];
        if ( isset($data['a_user_task_Link']) ) { $mf_colonnes_a_modifier[] = 'a_user_task_Link = ' . format_sql('a_user_task_Link', $data['a_user_task_Link']); }
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

            $requete = 'UPDATE ' . inst('a_user_task') . ' SET ' . enumeration($mf_colonnes_a_modifier) . " WHERE 1".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )."".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" )."$argument_cond" . ( $limit>0 ? ' LIMIT ' . $limit : '' ) . ";";
            executer_requete_mysql( $requete , array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_A_USER_TASK__MODIFIER_4__AUCUN_CHANGEMENT;
            } else {
                self::$cache_db->clear();
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_supprimer(?int $Code_user = null, ?int $Code_task = null, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        $Code_user = intval($Code_user);
        $Code_task = intval($Code_task);
        $copie__liste_a_user_task = $this->mf_lister($Code_user, $Code_task, ['autocompletion' => false]);
        $liste_Code_user = [];
        $liste_Code_task = [];
        foreach ( $copie__liste_a_user_task as $copie__a_user_task )
        {
            $Code_user = $copie__a_user_task['Code_user'];
            $Code_task = $copie__a_user_task['Code_task'];
            if (!$force)
            {
                if (!self::$maj_droits_supprimer_en_cours)
                {
                    self::$maj_droits_supprimer_en_cours = true;
                    Hook_a_user_task::hook_actualiser_les_droits_supprimer($Code_user, $Code_task);
                    self::$maj_droits_supprimer_en_cours = false;
                }
            }
            if ( !$force && !mf_matrice_droits(['a_user_task__SUPPRIMER']) ) $code_erreur = REFUS_A_USER_TASK__SUPPRIMER;
            elseif ( !Hook_a_user_task::autorisation_suppression($Code_user, $Code_task) ) $code_erreur = REFUS_A_USER_TASK__SUPPRESSION_BLOQUEE;
            {
                $liste_Code_user[] = $Code_user;
                $liste_Code_task[] = $Code_task;
            }
        }
        if ($code_erreur == 0 && count($liste_Code_user)>0 && count($liste_Code_task)>0) {
            $requete = 'DELETE IGNORE FROM ' . inst('a_user_task') . " WHERE Code_user IN ".Sql_Format_Liste($liste_Code_user)." AND Code_task IN ".Sql_Format_Liste($liste_Code_task).";";
            executer_requete_mysql( $requete , array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_A_USER_TASK__SUPPRIMER__REFUSE;
            } else {
                self::$cache_db->clear();
                Hook_a_user_task::supprimer($copie__liste_a_user_task);
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

    public function mf_supprimer_2(?int $Code_user = null, ?int $Code_task = null)
    {
        $code_erreur = 0;
        $Code_user = intval($Code_user);
        $Code_task = intval($Code_task);
        $copie__liste_a_user_task = $this->mf_lister_2($Code_user, $Code_task, ['autocompletion' => false]);
        $requete = 'DELETE IGNORE FROM ' . inst('a_user_task') . " WHERE 1".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )."".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" ).";";
        executer_requete_mysql( $requete , array_search('a_user_task', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        if ( requete_mysqli_affected_rows()==0 )
        {
            $code_erreur = ERR_A_USER_TASK__SUPPRIMER_2__REFUSE;
        } else {
            self::$cache_db->clear();
            Hook_a_user_task::supprimer($copie__liste_a_user_task);
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

    public function mf_lister_contexte(?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        global $mf_contexte, $est_charge;
        return $this->mf_lister(isset($est_charge['user']) ? $mf_contexte['Code_user'] : 0, isset($est_charge['task']) ? $mf_contexte['Code_task'] : 0, $options);
    }

    public function mf_lister(?int $Code_user = null, ?int $Code_task = null, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $liste = $this->mf_lister_2($Code_user, $Code_task, $options);

        // controle_acces_donnees
        $controle_acces_donnees = CONTROLE_ACCES_DONNEES_DEFAUT;
        if (isset($options['controle_acces_donnees']))
        {
            $controle_acces_donnees = ( $options['controle_acces_donnees']==true );
        }

        foreach ($liste as $key => $elem)
        {
            if ( $controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_user', $elem['Code_user']) || $controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_task', $elem['Code_task']) )
            {
                unset($liste[$key]);
            }
        }

        return $liste;
    }

    public function mf_lister_2(?int $Code_user = null, ?int $Code_task = null, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = 'a_user_task__lister';
        $Code_user = intval($Code_user);
        $cle .= "_{$Code_user}";
        $Code_task = intval($Code_task);
        $cle .= "_{$Code_task}";

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
            if ( isset($mf_tri_defaut_table['a_user_task']) )
            {
                $options['tris'] = $mf_tri_defaut_table['a_user_task'];
            }
        }
        foreach ($options['tris'] as $colonne => $tri)
        {
            if ( $argument_tris=='' ) { $argument_tris = ' ORDER BY '; } else { $argument_tris .= ', '; }
            if ( $tri!='DESC' ) $tri = 'ASC';
            $argument_tris .= $colonne.' '.$tri;
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

        if (false === $liste = self::$cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'a_user_task_Link')!==false ) { $liste_colonnes_a_indexer['a_user_task_Link'] = 'a_user_task_Link'; }
            }
            if (isset($options['tris'])) {
                if ( isset($options['tris']['a_user_task_Link']) ) { $liste_colonnes_a_indexer['a_user_task_Link'] = 'a_user_task_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = self::$cache_db->read('a_user_task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_user_task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    self::$cache_db->write('a_user_task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_user_task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    self::$cache_db->clear();
                }
            }

            $liste = [];
            if (count($liste_colonnes_a_selectionner) == 0) {
                if ($toutes_colonnes) {
                    $colonnes = 'a_user_task_Link, Code_user, Code_task';
                } else {
                    $colonnes = 'a_user_task_Link, Code_user, Code_task';
                }
            } else {
                $liste_colonnes_a_selectionner[] = 'Code_user';
                $liste_colonnes_a_selectionner[] = 'Code_task';
                $colonnes = enumeration($liste_colonnes_a_selectionner);
            }

            $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM '.inst('a_user_task')." WHERE 1{$argument_cond}".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )."".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" )."{$argument_tris}{$argument_limit};", false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                mf_formatage_db_type_php($row_requete);
                $liste[$row_requete['Code_user'].'-'.$row_requete['Code_task']] = $row_requete;
            }
            mysqli_free_result($res_requete);
            if (count($options['tris']) == 1) {
                foreach ($options['tris'] as $colonne => $tri) {
                    global $lang_standard;
                    if (isset($lang_standard[$colonne.'_'])) {
                        effectuer_tri_suivant_langue($liste, $colonne, $tri);
                    }
                }
            }
            self::$cache_db->write($cle, $liste);
        }
        foreach ($liste as &$element)
        {
            if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                self::$auto_completion ++;
                Hook_a_user_task::completion($element, self::$auto_completion - 1);
                self::$auto_completion --;
            }
        }
        unset($element);
        return $liste;
    }

    public function mf_lister_3(?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        return $this->mf_lister(null, null, $options);
    }

    public function mf_get(int $Code_user, int $Code_task, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "a_user_task__get";
        $Code_user = intval($Code_user);
        $cle .= "_{$Code_user}";
        $Code_task = intval($Code_task);
        $cle .= "_{$Code_task}";
        $retour = [];
        if (! CONTROLE_ACCES_DONNEES_DEFAUT || Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user) && Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task)) {

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
                    $colonnes='a_user_task_Link, Code_user, Code_task';
                } else {
                    $colonnes='a_user_task_Link, Code_user, Code_task';
                }
                $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM ' . inst('a_user_task')." WHERE Code_user=$Code_user AND Code_task=$Code_task;", false);
                if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                    mf_formatage_db_type_php($row_requete);
                    $retour = $row_requete;
                } else {
                    $retour = [];
                }
                mysqli_free_result($res_requete);
                self::$cache_db->write($cle, $retour);
            }
            if (isset($retour['Code_user'])) {
                if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                    self::$auto_completion ++;
                    Hook_a_user_task::completion($retour, self::$auto_completion - 1);
                    self::$auto_completion --;
                }
            }
        }
        return $retour;
    }

    public function mf_get_2(int $Code_user, int $Code_task, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "a_user_task__get";
        $Code_user = intval($Code_user);
        $cle .= "_{$Code_user}";
        $Code_task = intval($Code_task);
        $cle .= "_{$Code_task}";

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
                $colonnes='a_user_task_Link, Code_user, Code_task';
            } else {
                $colonnes='a_user_task_Link, Code_user, Code_task';
            }
            $res_requete = executer_requete_mysql("SELECT $colonnes FROM " . inst('a_user_task')." WHERE Code_user=$Code_user AND Code_task=$Code_task;", false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                mf_formatage_db_type_php($row_requete);
                $retour = $row_requete;
            } else {
                $retour = [];
            }
            mysqli_free_result($res_requete);
            self::$cache_db->write($cle, $retour);
        }
        if (isset($retour['Code_user'])) {
            if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                self::$auto_completion ++;
                Hook_a_user_task::completion($retour, self::$auto_completion - 1);
                self::$auto_completion --;
            }
        }
        return $retour;
    }

    public function mf_compter(?int $Code_user = null, ?int $Code_task = null, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = 'a_user_task__compter';
        $Code_user = intval($Code_user);
        $cle .= '_{'.$Code_user.'}';
        $Code_task = intval($Code_task);
        $cle .= '_{'.$Code_task.'}';

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
                if ( strpos($argument_cond, 'a_user_task_Link')!==false ) { $liste_colonnes_a_indexer['a_user_task_Link'] = 'a_user_task_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = self::$cache_db->read('a_user_task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_user_task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    self::$cache_db->write('a_user_task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_user_task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    self::$cache_db->clear();
                }
            }

            $res_requete = executer_requete_mysql("SELECT COUNT(CONCAT(Code_user,'|',Code_task)) as nb FROM ".inst('a_user_task')." WHERE 1{$argument_cond}".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )."".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" ).";", false);
            $row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC);
            mysqli_free_result($res_requete);
            $nb = (int) $row_requete['nb'];
            self::$cache_db->write($cle, $nb);
        }
        return $nb;
    }

    public function mf_liste_Code_user_vers_liste_Code_task( array $liste_Code_user, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        return $this->a_user_task_liste_Code_user_vers_liste_Code_task( $liste_Code_user , $options );
    }

    public function mf_liste_Code_task_vers_liste_Code_user( array $liste_Code_task, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        return $this->a_user_task_liste_Code_task_vers_liste_Code_user( $liste_Code_task , $options );
    }
}
