<?php

function get($id, $options)
{
    return array();
}

function post($data, $options)
{
    $mf_login = ( isset($data['mf_login']) ? $data['mf_login'] : '' );
    $mf_email = ( isset($data['mf_email']) ? $data['mf_email'] : '' );
    $mf_connexion = new Mf_Connexion(true);
    if (ACTIVER_CONNEXION_EMAIL)
    {
        return $mf_connexion->regenerer_mot_de_passe_email($mf_email);
    }
    else
    {
        return $mf_connexion->regenerer_mot_de_passe($mf_login, $mf_email);
    }
}

function put($id, $data, $options)
{
    return array();
}

function delete($id, $options)
{
    return array();
}
