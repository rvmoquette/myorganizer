<?php

class Mf_Cache_systeme
{

    private $dossier_cache;

    function __construct()
    {
        $this->dossier_cache = __DIR__ . '/cache_systeme/';
        if (! file_exists($this->dossier_cache)) {
            mkdir($this->dossier_cache);
        }

        global $mf_get_HTTP_HOST;
        $this->dossier_cache .= "$mf_get_HTTP_HOST/";
        if (! file_exists($this->dossier_cache)) {
            mkdir($this->dossier_cache);
        }

        if (TABLE_INSTANCE != '') {
            $instance = 'inst_' . get_instance();
            $this->dossier_cache .= $instance . '/';
            if (! file_exists($this->dossier_cache)) {
                mkdir($this->dossier_cache);
            }
        }
    }

    function read($cle)
    {
        $filename = $this->dossier_cache . md5('' . $cle) . '';
        if (file_exists($filename)) {
            return unserialize(file_get_contents($filename));
        }
        return false;
    }

    function write($cle, $variable)
    {
        $filename = $this->dossier_cache . md5('' . $cle) . '';
        file_put_contents($filename, serialize($variable));
    }

    function clear()
    {
        $files = glob($this->dossier_cache . '*');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
