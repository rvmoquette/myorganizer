<?php

function get($id, $options)
{
    return array();
}

function post($data, $options)
{
    return array();
}

function put($id, $data, $options)
{
    $auth = isset($_GET['auth']) ? $_GET['auth'] : 'api';
    if ( $auth=='api' )
    {
        $mf_connexion = new Mf_Connexion(true);
        $mf_token = isset($options['mf_token']) ? $options['mf_token'] : '';
        if ( ! $mf_connexion->est_connecte($mf_token) )
        {
            return array('code_erreur' => 1); // erreur de connexion
        }
        if ( ! isset($options['code_user']) )
        {
            $options['code_user'] = $user_courant['Code_user'];
        }
    }
    elseif ( $auth=='main' )
    {
        $mf_connexion = new Mf_Connexion();
        if ( isset($_SESSION[PREFIXE_SESSION]['token']) )
        {
            if ( ! $mf_connexion->est_connecte($_SESSION[PREFIXE_SESSION]['token']) )
            {
                unset($_SESSION[PREFIXE_SESSION]['token']);
            }
        }
        if ( ! isset($_SESSION[PREFIXE_SESSION]['token']) )
        {
            return array('code_erreur' => 1); // erreur de connexion
        }
    }
    $mf_current_pwd = ( isset($data['mf_current_pwd']) ? $data['mf_current_pwd'] : '' );
    $mf_new_pwd = ( isset($data['mf_new_pwd']) ? $data['mf_new_pwd'] : '' );
    $mf_conf_pwd = ( isset($data['mf_conf_pwd']) ? $data['mf_conf_pwd'] : '' );
    $mf_connexion = new Mf_Connexion(true);
    return $mf_connexion->changer_mot_de_passe($id, $mf_current_pwd, $mf_new_pwd, $mf_conf_pwd);
}

function delete($id, $options)
{
    return array();
}
