<?php declare(strict_types=1);
include __DIR__ . '/../../../systeme/myorganizer/acces_api_rest/a_user_task.php';

function get($id, $options)
{
    if (isset($options['mf_connector_token']) && $options['mf_connector_token'] != '') {
        $db = new DB();
        $code = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_search__colonne(CONNECTEUR_API_COLONNE_TOKEN, $options['mf_connector_token']);
        $r = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_get($code);
        $totay = substr(get_now(), 0, 10);
        if (! ($code != 0 && $r[CONNECTEUR_API_COLONNE_DATE_START] <= $totay && $totay <= $r[CONNECTEUR_API_COLONNE_DATE_STOP])) {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        if (isset($r['Code_user'])) {
            global $user_courant;
            $user_courant = $db -> user() -> mf_get_2($r['Code_user']);
        }
    } else {
        if (API_REST_ACCESS_GET_A_USER_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_GET_A_USER_TASK == 'user') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                if (! $mf_connexion->est_connecte($mf_token)) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
                if (! isset($options['code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'])) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
                if (! isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
            }
        } elseif (API_REST_ACCESS_GET_A_USER_TASK == 'all') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                $mf_connexion->est_connecte($mf_token, false);
                if (! isset($options['code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'], false)) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
            }
        } else {
            return ['code_erreur' => 1]; // erreur de connexion
        }
    }

    session_write_close();

    $table_a_user_task = new a_user_task();
    if ($id != '') {
        $table_id = explode('-', $id);
        $code_user = isset($table_id[0]) ? (int) $table_id[0] : -1;
        $code_task = isset($table_id[1]) ? (int) $table_id[1] : -1;
    } else {
        $code_user = isset($options['code_user']) ? $options['code_user'] : 0;
        $code_task = isset($options['code_task']) ? $options['code_task'] : 0;
    }
    $l = $table_a_user_task->mf_lister($code_user, $code_task, ['autocompletion' => true, 'limit' => [0, NB_RESULT_MAX_API]]);
    foreach ($l as $k => &$v) {
        $v = array_merge(['Code_a_user_task'=>$k], $v);
    }
    unset($v);
    $l = array_values($l);
    if ($id != '' && count($l) == 0) {
        $l['http_response_code'] = 404;
    }
    $l['code_erreur'] = (count($l) == NB_RESULT_MAX_API ? 8 : 0);
    return $l;
}

function post($data, $options)
{
    if (isset($options['mf_connector_token']) && $options['mf_connector_token'] != '') {
        $db = new DB();
        $code = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_search__colonne(CONNECTEUR_API_COLONNE_TOKEN, $options['mf_connector_token']);
        $r = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_get($code);
        $totay = substr(get_now(), 0, 10);
        if (! ($code != 0 && $r[CONNECTEUR_API_COLONNE_DATE_START] <= $totay && $totay <= $r[CONNECTEUR_API_COLONNE_DATE_STOP])) {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        if (isset($r['Code_user'])) {
            global $user_courant;
            $user_courant = $db -> user() -> mf_get_2($r['Code_user']);
        }
    } else {
        if (API_REST_ACCESS_POST_A_USER_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_POST_A_USER_TASK == 'user') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                if (! $mf_connexion->est_connecte($mf_token)) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
                if (! isset($options['code_user']) && ! isset($data['Code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'])) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
                if (! isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
            }
        } elseif (API_REST_ACCESS_POST_A_USER_TASK == 'all') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                $mf_connexion->est_connecte($mf_token, false);
                if (! isset($options['code_user']) && ! isset($data['Code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'], false)) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
            }
        } else {
            return ['code_erreur' => 1]; // erreur de connexion
        }
    }

    session_write_close();

    $table_a_user_task = new a_user_task();
    if (is_array(current($data))) {
        $retour = $table_a_user_task -> mf_supprimer( ( isset($options['code_user']) ? $options['code_user'] : 0 ), ( isset($options['code_task']) ? $options['code_task'] : 0 ) );
        if ($retour['code_erreur'] == 0) {
            foreach ($data as $value) {
                if (isset($options['code_user'])) $value['Code_user'] = $options['code_user'];
                if (isset($options['code_task'])) $value['Code_task'] = $options['code_task'];
                $retour = $table_a_user_task->mf_ajouter_2($value);
                if ($retour['code_erreur'] != 0) {
                    return $retour;
                }
            }
        }
    } else {
        if (isset($options['code_user'])) {
            $data['Code_user'] = $options['code_user'];
        } elseif(! isset($data['Code_user'])) {
            $data['Code_user'] = 0;
        }
        if (isset($options['code_task'])) {
            $data['Code_task'] = $options['code_task'];
        } elseif(! isset($data['Code_task'])) {
            $data['Code_task'] = 0;
        }
        $a_user_task = $table_a_user_task->mf_get( $data['Code_user'], $data['Code_task'] );
        if (isset($a_user_task['Code_user'])) {
            $retour['code_erreur'] = 0;
            $table_a_user_task->mf_modifier_2([$data]);
            $retour['callback'] = Hook_a_user_task::callback_post( $data['Code_user'], $data['Code_task'] );
        } else {
            $retour = $table_a_user_task->mf_ajouter_2($data);
        }
        if ($retour['code_erreur'] == 0) {
            if (isset($retour['Code_user'])) {
                $retour['id'] = $retour['Code_user'] . '-' . $retour['Code_task'];
            } else {
                $retour['id'] = $data['Code_user'] . '-' . $data['Code_task'];
            }
        } else {
            $retour['id'] = '';
        }
        unset($retour['Code_user']);
        unset($retour['Code_task']);
    }
    return $retour;
}

function put($id, $data, $options)
{
    if (isset($options['mf_connector_token']) && $options['mf_connector_token'] != '') {
        $db = new DB();
        $code = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_search__colonne(CONNECTEUR_API_COLONNE_TOKEN, $options['mf_connector_token']);
        $r = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_get($code);
        $totay = substr(get_now(), 0, 10);
        if (! ($code != 0 && $r[CONNECTEUR_API_COLONNE_DATE_START] <= $totay && $totay <= $r[CONNECTEUR_API_COLONNE_DATE_STOP])) {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        if (isset($r['Code_user'])) {
            global $user_courant;
            $user_courant = $db -> user() -> mf_get_2($r['Code_user']);
        }
    } else {
        if (API_REST_ACCESS_PUT_A_USER_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_PUT_A_USER_TASK == 'user') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                if (! $mf_connexion->est_connecte($mf_token)) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
                if (! isset($options['code_user']) && ! isset($data['Code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'])) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
                if (! isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
            }
        } elseif (API_REST_ACCESS_PUT_A_USER_TASK == 'all') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                $mf_connexion->est_connecte($mf_token, false);
                if (! isset($options['code_user']) && ! isset($data['Code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'], false)) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
            }
        } else {
            return ['code_erreur' => 1]; // erreur de connexion
        }
    }

    session_write_close();

    $table_a_user_task = new a_user_task();
    $codes = explode('-', $id);
    $data['Code_user']=$codes[0];
    $data['Code_task']=$codes[1];
    return $table_a_user_task->mf_modifier_2([$data]);
}

function delete($id, $options)
{
    if (isset($options['mf_connector_token']) && $options['mf_connector_token'] != '') {
        $db = new DB();
        $code = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_search__colonne(CONNECTEUR_API_COLONNE_TOKEN, $options['mf_connector_token']);
        $r = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_get($code);
        $totay = substr(get_now(), 0, 10);
        if (! ($code != 0 && $r[CONNECTEUR_API_COLONNE_DATE_START] <= $totay && $totay <= $r[CONNECTEUR_API_COLONNE_DATE_STOP])) {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        if (isset($r['Code_user'])) {
            global $user_courant;
            $user_courant = $db -> user() -> mf_get_2($r['Code_user']);
        }
    } else {
        if (API_REST_ACCESS_DELETE_A_USER_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_DELETE_A_USER_TASK == 'user') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                if (! $mf_connexion->est_connecte($mf_token)) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
                if (! isset($options['code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'])) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
                if (! isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
            }
        } elseif (API_REST_ACCESS_DELETE_A_USER_TASK == 'all') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                $mf_connexion->est_connecte($mf_token, false);
                if (! isset($options['code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'], false)) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
            }
        } else {
            return ['code_erreur' => 1]; // erreur de connexion
        }
    }

    session_write_close();

    if ( $id!='' )
    {
        $table_a_user_task = new a_user_task();
        $codes = explode('-', $id);
        return $table_a_user_task->mf_supprimer((isset($codes[0]) && intval($codes[0])!=0 ? intval($codes[0]) : -1), (isset($codes[1]) && intval($codes[1])!=0 ? intval($codes[1]) : -1));
    }
    else
    {
        $table_a_user_task = new a_user_task();
        $Code_user = ( isset($options['code_user']) ? $options['code_user'] : 0 );
        $Code_task = ( isset($options['code_task']) ? $options['code_task'] : 0 );
        return $table_a_user_task->mf_supprimer($Code_user, $Code_task);
    }
}

function options($id, $options)
{
    if (isset($options['mf_connector_token']) && $options['mf_connector_token'] != '') {
        $db = new DB();
        $code = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_search__colonne(CONNECTEUR_API_COLONNE_TOKEN, $options['mf_connector_token']);
        $r = $db -> mf_table(CONNECTEUR_API_TABLE) -> mf_get($code);
        $totay = substr(get_now(), 0, 10);
        if (! ($code != 0 && $r[CONNECTEUR_API_COLONNE_DATE_START] <= $totay && $totay <= $r[CONNECTEUR_API_COLONNE_DATE_STOP])) {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        if (isset($r['Code_user'])) {
            global $user_courant;
            $user_courant = $db -> user() -> mf_get_2($r['Code_user']);
        }
    } else {
        if (API_REST_ACCESS_OPTIONS_A_USER_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_OPTIONS_A_USER_TASK == 'user') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                if (! $mf_connexion->est_connecte($mf_token)) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
                if (! isset($options['code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'])) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
                if (! isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    return ['code_erreur' => 1]; // erreur de connexion
                }
            }
        } elseif (API_REST_ACCESS_OPTIONS_A_USER_TASK == 'all') {
            $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
            if ($auth == 'api') {
                $mf_connexion = new Mf_Connexion(true);
                $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
                $mf_connexion->est_connecte($mf_token, false);
                if (! isset($options['code_user'])) {
                    $options['code_user'] = get_user_courant('Code_user');
                }
            } elseif ($auth == 'main') {
                $mf_connexion = new Mf_Connexion();
                if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
                    if (! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token'], false)) {
                        unset($_SESSION[PREFIXE_SESSION]['token']);
                    }
                }
            }
        } else {
            return ['code_erreur' => 1]; // erreur de connexion
        }
    }

    session_write_close();

    $table_a_user_task = new a_user_task();
    if ($id != '') {
        $table_id = explode('-', $id);
        $code_user = isset($table_id[0]) ? (int) $table_id[0] : -1;
        $code_task = isset($table_id[1]) ? (int) $table_id[1] : -1;
    } else {
        $code_user = isset($options['code_user']) ? $options['code_user'] : 0;
        $code_task = isset($options['code_task']) ? $options['code_task'] : 0;
    }
    Hook_a_user_task::hook_actualiser_les_droits_ajouter($code_user, $code_task);
    Hook_a_user_task::hook_actualiser_les_droits_modifier($code_user, $code_task);
    Hook_a_user_task::hook_actualiser_les_droits_supprimer($code_user, $code_task);
    $authorization = [];
    global $mf_droits_defaut;
    $authorization['POST'] = $mf_droits_defaut['a_user_task__AJOUTER'];
    $authorization['PUT'] = $mf_droits_defaut['a_user_task__MODIFIER'];
    $authorization['PUT:a_user_task_Link'] = $mf_droits_defaut['api_modifier__a_user_task_Link'];
    $authorization['DELETE'] = $mf_droits_defaut['a_user_task__SUPPRIMER'];
    return ['code_erreur' => 0, 'authorization' => $authorization];
}
