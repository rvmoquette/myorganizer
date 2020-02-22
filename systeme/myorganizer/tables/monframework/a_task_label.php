<?php declare(strict_types=1);

/*
    +------------------------------+
    |  NE PAS MODIFIER CE FICHIER  |
    +------------------------------+
*/

class a_task_label_monframework extends entite_monframework
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
            include_once __DIR__ . '/../../erreurs/erreurs__a_task_label.php';
            self::$initialisation = false;
            Hook_a_task_label::initialisation();
            self::$cache_db = new Mf_Cachedb('a_task_label');
        }
        if (!self::$actualisation_en_cours)
        {
            self::$actualisation_en_cours=true;
            Hook_a_task_label::actualisation();
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

        if (! test_si_table_existe(inst('a_task_label'))) {
            executer_requete_mysql('CREATE TABLE '.inst('a_task_label').' (Code_task BIGINT UNSIGNED NOT NULL, Code_label BIGINT UNSIGNED NOT NULL, PRIMARY KEY (Code_task, Code_label)) ENGINE=MyISAM;', array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $liste_colonnes = lister_les_colonnes(inst('a_task_label'));

        if (isset($liste_colonnes['a_task_label_Link'])) {
            if (typeMyql2Sql($liste_colonnes['a_task_label_Link']['Type'])!='BOOL') {
                executer_requete_mysql('ALTER TABLE '.inst('a_task_label').' CHANGE a_task_label_Link a_task_label_Link BOOL;', array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['a_task_label_Link']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('a_task_label').' ADD a_task_label_Link BOOL;', array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('a_task_label').' SET a_task_label_Link=' . format_sql('a_task_label_Link', $mf_initialisation['a_task_label_Link']) . ';', array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        unset($liste_colonnes['Code_task']);
        unset($liste_colonnes['Code_label']);

        foreach ($liste_colonnes as $field => $value) {
            executer_requete_mysql('ALTER TABLE '.inst('a_task_label').' DROP COLUMN '.$field.';', array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

    }

    public function mfi_ajouter_auto(array $interface)
    {
        if (isset($interface['Code_task'])) {
            $liste_Code_task = [$interface['Code_task']];
            $liste_Code_task = $this->__get_liste_Code_task([OPTION_COND_MYSQL=>['Code_task IN ' . Sql_Format_Liste($liste_Code_task)]]);
        } elseif (isset($interface['liste_Code_task'])) {
            $liste_Code_task = $interface['liste_Code_task'];
            $liste_Code_task = $this->__get_liste_Code_task([OPTION_COND_MYSQL=>['Code_task IN ' . Sql_Format_Liste($liste_Code_task)]]);
        } else {
            $liste_Code_task = $this->get_liste_Code_task();
        }
        if (isset($interface['Code_label'])) {
            $liste_Code_label = [$interface['Code_label']];
            $liste_Code_label = $this->__get_liste_Code_label([OPTION_COND_MYSQL=>['Code_label IN ' . Sql_Format_Liste($liste_Code_label)]]);
        } elseif (isset($interface['liste_Code_label'])) {
            $liste_Code_label = $interface['liste_Code_label'];
            $liste_Code_label = $this->__get_liste_Code_label([OPTION_COND_MYSQL=>['Code_label IN ' . Sql_Format_Liste($liste_Code_label)]]);
        } else {
            $liste_Code_label = $this->get_liste_Code_label();
        }
        $mf_index = [];
        $res_requete = executer_requete_mysql('SELECT Code_task, Code_label FROM ' . inst('a_task_label') . ' WHERE Code_task IN ' . Sql_Format_Liste($liste_Code_task) . ' AND Code_label IN ' . Sql_Format_Liste($liste_Code_label) . ';', false);
        while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
            $mf_index[(int) $row_requete['Code_task']][(int) $row_requete['Code_label']] = 1;
        }
        mysqli_free_result($res_requete);
        $liste_a_task_label = [];
        foreach ($liste_Code_task as $Code_task) {
            foreach ($liste_Code_label as $Code_label) {
                if (! isset($mf_index[$Code_task][$Code_label])) {
                    $liste_a_task_label[] = ['Code_task'=>$Code_task,'Code_label'=>$Code_label];
                }
            }
        }
        if (isset($interface['a_task_label_Link'])) {
            foreach ($liste_a_task_label as &$a_task_label) {
                $a_task_label['a_task_label_Link'] = $interface['a_task_label_Link'];
            }
            unset($a_task_label);
        }
        return $this->mf_ajouter_3($liste_a_task_label);
    }

    public function mfi_supprimer_auto(array $interface)
    {
        if (isset($interface['Code_task'])) {
            $liste_Code_task = [$interface['Code_task']];
        } elseif (isset($interface['liste_Code_task'])) {
            $liste_Code_task = $interface['liste_Code_task'];
        } else {
            $liste_Code_task = $this->get_liste_Code_task();
        }
        if (isset($interface['Code_label'])) {
            $liste_Code_label = [$interface['Code_label']];
        } elseif (isset($interface['liste_Code_label'])) {
            $liste_Code_label = $interface['liste_Code_label'];
        } else {
            $liste_Code_label = $this->get_liste_Code_label();
        }
        $mf_index = [];
        $res_requete = executer_requete_mysql('SELECT Code_task, Code_label FROM ' . inst('a_task_label') . ' WHERE Code_task IN ' . Sql_Format_Liste($liste_Code_task) . ' AND Code_label IN ' . Sql_Format_Liste($liste_Code_label) . ';', false);
        while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
            $mf_index[(int) $row_requete['Code_task']][(int) $row_requete['Code_label']] = 1;
        }
        mysqli_free_result($res_requete);
        foreach ($liste_Code_task as &$Code_task) {
            if (isset($mf_index[$Code_task])) {
                foreach ($liste_Code_label as &$Code_label) {
                    if (isset($mf_index[$Code_task][$Code_label])) {
                        $this->mf_supprimer_2($Code_task, $Code_label);
                    }
                }
            }
        }
    }

    public function mf_ajouter(int $Code_task, int $Code_label, bool $a_task_label_Link, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        // Typage
        $a_task_label_Link = ($a_task_label_Link == true ? true : false);
        // Fin typage
        Hook_a_task_label::pre_controller($a_task_label_Link, $Code_task, $Code_label, true);
        if (! $force) {
            if (!self::$maj_droits_ajouter_en_cours)
            {
                self::$maj_droits_ajouter_en_cours = true;
                Hook_a_task_label::hook_actualiser_les_droits_ajouter($Code_task, $Code_label);
                self::$maj_droits_ajouter_en_cours = false;
            }
        }
        if ( !$force && !mf_matrice_droits(['a_task_label__AJOUTER']) ) $code_erreur = REFUS_A_TASK_LABEL__AJOUTER;
        elseif ( !$this->mf_tester_existance_Code_task($Code_task) ) $code_erreur = ERR_A_TASK_LABEL__AJOUTER__CODE_TASK_INEXISTANT;
        elseif ( !$this->mf_tester_existance_Code_label($Code_label) ) $code_erreur = ERR_A_TASK_LABEL__AJOUTER__CODE_LABEL_INEXISTANT;
        elseif ( $this->mf_tester_existance_a_task_label( $Code_task, $Code_label ) ) $code_erreur = ERR_A_TASK_LABEL__AJOUTER__DOUBLON;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task)) $code_erreur = ACCES_CODE_TASK_REFUSE;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_label', $Code_label)) $code_erreur = ACCES_CODE_LABEL_REFUSE;
        elseif (! Hook_a_task_label::autorisation_ajout($a_task_label_Link, $Code_task, $Code_label) ) $code_erreur = REFUS_A_TASK_LABEL__AJOUT_BLOQUEE;
        else
        {
            Hook_a_task_label::data_controller($a_task_label_Link, $Code_task, $Code_label, true);
            $a_task_label_Link = ($a_task_label_Link == true ? 1 : 0);
            $requete = 'INSERT INTO '.inst('a_task_label')." ( a_task_label_Link, Code_task, Code_label ) VALUES ( $a_task_label_Link, $Code_task, $Code_label );";
            executer_requete_mysql($requete, array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $n = requete_mysqli_affected_rows();
            if ($n == 0) {
                $code_erreur = ERR_A_TASK_LABEL__AJOUTER__REFUSE;
            } else {
                self::$cache_db->clear();
                Hook_a_task_label::ajouter($Code_task, $Code_label);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'Code_task' => $Code_task, 'Code_label' => $Code_label, 'callback' => ( $code_erreur==0 ? Hook_a_task_label::callback_post($Code_task, $Code_label) : null)];
    }

    public function mf_ajouter_2(array $ligne, ?bool $force = null) // array('colonne1' => 'valeur1',  [...] )
    {
        if ($force === null) {
            $force = false;
        }
        global $mf_initialisation;
        $Code_task = (isset($ligne['Code_task']) ? intval($ligne['Code_task']) : 0);
        $Code_label = (isset($ligne['Code_label']) ? intval($ligne['Code_label']) : 0);
        $a_task_label_Link = (isset($ligne['a_task_label_Link'])?$ligne['a_task_label_Link']:$mf_initialisation['a_task_label_Link']);
        // Typage
        $a_task_label_Link = ($a_task_label_Link == true ? true : false);
        // Fin typage
        return $this->mf_ajouter($Code_task, $Code_label, $a_task_label_Link, $force);
    }

    public function mf_ajouter_3(array $lignes) // array( array( 'colonne1' => 'valeur1', 'colonne2' => 'valeur2',  [...] ), [...] )
    {
        global $mf_initialisation;
        $code_erreur = 0;
        $values = '';
        foreach ($lignes as $ligne) {
            $Code_task = (isset($ligne['Code_task']) ? intval($ligne['Code_task']) : 0);
            $Code_label = (isset($ligne['Code_label']) ? intval($ligne['Code_label']) : 0);
            $a_task_label_Link = (isset($ligne['a_task_label_Link'])?$ligne['a_task_label_Link']:$mf_initialisation['a_task_label_Link'] == true ? 1 : 0);
            if ($Code_task != 0) {
                if ($Code_label != 0) {
                    $values .= ($values!='' ? ',' : '')."($a_task_label_Link, $Code_task, $Code_label)";
                }
            }
        }
        if ($values != '') {
            $requete = "INSERT INTO ".inst('a_task_label')." ( a_task_label_Link, Code_task, Code_label ) VALUES $values;";
            executer_requete_mysql($requete, array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $n = requete_mysqli_affected_rows();
            if ($n < count($lignes)) {
                $code_erreur = ERR_A_TASK_LABEL__AJOUTER_3__ECHEC_AJOUT;
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

    public function mf_modifier(int $Code_task, int $Code_label, bool $a_task_label_Link, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        // Typage
        $a_task_label_Link = ($a_task_label_Link == true ? true : false);
        // Fin typage
        Hook_a_task_label::pre_controller($a_task_label_Link, $Code_task, $Code_label, false);
        if (! $force) {
            if (! self::$maj_droits_modifier_en_cours) {
                self::$maj_droits_modifier_en_cours = true;
                Hook_a_task_label::hook_actualiser_les_droits_modifier($Code_task, $Code_label);
                self::$maj_droits_modifier_en_cours = false;
            }
        }
        $a_task_label = $this->mf_get_2( $Code_task, $Code_label, ['autocompletion' => false]);
        if ( !$force && !mf_matrice_droits(['a_task_label__MODIFIER']) ) $code_erreur = REFUS_A_TASK_LABEL__MODIFIER;
        elseif ( !$this->mf_tester_existance_Code_task($Code_task) ) $code_erreur = ERR_A_TASK_LABEL__MODIFIER__CODE_TASK_INEXISTANT;
        elseif ( !$this->mf_tester_existance_Code_label($Code_label) ) $code_erreur = ERR_A_TASK_LABEL__MODIFIER__CODE_LABEL_INEXISTANT;
        elseif ( !$this->mf_tester_existance_a_task_label( $Code_task, $Code_label ) ) $code_erreur = ERR_A_TASK_LABEL__MODIFIER__INEXISTANT;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task)) $code_erreur = ACCES_CODE_TASK_REFUSE;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_label', $Code_label)) $code_erreur = ACCES_CODE_LABEL_REFUSE;
        elseif ( !Hook_a_task_label::autorisation_modification($Code_task, $Code_label, $a_task_label_Link) ) $code_erreur = REFUS_A_TASK_LABEL__MODIFICATION_BLOQUEE;
        else {
            if (! isset(self::$lock["$Code_task-$Code_label"])) {
                self::$lock["$Code_task-$Code_label"] = 0;
            }
            if (self::$lock["$Code_task-$Code_label"] == 0) {
                self::$cache_db->add_lock("$Code_task-$Code_label");
            }
            self::$lock["$Code_task-$Code_label"]++;
            Hook_a_task_label::data_controller($a_task_label_Link, $Code_task, $Code_label, false);
            $mf_colonnes_a_modifier=[];
            $bool__a_task_label_Link = false; if ($a_task_label_Link !== $a_task_label['a_task_label_Link']) {Hook_a_task_label::data_controller__a_task_label_Link($a_task_label['a_task_label_Link'], $a_task_label_Link, $Code_task, $Code_label); if ( $a_task_label_Link !== $a_task_label['a_task_label_Link'] ) { $mf_colonnes_a_modifier[] = 'a_task_label_Link=' . format_sql('a_task_label_Link', $a_task_label_Link); $bool__a_task_label_Link = true;}}
            if (count($mf_colonnes_a_modifier)>0) {
                $requete = 'UPDATE ' . inst('a_task_label') . ' SET ' . enumeration($mf_colonnes_a_modifier) . " WHERE Code_task=$Code_task AND Code_label=$Code_label;";
                executer_requete_mysql($requete, array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if (requete_mysqli_affected_rows() == 0) {
                    $code_erreur = ERR_A_TASK_LABEL__MODIFIER__AUCUN_CHANGEMENT;
                } else {
                    self::$cache_db->clear();
                    Hook_a_task_label::modifier($Code_task, $Code_label, $bool__a_task_label_Link);
                }
            } else {
                $code_erreur = ERR_A_TASK_LABEL__MODIFIER__AUCUN_CHANGEMENT;
            }
            self::$lock["$Code_task-$Code_label"]--;
            if (self::$lock["$Code_task-$Code_label"] == 0) {
                self::$cache_db->release_lock("$Code_task-$Code_label");
                unset(self::$lock["$Code_task-$Code_label"]);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'callback' => ( $code_erreur == 0 ? Hook_a_task_label::callback_put($Code_task, $Code_label) : null )];
    }

    public function mf_modifier_2(array $lignes, ?bool $force = null) // array( array('Code_' => $Code, ..., 'colonne1' => 'valeur1', [...] ), [...] )
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        foreach ($lignes as $colonnes) {
            if ($code_erreur == 0) {
                $Code_task = (int) (isset($colonnes['Code_task']) ? $colonnes['Code_task'] : 0 );
                $Code_label = (int) (isset($colonnes['Code_label']) ? $colonnes['Code_label'] : 0 );
                $a_task_label = $this->mf_get_2($Code_task, $Code_label, ['autocompletion' => false]);
                if (! $force) {
                    if (! self::$maj_droits_modifier_en_cours) {
                        self::$maj_droits_modifier_en_cours = true;
                        Hook_a_task_label::hook_actualiser_les_droits_modifier($Code_task, $Code_label);
                        self::$maj_droits_modifier_en_cours = false;
                    }
                }
                $a_task_label_Link = (bool) ( isset($colonnes['a_task_label_Link']) && ( $force || mf_matrice_droits(['api_modifier__a_task_label_Link', 'a_task_label__MODIFIER']) ) ? $colonnes['a_task_label_Link'] : ( isset($a_task_label['a_task_label_Link']) ? $a_task_label['a_task_label_Link'] : '' ) );
                $retour = $this->mf_modifier($Code_task, $Code_label, $a_task_label_Link, true);
                if ($retour['code_erreur'] != 0 && $retour['code_erreur'] != ERR_A_TASK_LABEL__MODIFIER__AUCUN_CHANGEMENT) {
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
                if ( $colonne=='a_task_label_Link' )
                {
                    if ( isset($colonnes['Code_task']) && isset($colonnes['Code_label']) )
                    {
                        $valeurs_en_colonnes[$colonne]['Code_task='.$colonnes['Code_task'] . ' AND ' . 'Code_label='.$colonnes['Code_label']]=$valeur;
                        $liste_valeurs_indexees[$colonne][''.$valeur][]='Code_task='.$colonnes['Code_task'] . ' AND ' . 'Code_label='.$colonnes['Code_label'];
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
                executer_requete_mysql('UPDATE ' . inst('a_task_label') . ' SET ' . $colonne . ' = ' . $modification_sql . ' WHERE ' . $perimetre . ';', array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if (requete_mysqli_affected_rows() != 0) {
                    $modifs = true;
                }
            } else {
                foreach ($liste_valeurs_indexees[$colonne] as $valeur => $indices_par_valeur) {
                    $perimetre = '';
                    foreach ($indices_par_valeur as $conditions) {
                        $perimetre .= ($perimetre!='' ? ' OR ' : '') . $conditions;
                    }
                    executer_requete_mysql('UPDATE ' . inst('a_task_label') . ' SET ' . $colonne . ' = ' . format_sql($colonne, $valeur) . ' WHERE ' . $perimetre . ';', array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                    if (requete_mysqli_affected_rows() != 0) {
                        $modifs = true;
                    }
                }
            }
        }

        if (! $modifs && $code_erreur == 0) {
            $code_erreur = ERR_A_TASK_LABEL__MODIFIER_3__AUCUN_CHANGEMENT;
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

    public function mf_modifier_4(int $Code_task, int $Code_label, array $data, ?array $options = null ) // $data = array('colonne1' => 'valeur1', ... ) / $options = [ 'cond_mysql' => [], 'limit' => 0 ]
    {
        if ($options === null) {
            $options=[];
        }
        $code_erreur = 0;
        $Code_task = intval($Code_task);
        $Code_label = intval($Code_label);
        $mf_colonnes_a_modifier = [];
        if ( isset($data['a_task_label_Link']) ) { $mf_colonnes_a_modifier[] = 'a_task_label_Link = ' . format_sql('a_task_label_Link', $data['a_task_label_Link']); }
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

            $requete = 'UPDATE ' . inst('a_task_label') . ' SET ' . enumeration($mf_colonnes_a_modifier) . " WHERE 1".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" )."".( $Code_label!=0 ? " AND Code_label=$Code_label" : "" )."$argument_cond" . ( $limit>0 ? ' LIMIT ' . $limit : '' ) . ";";
            executer_requete_mysql( $requete , array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_A_TASK_LABEL__MODIFIER_4__AUCUN_CHANGEMENT;
            } else {
                self::$cache_db->clear();
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_supprimer(?int $Code_task = null, ?int $Code_label = null, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        $Code_task = intval($Code_task);
        $Code_label = intval($Code_label);
        $copie__liste_a_task_label = $this->mf_lister($Code_task, $Code_label, ['autocompletion' => false]);
        $liste_Code_task = [];
        $liste_Code_label = [];
        foreach ( $copie__liste_a_task_label as $copie__a_task_label )
        {
            $Code_task = $copie__a_task_label['Code_task'];
            $Code_label = $copie__a_task_label['Code_label'];
            if (!$force)
            {
                if (!self::$maj_droits_supprimer_en_cours)
                {
                    self::$maj_droits_supprimer_en_cours = true;
                    Hook_a_task_label::hook_actualiser_les_droits_supprimer($Code_task, $Code_label);
                    self::$maj_droits_supprimer_en_cours = false;
                }
            }
            if ( !$force && !mf_matrice_droits(['a_task_label__SUPPRIMER']) ) $code_erreur = REFUS_A_TASK_LABEL__SUPPRIMER;
            elseif ( !Hook_a_task_label::autorisation_suppression($Code_task, $Code_label) ) $code_erreur = REFUS_A_TASK_LABEL__SUPPRESSION_BLOQUEE;
            {
                $liste_Code_task[] = $Code_task;
                $liste_Code_label[] = $Code_label;
            }
        }
        if ($code_erreur == 0 && count($liste_Code_task)>0 && count($liste_Code_label)>0) {
            $requete = 'DELETE IGNORE FROM ' . inst('a_task_label') . " WHERE Code_task IN ".Sql_Format_Liste($liste_Code_task)." AND Code_label IN ".Sql_Format_Liste($liste_Code_label).";";
            executer_requete_mysql( $requete , array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_A_TASK_LABEL__SUPPRIMER__REFUSE;
            } else {
                self::$cache_db->clear();
                Hook_a_task_label::supprimer($copie__liste_a_task_label);
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

    public function mf_supprimer_2(?int $Code_task = null, ?int $Code_label = null)
    {
        $code_erreur = 0;
        $Code_task = intval($Code_task);
        $Code_label = intval($Code_label);
        $copie__liste_a_task_label = $this->mf_lister_2($Code_task, $Code_label, ['autocompletion' => false]);
        $requete = 'DELETE IGNORE FROM ' . inst('a_task_label') . " WHERE 1".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" )."".( $Code_label!=0 ? " AND Code_label=$Code_label" : "" ).";";
        executer_requete_mysql( $requete , array_search('a_task_label', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        if ( requete_mysqli_affected_rows()==0 )
        {
            $code_erreur = ERR_A_TASK_LABEL__SUPPRIMER_2__REFUSE;
        } else {
            self::$cache_db->clear();
            Hook_a_task_label::supprimer($copie__liste_a_task_label);
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
        return $this->mf_lister(isset($est_charge['task']) ? $mf_contexte['Code_task'] : 0, isset($est_charge['label']) ? $mf_contexte['Code_label'] : 0, $options);
    }

    public function mf_lister(?int $Code_task = null, ?int $Code_label = null, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $liste = $this->mf_lister_2($Code_task, $Code_label, $options);

        // controle_acces_donnees
        $controle_acces_donnees = CONTROLE_ACCES_DONNEES_DEFAUT;
        if (isset($options['controle_acces_donnees']))
        {
            $controle_acces_donnees = ( $options['controle_acces_donnees']==true );
        }

        foreach ($liste as $key => $elem)
        {
            if ( $controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_task', $elem['Code_task']) || $controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_label', $elem['Code_label']) )
            {
                unset($liste[$key]);
            }
        }

        return $liste;
    }

    public function mf_lister_2(?int $Code_task = null, ?int $Code_label = null, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = 'a_task_label__lister';
        $Code_task = intval($Code_task);
        $cle .= "_{$Code_task}";
        $Code_label = intval($Code_label);
        $cle .= "_{$Code_label}";

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
            if ( isset($mf_tri_defaut_table['a_task_label']) )
            {
                $options['tris'] = $mf_tri_defaut_table['a_task_label'];
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
                if ( strpos($argument_cond, 'a_task_label_Link')!==false ) { $liste_colonnes_a_indexer['a_task_label_Link'] = 'a_task_label_Link'; }
            }
            if (isset($options['tris'])) {
                if ( isset($options['tris']['a_task_label_Link']) ) { $liste_colonnes_a_indexer['a_task_label_Link'] = 'a_task_label_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = self::$cache_db->read('a_task_label__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_task_label').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    self::$cache_db->write('a_task_label__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_task_label').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    self::$cache_db->clear();
                }
            }

            $liste = [];
            if (count($liste_colonnes_a_selectionner) == 0) {
                if ($toutes_colonnes) {
                    $colonnes = 'a_task_label_Link, Code_task, Code_label';
                } else {
                    $colonnes = 'a_task_label_Link, Code_task, Code_label';
                }
            } else {
                $liste_colonnes_a_selectionner[] = 'Code_task';
                $liste_colonnes_a_selectionner[] = 'Code_label';
                $colonnes = enumeration($liste_colonnes_a_selectionner);
            }

            $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM '.inst('a_task_label')." WHERE 1{$argument_cond}".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" )."".( $Code_label!=0 ? " AND Code_label=$Code_label" : "" )."{$argument_tris}{$argument_limit};", false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                mf_formatage_db_type_php($row_requete);
                $liste[$row_requete['Code_task'].'-'.$row_requete['Code_label']] = $row_requete;
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
                Hook_a_task_label::completion($element, self::$auto_completion - 1);
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

    public function mf_get(int $Code_task, int $Code_label, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "a_task_label__get";
        $Code_task = intval($Code_task);
        $cle .= "_{$Code_task}";
        $Code_label = intval($Code_label);
        $cle .= "_{$Code_label}";
        $retour = [];
        if (! CONTROLE_ACCES_DONNEES_DEFAUT || Hook_mf_systeme::controle_acces_donnees('Code_task', $Code_task) && Hook_mf_systeme::controle_acces_donnees('Code_label', $Code_label)) {

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
                    $colonnes='a_task_label_Link, Code_task, Code_label';
                } else {
                    $colonnes='a_task_label_Link, Code_task, Code_label';
                }
                $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM ' . inst('a_task_label')." WHERE Code_task=$Code_task AND Code_label=$Code_label;", false);
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
                    Hook_a_task_label::completion($retour, self::$auto_completion - 1);
                    self::$auto_completion --;
                }
            }
        }
        return $retour;
    }

    public function mf_get_2(int $Code_task, int $Code_label, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "a_task_label__get";
        $Code_task = intval($Code_task);
        $cle .= "_{$Code_task}";
        $Code_label = intval($Code_label);
        $cle .= "_{$Code_label}";

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
                $colonnes='a_task_label_Link, Code_task, Code_label';
            } else {
                $colonnes='a_task_label_Link, Code_task, Code_label';
            }
            $res_requete = executer_requete_mysql("SELECT $colonnes FROM " . inst('a_task_label')." WHERE Code_task=$Code_task AND Code_label=$Code_label;", false);
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
                Hook_a_task_label::completion($retour, self::$auto_completion - 1);
                self::$auto_completion --;
            }
        }
        return $retour;
    }

    public function mf_compter(?int $Code_task = null, ?int $Code_label = null, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = 'a_task_label__compter';
        $Code_task = intval($Code_task);
        $cle .= '_{'.$Code_task.'}';
        $Code_label = intval($Code_label);
        $cle .= '_{'.$Code_label.'}';

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
                if ( strpos($argument_cond, 'a_task_label_Link')!==false ) { $liste_colonnes_a_indexer['a_task_label_Link'] = 'a_task_label_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = self::$cache_db->read('a_task_label__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_task_label').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    self::$cache_db->write('a_task_label__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_task_label').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    self::$cache_db->clear();
                }
            }

            $res_requete = executer_requete_mysql("SELECT COUNT(CONCAT(Code_task,'|',Code_label)) as nb FROM ".inst('a_task_label')." WHERE 1{$argument_cond}".( $Code_task!=0 ? " AND Code_task=$Code_task" : "" )."".( $Code_label!=0 ? " AND Code_label=$Code_label" : "" ).";", false);
            $row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC);
            mysqli_free_result($res_requete);
            $nb = (int) $row_requete['nb'];
            self::$cache_db->write($cle, $nb);
        }
        return $nb;
    }

    public function mf_liste_Code_task_vers_liste_Code_label( array $liste_Code_task, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        return $this->a_task_label_liste_Code_task_vers_liste_Code_label( $liste_Code_task , $options );
    }

    public function mf_liste_Code_label_vers_liste_Code_task( array $liste_Code_label, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        return $this->a_task_label_liste_Code_label_vers_liste_Code_task( $liste_Code_label , $options );
    }
}
