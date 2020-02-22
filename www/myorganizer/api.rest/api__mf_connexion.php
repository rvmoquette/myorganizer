<?php

function get($id, $options)
{
    return array();
}

function post($data, $options)
{
    $retour_json['code_erreur'] = 2;
    $retour_json['mf_token'] = '';
    $mf_login = ( isset($data['mf_login']) ? $data['mf_login'] : '' );
    $mf_pwd = ( isset($data['mf_pwd']) ? $data['mf_pwd'] : '' );

    $mf_connexion = new Mf_Connexion(true);
    if ( $mf_token = $mf_connexion->connexion($mf_login, $mf_pwd) )
    {
        $retour_json['mf_token'] = $mf_token;
        $retour_json['id'] = get_user_courant(MF_USER__ID);
        $retour_json['code_erreur'] = 0;
    }

    return $retour_json;
}

function put($id, $data, $options)
{
    return array();
}

function delete($id, $options)
{
    return array();
}
