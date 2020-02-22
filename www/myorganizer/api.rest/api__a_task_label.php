<?php declare(strict_types=1);
include __DIR__ . '/../../../systeme/myorganizer/acces_api_rest/a_task_label.php';

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
        if (API_REST_ACCESS_GET_A_TASK_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_GET_A_TASK_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_GET_A_TASK_LABEL == 'all') {
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

    $table_a_task_label = new a_task_label();
    if ($id != '') {
        $table_id = explode('-', $id);
        $code_task = isset($table_id[0]) ? (int) $table_id[0] : -1;
        $code_label = isset($table_id[1]) ? (int) $table_id[1] : -1;
    } else {
        $code_task = isset($options['code_task']) ? $options['code_task'] : 0;
        $code_label = isset($options['code_label']) ? $options['code_label'] : 0;
    }
    $l = $table_a_task_label->mf_lister($code_task, $code_label, ['autocompletion' => true, 'limit' => [0, NB_RESULT_MAX_API]]);
    foreach ($l as $k => &$v) {
        $v = array_merge(['Code_a_task_label'=>$k], $v);
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
        if (API_REST_ACCESS_POST_A_TASK_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_POST_A_TASK_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_POST_A_TASK_LABEL == 'all') {
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

    $table_a_task_label = new a_task_label();
    if (is_array(current($data))) {
        $retour = $table_a_task_label -> mf_supprimer( ( isset($options['code_task']) ? $options['code_task'] : 0 ), ( isset($options['code_label']) ? $options['code_label'] : 0 ) );
        if ($retour['code_erreur'] == 0) {
            foreach ($data as $value) {
                if (isset($options['code_task'])) $value['Code_task'] = $options['code_task'];
                if (isset($options['code_label'])) $value['Code_label'] = $options['code_label'];
                $retour = $table_a_task_label->mf_ajouter_2($value);
                if ($retour['code_erreur'] != 0) {
                    return $retour;
                }
            }
        }
    } else {
        if (isset($options['code_task'])) {
            $data['Code_task'] = $options['code_task'];
        } elseif(! isset($data['Code_task'])) {
            $data['Code_task'] = 0;
        }
        if (isset($options['code_label'])) {
            $data['Code_label'] = $options['code_label'];
        } elseif(! isset($data['Code_label'])) {
            $data['Code_label'] = 0;
        }
        $a_task_label = $table_a_task_label->mf_get( $data['Code_task'], $data['Code_label'] );
        if (isset($a_task_label['Code_task'])) {
            $retour['code_erreur'] = 0;
            $table_a_task_label->mf_modifier_2([$data]);
            $retour['callback'] = Hook_a_task_label::callback_post( $data['Code_task'], $data['Code_label'] );
        } else {
            $retour = $table_a_task_label->mf_ajouter_2($data);
        }
        if ($retour['code_erreur'] == 0) {
            if (isset($retour['Code_task'])) {
                $retour['id'] = $retour['Code_task'] . '-' . $retour['Code_label'];
            } else {
                $retour['id'] = $data['Code_task'] . '-' . $data['Code_label'];
            }
        } else {
            $retour['id'] = '';
        }
        unset($retour['Code_task']);
        unset($retour['Code_label']);
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
        if (API_REST_ACCESS_PUT_A_TASK_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_PUT_A_TASK_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_PUT_A_TASK_LABEL == 'all') {
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

    $table_a_task_label = new a_task_label();
    $codes = explode('-', $id);
    $data['Code_task']=$codes[0];
    $data['Code_label']=$codes[1];
    return $table_a_task_label->mf_modifier_2([$data]);
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
        if (API_REST_ACCESS_DELETE_A_TASK_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_DELETE_A_TASK_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_DELETE_A_TASK_LABEL == 'all') {
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
        $table_a_task_label = new a_task_label();
        $codes = explode('-', $id);
        return $table_a_task_label->mf_supprimer((isset($codes[0]) && intval($codes[0])!=0 ? intval($codes[0]) : -1), (isset($codes[1]) && intval($codes[1])!=0 ? intval($codes[1]) : -1));
    }
    else
    {
        $table_a_task_label = new a_task_label();
        $Code_task = ( isset($options['code_task']) ? $options['code_task'] : 0 );
        $Code_label = ( isset($options['code_label']) ? $options['code_label'] : 0 );
        return $table_a_task_label->mf_supprimer($Code_task, $Code_label);
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
        if (API_REST_ACCESS_OPTIONS_A_TASK_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_OPTIONS_A_TASK_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_OPTIONS_A_TASK_LABEL == 'all') {
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

    $table_a_task_label = new a_task_label();
    if ($id != '') {
        $table_id = explode('-', $id);
        $code_task = isset($table_id[0]) ? (int) $table_id[0] : -1;
        $code_label = isset($table_id[1]) ? (int) $table_id[1] : -1;
    } else {
        $code_task = isset($options['code_task']) ? $options['code_task'] : 0;
        $code_label = isset($options['code_label']) ? $options['code_label'] : 0;
    }
    Hook_a_task_label::hook_actualiser_les_droits_ajouter($code_task, $code_label);
    Hook_a_task_label::hook_actualiser_les_droits_modifier($code_task, $code_label);
    Hook_a_task_label::hook_actualiser_les_droits_supprimer($code_task, $code_label);
    $authorization = [];
    global $mf_droits_defaut;
    $authorization['POST'] = $mf_droits_defaut['a_task_label__AJOUTER'];
    $authorization['PUT'] = $mf_droits_defaut['a_task_label__MODIFIER'];
    $authorization['PUT:a_task_label_Link'] = $mf_droits_defaut['api_modifier__a_task_label_Link'];
    $authorization['DELETE'] = $mf_droits_defaut['a_task_label__SUPPRIMER'];
    return ['code_erreur' => 0, 'authorization' => $authorization];
}
