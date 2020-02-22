<?php
include __DIR__ . '/../../../systeme/myorganizer/dblayer_light.php';

$method = $_SERVER['REQUEST_METHOD']; // GET, PUT, POST, DELETE
$_url = '';
$input = file_get_contents('php://input');
$s = 0; // nouvelles tentatives en cas d'échec
while ($input === false && $s < 9) {
    sleep(1);
    $input = file_get_contents('php://input');
    $s ++;
}

$options = array();
foreach ($_GET as $key => $value) {
    if ($key == '_url') {
        $_url = $value;
    } elseif ($key != 'api_token') {
        $options[strtolower($key)] = $value;
    }
}

$url_tableau = explode('/', substr($_url, 1));

$max = count($url_tableau);
if ($max % 2 == 0) {
    $fonction_api = $url_tableau[$max - 2];
    $id = $url_tableau[$max - 1];
    $max -= 2;
} else {
    $fonction_api = $url_tableau[$max - 1];
    $id = '';
    $max -= 1;
}
for ($i = 0; $i < $max; $i += 2) {
    $options['code_' . strtolower($url_tableau[$i])] = $url_tableau[$i + 1];
}

$retour_json = array();

$log = '';

if (file_exists("api__$fonction_api.php")) {
    include __DIR__ . "/api__$fonction_api.php";
    // log
    if ($fonction_api != 'mf_connexion.php' && ($method == 'POST' || $method == 'PUT' || $method == 'DELETE')) {
        $log = "[$method] $_url $input";
    }
    // exécution
    switch ($method) {
        case 'GET':
            $retour_json['data'] = @get($id, $options);
            if (isset($retour_json['data']['http_response_code'])) {
                http_response_code($retour_json['data']['http_response_code']);
                unset($retour_json['data']['http_response_code']);
            }
            break; // renvoie des données
        case 'POST':
            $retour_json['data'] = @post(json_decode($input, true), $options);
            if (isset($retour_json['data']['code_erreur']) && $retour_json['data']['code_erreur'] == 0) {
                if ($fonction_api != 'mf_connexion') {
                    http_response_code(201);
                    if (isset($retour_json['data']['id'])) {
                        if ($fonction_api == 'mf_inscription') {
                            $fonction_api = 'compte';
                        }
                        header('Location: ' . ADRESSE_API . "$fonction_api/{$retour_json['data']['id']}");
                    }
                }
            }
            $cache = new Cachehtml();
            $cache->clear();
            break; // ajoute des données
        case 'PUT':
            $retour_json['data'] = @put($id, json_decode($input, true), $options);
            $cache = new Cachehtml();
            $cache->clear();
            break; // modifie des données
        case 'DELETE':
            $retour_json['data'] = @delete($id, $options);
            $cache = new Cachehtml();
            $cache->clear();
            break; // supprime des données
        case 'OPTIONS':
            $retour_json['options'] = @options($id, json_decode($input, true), $options);
            $cache = new Cachehtml();
            $cache->clear();
            break; // accès aux options
    }
}

if (isset($retour_json['data']['code_erreur'])) {
    $retour_json['error']['number'] = $retour_json['data']['code_erreur'];
    unset($retour_json['data']['code_erreur']);
    $retour_json['error']['label'] = ($mf_message_erreur_personalise == '' ? $mf_libelle_erreur[$retour_json['error']['number']] : $mf_message_erreur_personalise);
} elseif (isset($retour_json['options']['code_erreur'])) {
    $retour_json['error']['number'] = $retour_json['options']['code_erreur'];
    unset($retour_json['options']['code_erreur']);
    $retour_json['error']['label'] = $mf_libelle_erreur[$retour_json['error']['number']];
}

// Suppression des caractères non supportés par le format json
mf_verification_avant_conversion_json($retour_json);

$http_res_code = http_response_code();
$retour_json['resp'] = [
    'status' => $http_res_code,
    'status_msg' => mf_message_http_response_code($http_res_code),
    'execution_time' => get_execution_time()
];

// log
if ($log != '') {
    log_api($log . PHP_EOL . json_encode($retour_json));
}

fermeture_connexion_db();

// format
$format = isset($_GET['format']) ? $_GET['format'] : 'json';

switch ($format) {
    case 'json':
        header('Content-Type: application/json');
        echo json_encode($retour_json);
        break;
    case 'table':
        echo '<!DOCTYPE html><html lang=""><head><meta charset="UTF-8"><title></title></head><body>' . vue_tableau_html($retour_json) . '</body></html>';
        break;
    case 'excel':
        echo '<!DOCTYPE html><html lang=""><head><meta charset="UTF-8"><title></title></head><body>' . vue_tableau_html($retour_json, '\'') . '</body></html>';
        break;
}
