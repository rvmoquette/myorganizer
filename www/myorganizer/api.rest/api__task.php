<?php declare(strict_types=1);
include __DIR__ . '/../../../systeme/myorganizer/acces_api_rest/task.php';

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
        if (API_REST_ACCESS_GET_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_GET_TASK == 'user') {
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
        } elseif (API_REST_ACCESS_GET_TASK == 'all') {
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

    $id = intval($id);
    $table_task = new task();
    if ($id == 0) {
        $code_user = isset($options['code_user']) ? $options['code_user'] : 0;
        $l = array_values($table_task->mf_lister($code_user, ['autocompletion' => true, 'limit' => [0, NB_RESULT_MAX_API]]));
        return array_merge($l, ['code_erreur' => (count($l) == NB_RESULT_MAX_API ? 8 : 0 )]);
    } else {
        $r = $table_task->mf_get($id, ['autocompletion' => true]);
        if ($r === []) {
            return ['http_response_code' => 404, 'code_erreur' => 0];
        } else {
            return array_merge( [$r], ['code_erreur' => 0] );
        }
    }
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
        if (API_REST_ACCESS_POST_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_POST_TASK == 'user') {
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
        } elseif (API_REST_ACCESS_POST_TASK == 'all') {
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

    $table_task = new task();
    if (is_array(current($data))) {
        $liste_Code_task = $table_task->mf_liste_Code_task( ( isset($options['code_user']) ? $options['code_user'] : 0 ) );
        $retour = $table_task -> mf_supprimer_2($liste_Code_task);
        if ($retour['code_erreur'] == 0) {
            foreach ($data as $value) {
                if (isset($options['code_user'])) $value['Code_user'] = $options['code_user'];
                $retour = $table_task->mf_ajouter_2($value);
                unset($retour['Code_task']);
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
        if ($retour['Code_task'] = $table_task->mf_search($data)) {
            $retour['code_erreur'] = 0;
            $table_task->mf_modifier_2([$retour['Code_task']=>$data]);
            $retour['callback'] = Hook_task::callback_post($retour['Code_task']);
        } else {
            $retour = $table_task->mf_ajouter_2($data);
        }
        $retour['id'] = ( $retour['Code_task']!=0 ? $retour['Code_task'] : '' );
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
        if (API_REST_ACCESS_PUT_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_PUT_TASK == 'user') {
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
        } elseif (API_REST_ACCESS_PUT_TASK == 'all') {
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

    $table_task = new task();
    return $table_task->mf_modifier_2([$id=>$data]);
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
        if (API_REST_ACCESS_DELETE_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_DELETE_TASK == 'user') {
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
        } elseif (API_REST_ACCESS_DELETE_TASK == 'all') {
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
        $table_task = new task();
        return $table_task->mf_supprimer($id);
    }
    else
    {
        $table_task = new task();
        $Code_user = ( isset($options['code_user']) ? $options['code_user'] : 0 );
        $liste_Code_task = $table_task->mf_liste_Code_task($Code_user);
        return $table_task->mf_supprimer_2($liste_Code_task);
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
        if (API_REST_ACCESS_OPTIONS_TASK == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_OPTIONS_TASK == 'user') {
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
        } elseif (API_REST_ACCESS_OPTIONS_TASK == 'all') {
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

    $id = intval($id);
    $table_task = new task();
    $code_user = isset($options['code_user']) ? $options['code_user'] : 0;
    Hook_task::hook_actualiser_les_droits_ajouter($code_user);
    Hook_task::hook_actualiser_les_droits_modifier($id);
    Hook_task::hook_actualiser_les_droits_supprimer($id);
    $authorization = [];
    global $mf_droits_defaut;
    $authorization['POST'] = $mf_droits_defaut['task__AJOUTER'];
    $authorization['PUT'] = $mf_droits_defaut['task__MODIFIER'];
    $authorization['PUT:task_Name'] = $mf_droits_defaut['api_modifier__task_Name'];
    $authorization['PUT:task_Date_creation'] = $mf_droits_defaut['api_modifier__task_Date_creation'];
    $authorization['PUT:task_Description'] = $mf_droits_defaut['api_modifier__task_Description'];
    $authorization['PUT:task_Workflow'] = $mf_droits_defaut['api_modifier__task_Workflow'];
    $authorization['PUT:Code_user'] = $mf_droits_defaut['api_modifier_ref__task__Code_user'];
    $authorization['DELETE'] = $mf_droits_defaut['task__SUPPRIMER'];
    return ['code_erreur' => 0, 'authorization' => $authorization];
}
