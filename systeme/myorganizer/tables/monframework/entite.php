<?php declare(strict_types=1);

include __DIR__ . '/mf_cachedb.php';

class entite_monframework extends entite
{

/*
    +--------+
    |  user  |
    +--------+
*/

    protected function mf_tester_existance_Code_user( int $Code_user )
    {
        $Code_user = intval($Code_user);
        $requete_sql = "SELECT Code_user FROM ".inst('user')." WHERE Code_user = $Code_user;";
        $cache_db = new Mf_Cachedb('user');
        if (false === $r = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) $r = 'o'; else $r = 'n';
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $r);
        }
        return $r == 'o';
    }

    protected function rechercher_user_Login(string $user_Login): int
    {
        $user_Login = format_sql('user_Login', $user_Login);
        $requete_sql = 'SELECT Code_user FROM '.inst('user').' WHERE user_Login = ' . $user_Login . ' LIMIT 0, 1;';
        $cache_db = new Mf_Cachedb('user');
        if (false === $Code_user = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_user = (int) $row_requete['Code_user'];
            } else {
                $Code_user = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_user);
        }
        return $Code_user;
    }

    protected function rechercher_user_Password(string $user_Password): int
    {
        $user_Password = format_sql('user_Password', $user_Password);
        $requete_sql = 'SELECT Code_user FROM '.inst('user')." WHERE user_Password = $user_Password LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('user');
        if (false === $Code_user = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_user = (int) $row_requete['Code_user'];
            } else {
                $Code_user = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_user);
        }
        return $Code_user;
    }

    protected function rechercher_user_Email(string $user_Email): int
    {
        $user_Email = format_sql('user_Email', $user_Email);
        $requete_sql = 'SELECT Code_user FROM '.inst('user')." WHERE user_Email = $user_Email LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('user');
        if (false === $Code_user = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_user = (int) $row_requete['Code_user'];
            } else {
                $Code_user = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_user);
        }
        return $Code_user;
    }

    protected function rechercher_user_Admin(bool $user_Admin): int
    {
        $user_Admin = format_sql('user_Admin', $user_Admin);
        $requete_sql = 'SELECT Code_user FROM '.inst('user')." WHERE user_Admin = $user_Admin LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('user');
        if (false === $Code_user = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_user = (int) $row_requete['Code_user'];
            } else {
                $Code_user = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_user);
        }
        return $Code_user;
    }

    protected function __get_liste_Code_user(?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        return $this->get_liste_Code_user($options);
    }

    protected function get_liste_Code_user(?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb('user');
        $cle = "user__lister_cles";

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        // limit
        $argument_limit = '';
        if (isset($options['limit'][0]) && isset($options['limit'][1])) {
            $argument_limit = " LIMIT {$options['limit'][0]}, {$options['limit'][1]}";
        }
        $cle .= "_$argument_limit";

        if (false === $liste = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'user_Login')!==false ) { $liste_colonnes_a_indexer['user_Login'] = 'user_Login'; }
                if ( strpos($argument_cond, 'user_Password')!==false ) { $liste_colonnes_a_indexer['user_Password'] = 'user_Password'; }
                if ( strpos($argument_cond, 'user_Email')!==false ) { $liste_colonnes_a_indexer['user_Email'] = 'user_Email'; }
                if ( strpos($argument_cond, 'user_Admin')!==false ) { $liste_colonnes_a_indexer['user_Admin'] = 'user_Admin'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('user__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('user').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('user__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('user').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste = [];
            $table = inst('user');
            $res_requete = executer_requete_mysql("SELECT Code_user FROM $table WHERE 1 $argument_cond ORDER BY Code_user ASC $argument_limit;", false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste[] = (int) $row_requete['Code_user'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste);
        }
        return $liste;
    }

/*
    +--------+
    |  task  |
    +--------+
*/

    protected function mf_tester_existance_Code_task( int $Code_task )
    {
        $Code_task = intval($Code_task);
        $requete_sql = "SELECT Code_task FROM ".inst('task')." WHERE Code_task = $Code_task;";
        $cache_db = new Mf_Cachedb('task');
        if (false === $r = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) $r = 'o'; else $r = 'n';
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $r);
        }
        return $r == 'o';
    }

    protected function rechercher_task_Name(string $task_Name, ?int $Code_user = null): int
    {
        $task_Name = format_sql('task_Name', $task_Name);
        $Code_user = intval($Code_user);
        $requete_sql = 'SELECT Code_task FROM '.inst('task')." WHERE task_Name = $task_Name".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )." LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('task');
        if (false === $Code_task = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_task = (int) $row_requete['Code_task'];
            } else {
                $Code_task = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_task);
        }
        return $Code_task;
    }

    protected function rechercher_task_Date_creation(string $task_Date_creation, ?int $Code_user = null): int
    {
        $task_Date_creation = format_sql('task_Date_creation', $task_Date_creation);
        $Code_user = intval($Code_user);
        $requete_sql = 'SELECT Code_task FROM '.inst('task')." WHERE task_Date_creation = $task_Date_creation".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )." LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('task');
        if (false === $Code_task = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_task = (int) $row_requete['Code_task'];
            } else {
                $Code_task = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_task);
        }
        return $Code_task;
    }

    protected function rechercher_task_Workflow(int $task_Workflow, ?int $Code_user = null): int
    {
        $task_Workflow = format_sql('task_Workflow', $task_Workflow);
        $Code_user = intval($Code_user);
        $requete_sql = 'SELECT Code_task FROM '.inst('task')." WHERE task_Workflow = $task_Workflow".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )." LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('task');
        if (false === $Code_task = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_task = (int) $row_requete['Code_task'];
            } else {
                $Code_task = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_task);
        }
        return $Code_task;
    }

    protected function rechercher_task__Code_user(int $Code_user): int
    {
        $requete_sql = 'SELECT Code_task FROM '.inst('task')." WHERE Code_user = $Code_user LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('task');
        if (false === $Code_task = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_task = (int) $row_requete['Code_task'];
            } else {
                $Code_task = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_task);
        }
        return $Code_task;
    }

    protected function __get_liste_Code_task(?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        return $this->get_liste_Code_task(null, $options);
    }

    protected function get_liste_Code_task(?int $Code_user = null, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb('task');
        $cle = "task__lister_cles";
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

        // limit
        $argument_limit = '';
        if (isset($options['limit'][0]) && isset($options['limit'][1])) {
            $argument_limit = " LIMIT {$options['limit'][0]}, {$options['limit'][1]}";
        }
        $cle .= "_$argument_limit";

        if (false === $liste = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'task_Name')!==false ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                if ( strpos($argument_cond, 'task_Date_creation')!==false ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                if ( strpos($argument_cond, 'task_Workflow')!==false ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste = [];
            $table = inst('task');
            $res_requete = executer_requete_mysql("SELECT Code_task FROM $table WHERE 1 ".( $Code_user!=0 ? " AND Code_user=$Code_user" : "" )."$argument_cond ORDER BY Code_task ASC $argument_limit;", false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste[] = (int) $row_requete['Code_task'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste);
        }
        return $liste;
    }

    protected function Code_task_vers_Code_user( int $Code_task )
    {
        $Code_task = intval($Code_task);
        if ($Code_task<0) $Code_task = 0;
        $p = floor($Code_task/100);
        $start = $p*100;
        $end = ($p+1)*100;
        $cache_db = new Mf_Cachedb('task');
        $cle = 'Code_task_vers_Code_user__'.$start.'__'.$end;
        if (false === $conversion = $cache_db->read($cle)) {
            $res_requete = executer_requete_mysql('SELECT Code_task, Code_user FROM '.inst('task').' WHERE '.$start.' <= Code_task AND Code_task < '.$end.';', false);
            $conversion = [];
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $conversion[(int) $row_requete['Code_task']] = (int) $row_requete['Code_user'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $conversion);
        }
        return (isset($conversion[$Code_task]) ? $conversion[$Code_task] : 0);
    }

    protected function liste_Code_user_vers_liste_Code_task( array $liste_Code_user, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options = [];
        }
        $cache_db = new Mf_Cachedb('task');
        $cle = 'liste_Code_user_vers_liste_Code_task__' . Sql_Format_Liste($liste_Code_user);

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        if (false === $liste_Code_task = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'task_Name')!==false ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                if ( strpos($argument_cond, 'task_Date_creation')!==false ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                if ( strpos($argument_cond, 'task_Workflow')!==false ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste_Code_task = [];
            $res_requete = executer_requete_mysql('SELECT Code_task FROM '.inst('task')." WHERE Code_user IN ".Sql_Format_Liste($liste_Code_user).$argument_cond.";", false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste_Code_task[] = (int) $row_requete['Code_task'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste_Code_task);
        }
        return $liste_Code_task;
    }

    protected function task__liste_Code_task_vers_liste_Code_user( array $liste_Code_task, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb("task");
        $cle = "liste_Code_task_vers_liste_Code_user__".Sql_Format_Liste($liste_Code_task);

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        if (false === $liste_Code_user = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'task_Name')!==false ) { $liste_colonnes_a_indexer['task_Name'] = 'task_Name'; }
                if ( strpos($argument_cond, 'task_Date_creation')!==false ) { $liste_colonnes_a_indexer['task_Date_creation'] = 'task_Date_creation'; }
                if ( strpos($argument_cond, 'task_Workflow')!==false ) { $liste_colonnes_a_indexer['task_Workflow'] = 'task_Workflow'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $controle_doublons = [];
            $liste_Code_user = [];
            $res_requete = executer_requete_mysql("SELECT Code_user FROM ".inst('task')." WHERE Code_task IN ".Sql_Format_Liste($liste_Code_task).$argument_cond.";", false);
            while ( $row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC) )
            {
                if ( ! isset($controle_doublons[(int) $row_requete['Code_user']]) )
                {
                    $controle_doublons[(int) $row_requete['Code_user']] = 1;
                    $liste_Code_user[] = (int) $row_requete['Code_user'];
                }
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste_Code_user);
        }
        return $liste_Code_user;
    }

/*
    +---------+
    |  label  |
    +---------+
*/

    protected function mf_tester_existance_Code_label( int $Code_label )
    {
        $Code_label = intval($Code_label);
        $requete_sql = "SELECT Code_label FROM ".inst('label')." WHERE Code_label = $Code_label;";
        $cache_db = new Mf_Cachedb('label');
        if (false === $r = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) $r = 'o'; else $r = 'n';
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $r);
        }
        return $r == 'o';
    }

    protected function rechercher_label_Name(string $label_Name): int
    {
        $label_Name = format_sql('label_Name', $label_Name);
        $requete_sql = 'SELECT Code_label FROM '.inst('label')." WHERE label_Name = $label_Name LIMIT 0, 1;";
        $cache_db = new Mf_Cachedb('label');
        if (false === $Code_label = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $Code_label = (int) $row_requete['Code_label'];
            } else {
                $Code_label = 0;
            }
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $Code_label);
        }
        return $Code_label;
    }

    protected function __get_liste_Code_label(?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        return $this->get_liste_Code_label($options);
    }

    protected function get_liste_Code_label(?array $options = null /* $options = [ 'cond_mysql' => [] ] */)
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb('label');
        $cle = "label__lister_cles";

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        // limit
        $argument_limit = '';
        if (isset($options['limit'][0]) && isset($options['limit'][1])) {
            $argument_limit = " LIMIT {$options['limit'][0]}, {$options['limit'][1]}";
        }
        $cle .= "_$argument_limit";

        if (false === $liste = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'label_Name')!==false ) { $liste_colonnes_a_indexer['label_Name'] = 'label_Name'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('label__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('label').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('label__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('label').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste = [];
            $table = inst('label');
            $res_requete = executer_requete_mysql("SELECT Code_label FROM $table WHERE 1 $argument_cond ORDER BY Code_label ASC $argument_limit;", false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste[] = (int) $row_requete['Code_label'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste);
        }
        return $liste;
    }

/*
    +----------------+
    |  a_task_label  |
    +----------------+
*/

    protected function mf_tester_existance_a_task_label(int $Code_task, int $Code_label)
    {
        $Code_task = intval($Code_task);
        $Code_label = intval($Code_label);
        $requete_sql = 'SELECT * FROM ' . inst('a_task_label') . " WHERE Code_task=$Code_task AND Code_label=$Code_label;";
        $cache_db = new Mf_Cachedb('a_task_label');
        if (false === $r = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) $r = 'o'; else $r = 'n';
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $r);
        }
        return $r=='o';
    }

    protected function a_task_label_liste_Code_task_vers_liste_Code_label(  array $liste_Code_task, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb("a_task_label");
        $cle = "liste_Code_task_vers_liste_Code_label__".Sql_Format_Liste($liste_Code_task);

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        if (false === $liste_Code_label = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'a_task_label_Link')!==false ) { $liste_colonnes_a_indexer['a_task_label_Link'] = 'a_task_label_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('a_task_label__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_task_label').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('a_task_label__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_task_label').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste_Code_label = [];
            $res_requete = executer_requete_mysql('SELECT Code_label FROM '.inst('a_task_label')." WHERE Code_task IN ".Sql_Format_Liste($liste_Code_task).$argument_cond.';', false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste_Code_label[(int) $row_requete['Code_label']] = (int) $row_requete['Code_label'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste_Code_label);
        }
        return $liste_Code_label;
    }

    protected function a_task_label_liste_Code_label_vers_liste_Code_task(  array $liste_Code_label, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb("a_task_label");
        $cle = "liste_Code_label_vers_liste_Code_task__".Sql_Format_Liste($liste_Code_label);

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        if (false === $liste_Code_task = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'a_task_label_Link')!==false ) { $liste_colonnes_a_indexer['a_task_label_Link'] = 'a_task_label_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('a_task_label__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_task_label').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('a_task_label__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_task_label').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste_Code_task = [];
            $res_requete = executer_requete_mysql('SELECT Code_task FROM '.inst('a_task_label')." WHERE Code_label IN ".Sql_Format_Liste($liste_Code_label).$argument_cond.';', false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste_Code_task[(int) $row_requete['Code_task']] = (int) $row_requete['Code_task'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste_Code_task);
        }
        return $liste_Code_task;
    }

/*
    +---------------+
    |  a_user_task  |
    +---------------+
*/

    protected function mf_tester_existance_a_user_task(int $Code_user, int $Code_task)
    {
        $Code_user = intval($Code_user);
        $Code_task = intval($Code_task);
        $requete_sql = 'SELECT * FROM ' . inst('a_user_task') . " WHERE Code_user=$Code_user AND Code_task=$Code_task;";
        $cache_db = new Mf_Cachedb('a_user_task');
        if (false === $r = $cache_db->read($requete_sql)) {
            $res_requete = executer_requete_mysql($requete_sql, false);
            if ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) $r = 'o'; else $r = 'n';
            mysqli_free_result($res_requete);
            $cache_db->write($requete_sql, $r);
        }
        return $r=='o';
    }

    protected function a_user_task_liste_Code_user_vers_liste_Code_task(  array $liste_Code_user, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb("a_user_task");
        $cle = "liste_Code_user_vers_liste_Code_task__".Sql_Format_Liste($liste_Code_user);

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        if (false === $liste_Code_task = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'a_user_task_Link')!==false ) { $liste_colonnes_a_indexer['a_user_task_Link'] = 'a_user_task_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('a_user_task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_user_task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('a_user_task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_user_task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste_Code_task = [];
            $res_requete = executer_requete_mysql('SELECT Code_task FROM '.inst('a_user_task')." WHERE Code_user IN ".Sql_Format_Liste($liste_Code_user).$argument_cond.';', false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste_Code_task[(int) $row_requete['Code_task']] = (int) $row_requete['Code_task'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste_Code_task);
        }
        return $liste_Code_task;
    }

    protected function a_user_task_liste_Code_task_vers_liste_Code_user(  array $liste_Code_task, ?array $options = null /* $options = [ 'cond_mysql' => [] ] */ )
    {
        if ($options === null) {
            $options=[];
        }
        $cache_db = new Mf_Cachedb("a_user_task");
        $cle = "liste_Code_task_vers_liste_Code_user__".Sql_Format_Liste($liste_Code_task);

        // cond_mysql
        $argument_cond = '';
        if (isset($options['cond_mysql'])) {
            foreach ($options['cond_mysql'] as &$condition) {
                $argument_cond .= " AND ($condition)";
            }
            unset($condition);
        }
        $cle .= "_$argument_cond";

        if (false === $liste_Code_user = $cache_db->read($cle)) {

            // Indexes
            $liste_colonnes_a_indexer = [];
            if ($argument_cond != '') {
                if ( strpos($argument_cond, 'a_user_task_Link')!==false ) { $liste_colonnes_a_indexer['a_user_task_Link'] = 'a_user_task_Link'; }
            }
            if (count($liste_colonnes_a_indexer) > 0) {
                if (false === $mf_liste_requete_index = $cache_db->read('a_user_task__index')) {
                    $res_requete_index = executer_requete_mysql('SHOW INDEX FROM `'.inst('a_user_task').'`;', false);
                    $mf_liste_requete_index = [];
                    while ($row_requete_index = mysqli_fetch_array($res_requete_index, MYSQLI_ASSOC)) {
                        $mf_liste_requete_index[$row_requete_index['Column_name']] = $row_requete_index['Column_name'];
                    }
                    mysqli_free_result($res_requete_index);
                    $cache_db->write('a_user_task__index', $mf_liste_requete_index);
                }
                foreach ($mf_liste_requete_index as $mf_colonne_indexee) {
                    if ( isset($liste_colonnes_a_indexer[$mf_colonne_indexee]) ) unset($liste_colonnes_a_indexer[$mf_colonne_indexee]);
                }
                if (count($liste_colonnes_a_indexer) > 0) {
                    foreach ($liste_colonnes_a_indexer as $colonnes_a_indexer) {
                        executer_requete_mysql('ALTER TABLE `'.inst('a_user_task').'` ADD INDEX(`' . $colonnes_a_indexer . '`);');
                    }
                    $cache_db->clear();
                }
            }

            $liste_Code_user = [];
            $res_requete = executer_requete_mysql('SELECT Code_user FROM '.inst('a_user_task')." WHERE Code_task IN ".Sql_Format_Liste($liste_Code_task).$argument_cond.';', false);
            while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                $liste_Code_user[(int) $row_requete['Code_user']] = (int) $row_requete['Code_user'];
            }
            mysqli_free_result($res_requete);
            $cache_db->write($cle, $liste_Code_user);
        }
        return $liste_Code_user;
    }

/*
    +-------+
    |  ###  |
    +-------+
*/

    private $mf_dependances = null;
    private $mf_type_table_enfant;

    private function initialisation_dependances()
    {
        $this->mf_dependances=[];
        $this->mf_dependances['user'][]='task';
        $this->mf_dependances['task'][]='a_task_label';
        $this->mf_dependances['label'][]='a_task_label';
        $this->mf_dependances['user'][]='a_user_task';
        $this->mf_dependances['task'][]='a_user_task';

        $this->mf_type_table_enfant=[];
        $this->mf_type_table_enfant['task']='entite';
        $this->mf_type_table_enfant['a_task_label']='association';
        $this->mf_type_table_enfant['a_user_task']='association';
    }

    protected function get_liste_tables_enfants( string $table )
    {
        $liste_tables_enfants = [];
        if ( isset($this->mf_dependances[$table]) )
        {
            foreach ($this->mf_dependances[$table] as $table_fille)
            {
                $liste_tables_enfants[] = $table_fille;
            }
        }
        return $liste_tables_enfants;
    }

    protected function supprimer_donnes_en_cascade(string $nom_table, array $liste_codes)
    {
        if ($this->mf_dependances == null) {
            $this->initialisation_dependances();
        }
        $liste_tables_enfants = $this->get_liste_tables_enfants($nom_table);
        foreach ($liste_tables_enfants as $table_enfant) {
            if ($this->mf_type_table_enfant[$table_enfant] == 'entite') {
                $liste_codes_enfants=[];
                $res_requete = executer_requete_mysql('SELECT Code_'.$table_enfant . ' FROM ' . inst($table_enfant) . ' WHERE Code_' . $nom_table . ' IN ' . Sql_Format_Liste($liste_codes) . ';', false);
                while ($row_requete = mysqli_fetch_array($res_requete, MYSQLI_ASSOC)) {
                    $liste_codes_enfants[]=$row_requete['Code_' . $table_enfant];
                }
                mysqli_free_result($res_requete);
                if (count($liste_codes_enfants) > 0) {
                    $this->supprimer_donnes_en_cascade($table_enfant, $liste_codes_enfants);
                    executer_requete_mysql('DELETE IGNORE FROM '.inst($table_enfant).' WHERE Code_'.$table_enfant.' IN '.Sql_Format_Liste($liste_codes_enfants).';', array_search($table_enfant, LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                    $cache_db = new Mf_Cachedb($table_enfant);
                    $cache_db->clear();
                }
            } else {
                executer_requete_mysql('DELETE IGNORE FROM '.inst($table_enfant).' WHERE Code_'.$nom_table.' IN '.Sql_Format_Liste($liste_codes).';', array_search($table_enfant, LISTE_TABLES_HISTORIQUE_DESACTIVE) === false);
                if (requete_mysqli_affected_rows() > 0) {
                    $cache_db = new Mf_Cachedb($table_enfant);
                    $cache_db->clear();
                }
            }
        }
    }

}
