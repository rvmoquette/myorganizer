<?php declare(strict_types=1);
include __DIR__ . '/../../../systeme/myorganizer/acces_api_rest/label.php';

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
        if (API_REST_ACCESS_GET_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_GET_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_GET_LABEL == 'all') {
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
    $table_label = new label();
    if ($id == 0) {
        $l = array_values($table_label->mf_lister(['autocompletion' => true, 'limit' => [0, NB_RESULT_MAX_API]]));
        return array_merge($l, ['code_erreur' => (count($l) == NB_RESULT_MAX_API ? 8 : 0 )]);
    } else {
        $r = $table_label->mf_get($id, ['autocompletion' => true]);
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
        if (API_REST_ACCESS_POST_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_POST_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_POST_LABEL == 'all') {
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

    $table_label = new label();
    if (is_array(current($data))) {
        $liste_Code_label = $table_label->mf_liste_Code_label(  );
        $retour = $table_label -> mf_supprimer_2($liste_Code_label);
        if ($retour['code_erreur'] == 0) {
            foreach ($data as $value) {
                $retour = $table_label->mf_ajouter_2($value);
                unset($retour['Code_label']);
                if ($retour['code_erreur'] != 0) {
                    return $retour;
                }
            }
        }
    } else {
        if ($retour['Code_label'] = $table_label->mf_search($data)) {
            $retour['code_erreur'] = 0;
            $table_label->mf_modifier_2([$retour['Code_label']=>$data]);
            $retour['callback'] = Hook_label::callback_post($retour['Code_label']);
        } else {
            $retour = $table_label->mf_ajouter_2($data);
        }
        $retour['id'] = ( $retour['Code_label']!=0 ? $retour['Code_label'] : '' );
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
        if (API_REST_ACCESS_PUT_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_PUT_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_PUT_LABEL == 'all') {
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

    $table_label = new label();
    return $table_label->mf_modifier_2([$id=>$data]);
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
        if (API_REST_ACCESS_DELETE_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_DELETE_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_DELETE_LABEL == 'all') {
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
        $table_label = new label();
        return $table_label->mf_supprimer($id);
    }
    else
    {
        $table_label = new label();
        $liste_Code_label = $table_label->mf_liste_Code_label();
        return $table_label->mf_supprimer_2($liste_Code_label);
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
        if (API_REST_ACCESS_OPTIONS_LABEL == 'none') {
            return ['code_erreur' => 1]; // erreur de connexion
        }
        elseif (API_REST_ACCESS_OPTIONS_LABEL == 'user') {
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
        } elseif (API_REST_ACCESS_OPTIONS_LABEL == 'all') {
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
    $table_label = new label();
    Hook_label::hook_actualiser_les_droits_ajouter();
    Hook_label::hook_actualiser_les_droits_modifier($id);
    Hook_label::hook_actualiser_les_droits_supprimer($id);
    $authorization = [];
    global $mf_droits_defaut;
    $authorization['POST'] = $mf_droits_defaut['label__AJOUTER'];
    $authorization['PUT'] = $mf_droits_defaut['label__MODIFIER'];
    $authorization['PUT:label_Name'] = $mf_droits_defaut['api_modifier__label_Name'];
    $authorization['DELETE'] = $mf_droits_defaut['label__SUPPRIMER'];
    return ['code_erreur' => 0, 'authorization' => $authorization];
}
