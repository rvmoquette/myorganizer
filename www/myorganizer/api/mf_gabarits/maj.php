<?php

    include __DIR__ . '/../../../../systeme/myorganizer/api_espace_publique.php';

    $filename = "";
    $contenu = "";

    if ( isset($_POST["filename"]) ) $filename = $_POST["filename"];
    if ( isset($_POST["contenu"]) ) $contenu = $_POST["contenu"];

    $filename = __DIR__ . '/../../gabarits/' . $filename;

    if (!MODE_PROD)
    {
        $trans = array(
                "<!DOCTYPE html>" => "",
                "<html>" => "", "</html>" => "",
                "<head>" => "", "</head>" => "",
                "<body>" => "", "</body>" => ""
        );
        $contenu = strtr($contenu, $trans);
        file_put_contents($filename, $contenu);
        $cache = new Cachehtml();
        $cache->clear();
    }
