<?php declare(strict_types=1);

/*
    +------------------------------+
    |  NE PAS MODIFIER CE FICHIER  |
    +------------------------------+
*/

class user_monframework extends entite_monframework
{

    protected static $initialisation = true;
    private static $auto_completion = 0;
    private static $actualisation_en_cours = false;
    protected static $cache_db;
    private static $maj_droits_ajouter_en_cours = false;
    private static $maj_droits_modifier_en_cours = false;
    private static $maj_droits_supprimer_en_cours = false;
    private static $lock = [];

    public function __construct()
    {
        if (self::$initialisation)
        {
            include_once __DIR__ . '/../../erreurs/erreurs__user.php';
            self::$initialisation = false;
            Hook_user::initialisation();
            self::$cache_db = new Mf_Cachedb('user');
        }
        if (!self::$actualisation_en_cours)
        {
            self::$actualisation_en_cours=true;
            Hook_user::actualisation();
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

        if (! test_si_table_existe(inst('user'))) {
            executer_requete_mysql('CREATE TABLE '.inst('user').'(Code_user BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (Code_user)) ENGINE=MyISAM;', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $liste_colonnes = lister_les_colonnes(inst('user'));

        if (isset($liste_colonnes['user_Login'])) {
            if (typeMyql2Sql($liste_colonnes['user_Login']['Type'])!='VARCHAR') {
                executer_requete_mysql('ALTER TABLE '.inst('user').' CHANGE user_Login user_Login VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['user_Login']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('user').' ADD user_Login VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('user').' SET user_Login=' . format_sql('user_Login', $mf_initialisation['user_Login']) . ';', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        if (isset($liste_colonnes['user_Password'])) {
            if (typeMyql2Sql($liste_colonnes['user_Password']['Type'])!='VARCHAR') {
                executer_requete_mysql('ALTER TABLE '.inst('user').' CHANGE user_Password user_Password VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['user_Password']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('user').' ADD user_Password VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('user').' SET user_Password=' . format_sql('user_Password', $mf_initialisation['user_Password']) . ';', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        if (isset($liste_colonnes['user_Email'])) {
            if (typeMyql2Sql($liste_colonnes['user_Email']['Type'])!='VARCHAR') {
                executer_requete_mysql('ALTER TABLE '.inst('user').' CHANGE user_Email user_Email VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            }
            unset($liste_colonnes['user_Email']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('user').' ADD user_Email VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            executer_requete_mysql('UPDATE '.inst('user').' SET user_Email=' . format_sql('user_Email', $mf_initialisation['user_Email']) . ';', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $liste_colonnes_a_indexer = [];

        if (isset($liste_colonnes['mf_signature'])) {
            unset($liste_colonnes['mf_signature']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('user').' ADD mf_signature VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_signature'] = 'mf_signature';

        if (isset($liste_colonnes['mf_cle_unique'])) {
            unset($liste_colonnes['mf_cle_unique']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('user').' ADD mf_cle_unique VARCHAR(255);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_cle_unique'] = 'mf_cle_unique';

        if (isset($liste_colonnes['mf_date_creation'])) {
            unset($liste_colonnes['mf_date_creation']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('user').' ADD mf_date_creation DATETIME;', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_date_creation'] = 'mf_date_creation';

        if (isset($liste_colonnes['mf_date_modification'])) {
            unset($liste_colonnes['mf_date_modification']);
        } else {
            executer_requete_mysql('ALTER TABLE '.inst('user').' ADD mf_date_modification DATETIME;', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
        $liste_colonnes_a_indexer['mf_date_modification'] = 'mf_date_modification';

        unset($liste_colonnes['Code_user']);

        foreach ($liste_colonnes as $field => $value) {
            executer_requete_mysql('ALTER TABLE '.inst('user').' DROP COLUMN '.$field.';', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }

        $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `' . inst('user') . '`;', false);
        $mf_liste_requete_index = [];
        while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
            $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
        }
        mysqli_free_result($res_requete_index);
        foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
            if (isset($liste_colonnes_a_indexer[$mf_colonne_indexee])) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
        }
        foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
            executer_requete_mysql('ALTER TABLE `' . inst('user') . '` ADD INDEX(`' . $colonnes_a_indexer . '`);', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        }
    }

    public function mf_ajouter(string $user_Login, string $user_Password, string $user_Email, ?bool $force = false)
    {
        if ($force === null) {
            $force = false;
        }
        $Code_user = 0;
        $code_erreur = 0;
        // Typage
        $user_Login = (string) $user_Login;
        $user_Password = (string) $user_Password;
        $user_Email = (string) $user_Email;
        // Fin typage
        Hook_user::pre_controller($user_Login, $user_Password, $user_Email);
        if (!$force)
        {
            if (!self::$maj_droits_ajouter_en_cours)
            {
                self::$maj_droits_ajouter_en_cours = true;
                Hook_user::hook_actualiser_les_droits_ajouter();
                self::$maj_droits_ajouter_en_cours = false;
            }
        }
        if ( !$force && !mf_matrice_droits(['user__AJOUTER']) ) $code_erreur = REFUS_USER__AJOUTER;
        elseif (! Hook_user::autorisation_ajout($user_Login, $user_Password, $user_Email) ) $code_erreur = REFUS_USER__AJOUT_BLOQUEE;
        elseif ( $this->rechercher_user_Login($user_Login)!=0 ) $code_erreur = ERR_USER__AJOUTER__USER_LOGIN_DOUBLON;
        elseif ( ACTIVER_CONNEXION_EMAIL && $this->rechercher_user_Email($user_Email)!=0 ) $code_erreur = ERR_USER__AJOUTER__USER_EMAIL_DOUBLON;
        else {
            Hook_user::data_controller($user_Login, $user_Password, $user_Email);
            $mf_signature = text_sql(Hook_user::calcul_signature($user_Login, $user_Email));
            $mf_cle_unique = text_sql(Hook_user::calcul_cle_unique($user_Login, $user_Email));
            $user_Login = text_sql($user_Login);
            $salt = salt(100);
            $user_Password = md5($user_Password.$salt).':'.$salt;
            $user_Email = text_sql($user_Email);
            $requete = "INSERT INTO ".inst('user')." ( mf_signature, mf_cle_unique, mf_date_creation, mf_date_modification, user_Login, user_Password, user_Email ) VALUES ( '$mf_signature', '$mf_cle_unique', '".get_now()."', '".get_now()."', '$user_Login', '$user_Password', '$user_Email' );";
            executer_requete_mysql($requete, array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $Code_user = requete_mysql_insert_id();
            if ($Code_user==0)
            {
                $code_erreur = ERR_USER__AJOUTER__AJOUT_REFUSE;
            } else {
                self::$cache_db->clear();
                Hook_user::ajouter( $Code_user );
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'Code_user' => $Code_user, 'callback' => ( $code_erreur==0 ? Hook_user::callback_post($Code_user) : null )];
    }

    public function mf_ajouter_2(array $ligne, bool $force = null) // array('colonne1' => 'valeur1',  [...] )
    {
        if ($force === null) {
            $force = false;
        }
        global $mf_initialisation;
        $user_Login = (isset($ligne['user_Login'])?$ligne['user_Login']:$mf_initialisation['user_Login']);
        $user_Password = (isset($ligne['user_Password'])?$ligne['user_Password']:$mf_initialisation['user_Password']);
        $user_Email = (isset($ligne['user_Email'])?$ligne['user_Email']:$mf_initialisation['user_Email']);
        // Typage
        $user_Login = (string) $user_Login;
        $user_Password = (string) $user_Password;
        $user_Email = (string) $user_Email;
        // Fin typage
        return $this->mf_ajouter($user_Login, $user_Password, $user_Email, $force);
    }

    public function mf_ajouter_3(array $lignes) // array( array( 'colonne1' => 'valeur1', 'colonne2' => 'valeur2',  [...] ), [...] )
    {
        global $mf_initialisation;
        $code_erreur = 0;
        $values = '';
        foreach ($lignes as $ligne) {
            $user_Login = text_sql(isset($ligne['user_Login'])?$ligne['user_Login']:$mf_initialisation['user_Login']);
            $salt = salt(100);
            $user_Password = md5(isset($ligne['user_Password'])?$ligne['user_Password']:$mf_initialisation['user_Password'].$salt).':'.$salt;
            $user_Email = text_sql(isset($ligne['user_Email'])?$ligne['user_Email']:$mf_initialisation['user_Email']);
            $values .= ($values!="" ? "," : "")."('$user_Login', '$user_Password', '$user_Email')";
        }
        if ($values!='')
        {
            $requete = "INSERT INTO ".inst('user')." ( user_Login, user_Password, user_Email ) VALUES $values;";
            executer_requete_mysql( $requete , array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            $n = requete_mysqli_affected_rows();
            if ($n < count($lignes))
            {
                $code_erreur = ERR_USER__AJOUTER_3__ECHEC_AJOUT;
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

    public function mf_actualiser_signature(int $Code_user)
    {
        $user = $this->mf_get_2($Code_user, ['autocompletion' => false]);
        $mf_signature = text_sql(Hook_user::calcul_signature($user['user_Login'], $user['user_Email']));
        $mf_cle_unique = text_sql(Hook_user::calcul_cle_unique($user['user_Login'], $user['user_Email']));
        $table = inst('user');
        executer_requete_mysql("UPDATE $table SET mf_signature='$mf_signature', mf_cle_unique='$mf_cle_unique' WHERE Code_user=$Code_user;", array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
        if (requete_mysqli_affected_rows() == 1) {
            self::$cache_db->clear();
        }
    }

    public function mf_modifier( int $Code_user, string $user_Login, string $user_Password, string $user_Email, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        // Typage
        $user_Login = (string) $user_Login;
        $user_Password = (string) $user_Password;
        $user_Email = (string) $user_Email;
        // Fin typage
        Hook_user::pre_controller($user_Login, $user_Password, $user_Email, $Code_user);
        if (! $force) {
            if (! self::$maj_droits_modifier_en_cours) {
                self::$maj_droits_modifier_en_cours = true;
                Hook_user::hook_actualiser_les_droits_modifier($Code_user);
                self::$maj_droits_modifier_en_cours = false;
            }
        }
        $user = $this->mf_get_2( $Code_user, ['autocompletion' => false, 'masquer_mdp' => false]);
        if ( !$force && !mf_matrice_droits(['user__MODIFIER']) ) $code_erreur = REFUS_USER__MODIFIER;
        elseif ( !$this->mf_tester_existance_Code_user($Code_user) ) $code_erreur = ERR_USER__MODIFIER__CODE_USER_INEXISTANT;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user)) $code_erreur = ACCES_CODE_USER_REFUSE;
        elseif ( !Hook_user::autorisation_modification($Code_user, $user_Login, $user_Password, $user_Email) ) $code_erreur = REFUS_USER__MODIFICATION_BLOQUEE;
        elseif ($this->rechercher_user_Login($user_Login) != 0 && $this->rechercher_user_Login($user_Login)!=$Code_user) $code_erreur = ERR_USER__AJOUTER__USER_LOGIN_DOUBLON;
        else {
            if (! isset(self::$lock[$Code_user])) {
                self::$lock[$Code_user] = 0;
            }
            if (self::$lock[$Code_user] == 0) {
                self::$cache_db->add_lock((string) $Code_user);
            }
            self::$lock[$Code_user]++;
            Hook_user::data_controller($user_Login, $user_Password, $user_Email, $Code_user);
            $mf_colonnes_a_modifier=[];
            $bool__user_Login = false; if ($user_Login !== $user['user_Login']) {Hook_user::data_controller__user_Login($user['user_Login'], $user_Login, $Code_user); if ( $user_Login !== $user['user_Login'] ) { $mf_colonnes_a_modifier[] = 'user_Login=' . format_sql('user_Login', $user_Login); $bool__user_Login = true;}}
            $bool__user_Password = false; if ($user_Password !== '') {$mf_colonnes_a_modifier[] = 'user_Password = ' . format_sql('user_Password', $user_Password); $bool__user_Password = true;}
            $bool__user_Email = false; if ($user_Email !== $user['user_Email']) {Hook_user::data_controller__user_Email($user['user_Email'], $user_Email, $Code_user); if ( $user_Email !== $user['user_Email'] ) { $mf_colonnes_a_modifier[] = 'user_Email=' . format_sql('user_Email', $user_Email); $bool__user_Email = true;}}
            if (count($mf_colonnes_a_modifier) > 0) {
                $mf_signature = text_sql(Hook_user::calcul_signature($user_Login, $user_Email));
                $mf_cle_unique = text_sql(Hook_user::calcul_cle_unique($user_Login, $user_Email));
                $mf_colonnes_a_modifier[] = 'mf_signature=\'' . $mf_signature . '\'';
                $mf_colonnes_a_modifier[] = 'mf_cle_unique=\'' . $mf_cle_unique . '\'';
                $mf_colonnes_a_modifier[] = 'mf_date_modification=\'' . get_now() . '\'';
                $requete = 'UPDATE '.inst('user').' SET ' . enumeration($mf_colonnes_a_modifier) . ' WHERE Code_user = ' . $Code_user . ';';
                executer_requete_mysql($requete, array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if (requete_mysqli_affected_rows() == 0) {
                    $code_erreur = ERR_USER__MODIFIER__AUCUN_CHANGEMENT;
                } else {
                    self::$cache_db->clear();
                    Hook_user::modifier($Code_user, $bool__user_Login, $bool__user_Password, $bool__user_Email);
                }
            } else {
                $code_erreur = ERR_USER__MODIFIER__AUCUN_CHANGEMENT;
            }
            self::$lock[$Code_user]--;
            if (self::$lock[$Code_user] == 0) {
                self::$cache_db->release_lock((string) $Code_user);
                unset(self::$lock[$Code_user]);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return ['code_erreur' => $code_erreur, 'callback' => ($code_erreur == 0 ? Hook_user::callback_put($Code_user) : null)];
    }

    public function mf_modifier_2(array $lignes, ?bool $force = null) // array( $Code_user => array('colonne1' => 'valeur1',  [...] ) )
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        foreach ($lignes as $Code_user => $colonnes) {
            if ($code_erreur == 0) {
                $Code_user = intval($Code_user);
                $user = $this->mf_get_2($Code_user, ['autocompletion' => false]);
                if (! $force) {
                    if (! self::$maj_droits_modifier_en_cours) {
                        self::$maj_droits_modifier_en_cours = true;
                        Hook_user::hook_actualiser_les_droits_modifier($Code_user);
                        self::$maj_droits_modifier_en_cours = false;
                    }
                }
                $user_Login = ( isset($colonnes['user_Login']) && ( $force || mf_matrice_droits(['api_modifier__user_Login', 'user__MODIFIER']) ) ? $colonnes['user_Login'] : ( isset($user['user_Login']) ? $user['user_Login'] : '' ) );
                $user_Password = ( isset($colonnes['user_Password']) && ( $force || mf_matrice_droits(['api_modifier__user_Password', 'user__MODIFIER']) ) ? $colonnes['user_Password'] : '' );
                $user_Email = ( isset($colonnes['user_Email']) && ( $force || mf_matrice_droits(['api_modifier__user_Email', 'user__MODIFIER']) ) ? $colonnes['user_Email'] : ( isset($user['user_Email']) ? $user['user_Email'] : '' ) );
                // Typage
                $user_Login = (string) $user_Login;
                $user_Password = (string) $user_Password;
                $user_Email = (string) $user_Email;
                // Fin typage
                $retour = $this->mf_modifier($Code_user, $user_Login, $user_Password, $user_Email, true);
                if ($retour['code_erreur'] != 0 && $retour['code_erreur'] != ERR_USER__MODIFIER__AUCUN_CHANGEMENT) {
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

    public function mf_modifier_3(array $lignes) // array( $Code_user => array('colonne1' => 'valeur1',  [...] ) )
    {
        $code_erreur = 0;
        $modifs = false;

        // transformation des lignes en colonnes
        $valeurs_en_colonnes=[];
        $indices_par_colonne=[];
        $liste_valeurs_indexees=[];
        foreach ( $lignes as $Code_user => $colonnes )
        {
            foreach ($colonnes as $colonne => $valeur)
            {
                if ( $colonne=='user_Login' || $colonne=='user_Password' || $colonne=='user_Email' )
                {
                    $valeurs_en_colonnes[$colonne][$Code_user]=$valeur;
                    $indices_par_colonne[$colonne][]=$Code_user;
                    $liste_valeurs_indexees[$colonne][''.$valeur][]=$Code_user;
                }
            }
        }

        // fabrication des requetes
        foreach ( $valeurs_en_colonnes as $colonne => $valeurs )
        {
            if ( count($liste_valeurs_indexees[$colonne]) > 3 )
            {
                $modification_sql = $colonne . ' = CASE Code_user';
                foreach ( $valeurs as $Code_user => $valeur )
                {
                    $modification_sql .= ' WHEN ' . $Code_user . ' THEN ' . format_sql($colonne, $valeur);
                }
                $modification_sql .= ' END';
                $perimetre = Sql_Format_Liste($indices_par_colonne[$colonne]);
                executer_requete_mysql('UPDATE ' . inst('user') . ' SET ' . $modification_sql . ' WHERE Code_user IN ' . $perimetre . ';', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
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
                    executer_requete_mysql('UPDATE ' . inst('user') . ' SET ' . $colonne . ' = ' . format_sql($colonne, $valeur) . ' WHERE Code_user IN ' . $perimetre . ';', array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                    if ( requete_mysqli_affected_rows()!=0 )
                    {
                        $modifs = true;
                    }
                }
            }
        }

        if ( ! $modifs && $code_erreur==0 )
        {
            $code_erreur = ERR_USER__MODIFIER_3__AUCUN_CHANGEMENT;
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

    public function mf_modifier_4( array $data, ?array $options = null /* $options = array( 'cond_mysql' => [], 'limit' => 0 ) */ ) // $data = array('colonne1' => 'valeur1', ... )
    {
        $code_erreur = 0;
        $mf_colonnes_a_modifier=[];
        if (isset($data['user_Login'])) { $mf_colonnes_a_modifier[] = 'user_Login = ' . format_sql('user_Login', $data['user_Login']); }
        if (isset($data['user_Password'])) { $mf_colonnes_a_modifier[] = 'user_Password = ' . format_sql('user_Password', $data['user_Password']); }
        if (isset($data['user_Email'])) { $mf_colonnes_a_modifier[] = 'user_Email = ' . format_sql('user_Email', $data['user_Email']); }
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

            $requete = 'UPDATE ' . inst('user') . ' SET ' . enumeration($mf_colonnes_a_modifier) . " WHERE 1$argument_cond" . ( $limit>0 ? ' LIMIT ' . $limit : '' ) . ";";
            executer_requete_mysql( $requete , array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_USER__MODIFIER_4__AUCUN_CHANGEMENT;
            } else {
                self::$cache_db->clear();
            }
        }
        return ['code_erreur' => $code_erreur];
    }

    public function mf_supprimer(int $Code_user, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        $Code_user = intval($Code_user);
        if (! $force) {
            if (!self::$maj_droits_supprimer_en_cours)
            {
                self::$maj_droits_supprimer_en_cours = true;
                Hook_user::hook_actualiser_les_droits_supprimer($Code_user);
                self::$maj_droits_supprimer_en_cours = false;
            }
        }
        if ( !$force && !mf_matrice_droits(['user__SUPPRIMER']) ) $code_erreur = REFUS_USER__SUPPRIMER;
        elseif (! $this->mf_tester_existance_Code_user($Code_user) ) $code_erreur = ERR_USER__SUPPRIMER_2__CODE_USER_INEXISTANT;
        elseif (CONTROLE_ACCES_DONNEES_DEFAUT && ! Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user)) $code_erreur = ACCES_CODE_USER_REFUSE;
        elseif ( !Hook_user::autorisation_suppression($Code_user) ) $code_erreur = REFUS_USER__SUPPRESSION_BLOQUEE;
        else
        {
            $copie__user = $this->mf_get($Code_user, ['autocompletion' => false]);
            $this->supprimer_donnes_en_cascade("user", [$Code_user]);
            $requete = 'DELETE IGNORE FROM ' . inst('user') . ' WHERE Code_user=' . $Code_user . ';';
            executer_requete_mysql($requete, array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_USER__SUPPRIMER__REFUSEE;
            } else {
                self::$cache_db->clear();
                Hook_user::supprimer($copie__user);
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

    public function mf_supprimer_2(array $liste_Code_user, ?bool $force = null)
    {
        if ($force === null) {
            $force = false;
        }
        $code_erreur = 0;
        $copie__liste_user = $this->mf_lister_2($liste_Code_user, ['autocompletion' => false]);
        $liste_Code_user=[];
        foreach ( $copie__liste_user as $copie__user )
        {
            $Code_user = $copie__user['Code_user'];
            if (!$force)
            {
                if (!self::$maj_droits_supprimer_en_cours)
                {
                    self::$maj_droits_supprimer_en_cours = true;
                    Hook_user::hook_actualiser_les_droits_supprimer($Code_user);
                    self::$maj_droits_supprimer_en_cours = false;
                }
            }
            if ( !$force && !mf_matrice_droits(['user__SUPPRIMER']) ) $code_erreur = REFUS_USER__SUPPRIMER;
            elseif ( !Hook_user::autorisation_suppression($Code_user) ) $code_erreur = REFUS_USER__SUPPRESSION_BLOQUEE;
            {
                $liste_Code_user[] = $Code_user;
            }
        }
        if ( $code_erreur==0 && count($liste_Code_user)>0 )
        {
            $this->supprimer_donnes_en_cascade("user", $liste_Code_user);
            $requete = 'DELETE IGNORE FROM ' . inst('user') . ' WHERE Code_user IN ' . Sql_Format_Liste($liste_Code_user) . ';';
            executer_requete_mysql( $requete , array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_USER__SUPPRIMER_2__REFUSEE;
            } else {
                self::$cache_db->clear();
                Hook_user::supprimer_2($copie__liste_user);
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

    public function mf_supprimer_3(array $liste_Code_user)
    {
        $code_erreur = 0;
        if (count($liste_Code_user) > 0) {
            $this->supprimer_donnes_en_cascade("user", $liste_Code_user);
            $requete = 'DELETE IGNORE FROM ' . inst('user') . ' WHERE Code_user IN ' . Sql_Format_Liste($liste_Code_user) . ';';
            executer_requete_mysql( $requete , array_search('user', LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
            if (requete_mysqli_affected_rows() == 0) {
                $code_erreur = ERR_USER__SUPPRIMER_3__REFUSEE;
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
        global $mf_contexte;
        if (! $contexte_parent && $mf_contexte['Code_user'] != 0) {
            $user = $this->mf_get( $mf_contexte['Code_user'], $options);
            return [$user['Code_user'] => $user];
        } else {
            return $this->mf_lister($options);
        }
    }

    public function mf_lister(?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "user__lister";

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
            if ( isset($mf_tri_defaut_table['user']) )
            {
                $options['tris'] = $mf_tri_defaut_table['user'];
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
            $liste_user_pas_a_jour = [];
            if (false === $liste = self::$cache_db->read($cle)) {

                // Indexes
                $liste_colonnes_a_indexer = [];
                if ($argument_cond != '') {
                    if ( strpos($argument_cond, 'user_Login')!==false ) { $liste_colonnes_a_indexer['user_Login'] = 'user_Login'; }
                    if ( strpos($argument_cond, 'user_Password')!==false ) { $liste_colonnes_a_indexer['user_Password'] = 'user_Password'; }
                    if ( strpos($argument_cond, 'user_Email')!==false ) { $liste_colonnes_a_indexer['user_Email'] = 'user_Email'; }
                }
                if (isset($options['tris'])) {
                    if ( isset($options['tris']['user_Login']) ) { $liste_colonnes_a_indexer['user_Login'] = 'user_Login'; }
                    if ( isset($options['tris']['user_Password']) ) { $liste_colonnes_a_indexer['user_Password'] = 'user_Password'; }
                    if ( isset($options['tris']['user_Email']) ) { $liste_colonnes_a_indexer['user_Email'] = 'user_Email'; }
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    if (false === $mf_liste_requete_index = self::$cache_db->read('user__index')) {
                        $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('user').'`;', false);
                        $mf_liste_requete_index = [];
                        while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                            $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                        }
                        mysqli_free_result($res_requete_index);
                        self::$cache_db->write('user__index', $mf_liste_requete_index);
                    }
                    foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                        if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                    }
                    if (count($liste_colonnes_a_indexer) > 0) {
                        foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                            executer_requete_mysql('ALTER TABLE `'.inst('user').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                        }
                        self::$cache_db->clear();
                    }
                }

                if (count($liste_colonnes_a_selectionner) == 0) {
                    if ($toutes_colonnes) {
                        $colonnes = 'Code_user, user_Login, user_Password, user_Email';
                    } else {
                        $colonnes = 'Code_user, user_Login, user_Password, user_Email';
                    }
                } else {
                    $liste_colonnes_a_selectionner[] = 'Code_user';
                    $colonnes = enumeration($liste_colonnes_a_selectionner, ',');
                }

                $liste = [];
                $liste_user_pas_a_jour = [];
                $res_requete = executer_requete_mysql("SELECT $colonnes FROM " . inst('user') . " WHERE 1{$argument_cond}{$argument_tris}{$argument_limit};", false);
                while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                    unset($row_requete['user_Password']);
                    mf_formatage_db_type_php($row_requete);
                    $liste[$row_requete['Code_user']] = $row_requete;
                    if ($maj && ! Hook_user::est_a_jour($row_requete)) {
                        $liste_user_pas_a_jour[$row_requete['Code_user']] = $row_requete;
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
                Hook_user::mettre_a_jour( $liste_user_pas_a_jour );
            }
        }

        foreach ($liste as $elem) {
            if ($controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_user', $elem['Code_user'])) {
                unset($liste[$elem['Code_user']]);
            } else {
                if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                    self::$auto_completion ++;
                    Hook_user::completion($liste[$elem['Code_user']], self::$auto_completion - 1);
                    self::$auto_completion --;
                }
            }
        }

        return $liste;
    }

    public function mf_lister_2(array $liste_Code_user, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        if (count($liste_Code_user) > 0) {
            $cle = "user__mf_lister_2_".Sql_Format_Liste($liste_Code_user);

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
                if ( isset($mf_tri_defaut_table['user']) )
                {
                    $options['tris'] = $mf_tri_defaut_table['user'];
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
                $liste_user_pas_a_jour = [];
                if (false === $liste = self::$cache_db->read($cle)) {

                    // Indexes
                    $liste_colonnes_a_indexer = [];
                    if ($argument_cond != '') {
                        if ( strpos($argument_cond, 'user_Login')!==false ) { $liste_colonnes_a_indexer['user_Login'] = 'user_Login'; }
                        if ( strpos($argument_cond, 'user_Password')!==false ) { $liste_colonnes_a_indexer['user_Password'] = 'user_Password'; }
                        if ( strpos($argument_cond, 'user_Email')!==false ) { $liste_colonnes_a_indexer['user_Email'] = 'user_Email'; }
                    }
                    if (isset($options['tris'])) {
                        if ( isset($options['tris']['user_Login']) ) { $liste_colonnes_a_indexer['user_Login'] = 'user_Login'; }
                        if ( isset($options['tris']['user_Password']) ) { $liste_colonnes_a_indexer['user_Password'] = 'user_Password'; }
                        if ( isset($options['tris']['user_Email']) ) { $liste_colonnes_a_indexer['user_Email'] = 'user_Email'; }
                    }
                    if (count($liste_colonnes_a_indexer) > 0) {
                        if (false === $mf_liste_requete_index = self::$cache_db->read('user__index')) {
                            $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('user').'`;', false);
                            $mf_liste_requete_index = [];
                            while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                                $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                            }
                            mysqli_free_result($res_requete_index);
                            self::$cache_db->write('user__index', $mf_liste_requete_index);
                        }
                        foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                            if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                        }
                        if (count($liste_colonnes_a_indexer) > 0) {
                            foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                                executer_requete_mysql('ALTER TABLE `'.inst('user').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                            }
                            self::$cache_db->clear();
                        }
                    }

                    if (count($liste_colonnes_a_selectionner) == 0) {
                        if ($toutes_colonnes) {
                            $colonnes = 'Code_user, user_Login, user_Password, user_Email';
                        } else {
                            $colonnes = 'Code_user, user_Login, user_Password, user_Email';
                        }
                    } else {
                        $liste_colonnes_a_selectionner[] = 'Code_user';
                        $colonnes = enumeration($liste_colonnes_a_selectionner, ',');
                    }

                    $liste = [];
                    $liste_user_pas_a_jour = [];
                    $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM ' . inst('user') . " WHERE 1{$argument_cond} AND Code_user IN ".Sql_Format_Liste($liste_Code_user)."{$argument_tris}{$argument_limit};", false);
                    while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                        unset($row_requete['user_Password']);
                        mf_formatage_db_type_php($row_requete);
                        $liste[$row_requete['Code_user']] = $row_requete;
                        if ($maj && ! Hook_user::est_a_jour($row_requete)) {
                            $liste_user_pas_a_jour[$row_requete['Code_user']] = $row_requete;
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
                    Hook_user::mettre_a_jour( $liste_user_pas_a_jour );
                }
            }

            foreach ($liste as $elem) {
                if ($controle_acces_donnees && !Hook_mf_systeme::controle_acces_donnees('Code_user', $elem['Code_user'])) {
                    unset($liste[$elem['Code_user']]);
                } else {
                    if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                        self::$auto_completion ++;
                        Hook_user::completion($liste[$elem['Code_user']], self::$auto_completion - 1);
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
        return $this->mf_lister($options);
    }

    public function mf_get(int $Code_user, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $masquer_mdp = true;
        if ( isset($options['masquer_mdp']) )
        {
            $masquer_mdp = ( $options['masquer_mdp']==true );
        }
        $Code_user = intval($Code_user);
        $retour = [];
        if ( ! CONTROLE_ACCES_DONNEES_DEFAUT || Hook_mf_systeme::controle_acces_donnees('Code_user', $Code_user) ) {
            $cle = 'user__get_'.$Code_user.'_'.( $masquer_mdp ? 'masquer=1' : 'masquer=0' );

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
                        $colonnes='Code_user, user_Login, user_Password, user_Email';
                    } else {
                        $colonnes='Code_user, user_Login, user_Password, user_Email';
                    }
                    $res_requete = executer_requete_mysql("SELECT $colonnes FROM " . inst('user') . ' WHERE Code_user = ' . $Code_user . ';', false);
                    if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                        if ($masquer_mdp) {
                            unset($row_requete['user_Password']);
                        }
                        mf_formatage_db_type_php($row_requete);
                        $retour = $row_requete;
                        if ($maj && ! Hook_user::est_a_jour($row_requete)) {
                            $nouvelle_lecture = true;
                        }
                    } else {
                        $retour = [];
                    }
                    mysqli_free_result($res_requete);
                    if (! $nouvelle_lecture) {
                        self::$cache_db->write($cle, $retour);
                    } else {
                        Hook_user::mettre_a_jour([$row_requete['Code_user'] => $row_requete]);
                    }
                }
            }
            if (isset($retour['Code_user'])) {
                if (($autocompletion_recursive || self::$auto_completion < 1) && $autocompletion) {
                    self::$auto_completion ++;
                    Hook_user::completion($retour, self::$auto_completion - 1);
                    self::$auto_completion --;
                }
            }
        }
        return $retour;
    }

    public function mf_get_last(?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = "user__get_last";
        if (false === $retour = self::$cache_db->read($cle)) {
            $Code_user = 0;
            $res_requete = executer_requete_mysql('SELECT Code_user FROM ' . inst('user') . " WHERE 1 ORDER BY mf_date_creation DESC, Code_user DESC LIMIT 0 , 1;", false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_user = intval($row_requete['Code_user']);
            }
            mysqli_free_result($res_requete);
            $retour = $this->mf_get($Code_user, $options);
            self::$cache_db->write($cle, $retour);
        }
        return $retour;
    }

    protected function mf_get_connexion(int $Code_user, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $Code_user = intval($Code_user);
        $cle = "user__get_connexion_{$Code_user}";

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
                $colonnes='Code_user, user_Login, user_Password, user_Email';
            } else {
                $colonnes='Code_user, user_Login, user_Password, user_Email';
            }
            $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM ' . inst('user') . ' WHERE Code_user = ' . $Code_user . ';', false);
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
                Hook_user::completion($retour, self::$auto_completion - 1);
                self::$auto_completion --;
            }
        }
        return $retour;
    }

    public function mf_get_2(int $Code_user, ?array $options = null /* $options = [ 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'toutes_colonnes' => true, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $masquer_mdp = true;
        if (isset($options['masquer_mdp'])) {
            $masquer_mdp = ($options['masquer_mdp'] == true);
        }
        $cle = 'user__get_'.$Code_user.'_'.( $masquer_mdp ? 'masquer=1' : 'masquer=0' );

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
                $colonnes='Code_user, user_Login, user_Password, user_Email';
            } else {
                $colonnes='Code_user, user_Login, user_Password, user_Email';
            }
            $res_requete = executer_requete_mysql('SELECT ' . $colonnes . ' FROM ' . inst('user') . ' WHERE Code_user = ' . $Code_user . ';', false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                if ($masquer_mdp) {
                    unset($row_requete['user_Password']);
                }
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
                Hook_user::completion($retour, self::$auto_completion - 1);
                self::$auto_completion --;
            }
        }
        return $retour;
    }

    public function mf_prec_et_suiv( int $Code_user, ?array $options = null /* $options = [ 'cond_mysql' => [], 'tris' => [], 'limit' => [], 'toutes_colonnes' => TOUTES_COLONNES_DEFAUT, 'liste_colonnes_a_selectionner' => [], 'autocompletion' => AUTOCOMPLETION_DEFAUT, 'autocompletion_recursive' => AUTOCOMPLETION_RECURSIVE_DEFAUT, 'controle_acces_donnees' => CONTROLE_ACCES_DONNEES_DEFAUT, 'maj' => true ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $Code_user = intval($Code_user);
        $liste = $this->mf_lister($options);
        return prec_suiv($liste, $Code_user);
    }

    public function mf_compter(?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cle = 'user__compter';

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
                if ( strpos($argument_cond, 'user_Login')!==false ) { $liste_colonnes_a_indexer['user_Login'] = 'user_Login'; }
                if ( strpos($argument_cond, 'user_Password')!==false ) { $liste_colonnes_a_indexer['user_Password'] = 'user_Password'; }
                if ( strpos($argument_cond, 'user_Email')!==false ) { $liste_colonnes_a_indexer['user_Email'] = 'user_Email'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = self::$cache_db->read('user__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('user').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    self::$cache_db->write('user__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('user').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    self::$cache_db->clear();
                }
            }

            $res_requete = executer_requete_mysql('SELECT count(Code_user) as nb FROM ' . inst('user')." WHERE 1{$argument_cond};", false);
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
        return $this->mf_compter( $options );
    }

    public function mf_liste_Code_user(?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        return $this->get_liste_Code_user($options);
    }

    public function mf_get_liste_tables_enfants()
    {
        return $this->get_liste_tables_enfants( 'user' );
    }

    public function mf_get_liste_tables_parents()
    {
        return [];
    }

    public function mf_search_user_Login(string $user_Login): int
    {
        return $this->rechercher_user_Login($user_Login);
    }

    public function mf_search_user_Password(string $user_Password): int
    {
        return $this->rechercher_user_Password($user_Password);
    }

    public function mf_search_user_Email(string $user_Email): int
    {
        return $this->rechercher_user_Email($user_Email);
    }

    public function mf_search__colonne(string $colonne_db, $recherche): int
    {
        switch ($colonne_db) {
            case 'user_Login': return $this->mf_search_user_Login($recherche); break;
            case 'user_Password': return $this->mf_search_user_Password($recherche); break;
            case 'user_Email': return $this->mf_search_user_Email($recherche); break;
            default: return 0;
        }
    }

    public function mf_get_next_id(): int
    {
        $res_requete = executer_requete_mysql('SELECT AUTO_INCREMENT as next_id FROM INFORMATION_SCHEMA.TABLES WHERE table_name = \'user\';', false);
        $row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC);
        mysqli_free_result($res_requete);
        return intval($row_requete['next_id']);
    }

    public function mf_search(array $ligne): int // array('colonne1' => 'valeur1',  [...] )
    {
        global $mf_initialisation;
        $user_Login = (isset($ligne['user_Login']) ? $ligne['user_Login'] : $mf_initialisation['user_Login']);
        $user_Password = (isset($ligne['user_Password']) ? $ligne['user_Password'] : $mf_initialisation['user_Password']);
        $user_Email = (isset($ligne['user_Email']) ? $ligne['user_Email'] : $mf_initialisation['user_Email']);
        // Typage
        $user_Login = (string) $user_Login;
        $user_Password = (string) $user_Password;
        $user_Email = (string) $user_Email;
        // Fin typage
        Hook_user::pre_controller($user_Login, $user_Password, $user_Email);
        $mf_cle_unique = Hook_user::calcul_cle_unique($user_Login, $user_Email);
        $res_requete = executer_requete_mysql('SELECT Code_user FROM ' . inst('user') . ' WHERE mf_cle_unique = \'' . $mf_cle_unique . '\'', false);
        if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
            $r = intval($row_requete['Code_user']);
        } else {
            $r = 0;
        }
        mysqli_free_result($res_requete);
        return $r;
    }
}
