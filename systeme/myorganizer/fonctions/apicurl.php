<?php

class Apicurl
{

    private $url_api;

    function __construct($url_api)
    {
        $this->url_api = $url_api;
    }

    function get($appel_api) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $this->url_api.$appel_api );
        curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $data = json_decode( curl_exec( $ch ), true );
        curl_close( $ch );
        return $data;
    }

    function post($appel_api, $data) {
        $ch = curl_init($this->url_api.$appel_api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch), true );
        curl_close($ch);
        return($data);
    }

}
