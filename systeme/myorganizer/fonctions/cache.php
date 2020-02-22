<?php

class Cache
{

    private $dossier_cache;

    private $dossier_cache_volatil;

    private $microtime_1;

    private $microtime_2;

    private static $nb_lectures_disque;

    private $execution_time = [];

    function __construct($dossier_cache = '100', $sous_dossier = 'all') // Possibilité de catégoriser les pages ...
    {
        global $mf_get_HTTP_HOST;
        // Emplacement du cache
        $this->dossier_cache = __DIR__ . '/../cache/';
        if (! file_exists($this->dossier_cache)) {
            @mkdir($this->dossier_cache);
        }
        $this->dossier_cache .= "$mf_get_HTTP_HOST/";
        if (! file_exists($this->dossier_cache)) {
            @mkdir($this->dossier_cache);
        }
        if (TABLE_INSTANCE != '') {
            $instance = 'inst_' . get_instance();
            $this->dossier_cache .= "$instance/";
            if (! file_exists($this->dossier_cache)) {
                @mkdir($this->dossier_cache);
            }
        }
        // Premier niveau de cache
        $this->dossier_cache .= "$dossier_cache/";
        if (! file_exists($this->dossier_cache)) {
            @mkdir($this->dossier_cache);
        }
        $this->microtime_1 = $this->dossier_cache . 'microtime';
        $this->dossier_cache_volatil = 'mf_cache_' . md5($this->dossier_cache);
        $this->sous_dossier = $sous_dossier;
        // Deuxième niveau de cache
        $this->dossier_cache .= $sous_dossier . '/';
        if (! file_exists($this->dossier_cache)) {
            @mkdir($this->dossier_cache);
        }
        $this->microtime_2 = $this->dossier_cache . 'microtime';
    }

    private function initialisation_cache()
    {
        if (! file_exists($this->microtime_1)) {
            $this->clear();
        }
        if (! file_exists($this->microtime_2)) {
            $this->clear_local();
        }
    }

    function read($cle, $duree = DUREE_CACHE_MINUTES)
    {
        $cle = md5($cle);
        $this->initialisation_cache();
        $r = @file_get_contents($this->microtime_1);
        if ($r !== false) {
            $microtime_1 = unserialize($r);
            $r = @file_get_contents($this->microtime_2);
            if ($r !== false) {
                $microtime_2 = unserialize($r);
                $microtime = max([
                    $microtime_1,
                    $microtime_2
                ]);
                $filename = $this->dossier_cache . $cle;
                if (file_exists($filename)) {
                    $r = @file_get_contents($filename);
                    if ($r !== false) {
                        $r = unserialize($r);
                        if ($r['u'] > $microtime) {
                            self::$nb_lectures_disque ++;
                            return $r['v'];
                        }
                    }
                }
            }
        }
        $this->execution_time[$cle] = microtime(true);
        return false;
    }

    function write(string $cle, $variable)
    {
        $cle = md5($cle);
        if (isset($this->execution_time[$cle])) {
            $start = $this->execution_time[$cle];
            $stop = microtime(true);
            $execution_time = $stop - $start;
        } else {
            $execution_time = 0;
        }
        microtime(true);
        $filename = $this->dossier_cache . $cle;
        file_put_contents($filename, serialize([
            'u' => microtime(true),
            'v' => $variable,
            't' => $execution_time
        ]));
    }

    function clear()
    {
        file_put_contents($this->microtime_1, serialize(microtime(true) + 0.001));
    }

    function clear_local()
    {
        file_put_contents($this->microtime_2, serialize(microtime(true) + 0.001));
    }

    function get_adress($cle)
    {
        $filename = $this->dossier_cache . md5($cle);
        return $filename;
    }
}
