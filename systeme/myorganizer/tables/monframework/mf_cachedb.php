<?php declare(strict_types=1);

class Mf_Cachedb
{

    private $dossier_cache;

    private $dossier_lock;

    private $name;

    private $microtime;

    public static $nb_lectures_disque = 0;

    public function __construct($name)
    {
        $this->dossier_cache = __DIR__ . '/cache/';
        if (!file_exists($this->dossier_cache)) {
            @mkdir($this->dossier_cache);
        }

        global $mf_get_HTTP_HOST;
        $this->dossier_cache .= "$mf_get_HTTP_HOST/";
        if (!file_exists($this->dossier_cache)) {
            mkdir($this->dossier_cache);
        }

        if (TABLE_INSTANCE != '') {
            if ($name != TABLE_INSTANCE && array_search($name, LISTE_TABLES_GLOBALES) === false) {
                $instance = 'inst_' . get_instance();
                $this->dossier_cache .= $instance . '/';
                if (!file_exists($this->dossier_cache)) {
                    @mkdir($this->dossier_cache);
                }
            }
        }
        $this->dossier_lock = $this->dossier_cache;
        $this->dossier_cache .= $name . '/';
        if (!file_exists($this->dossier_cache)) {
            @mkdir($this->dossier_cache);
        }
        $this->dossier_lock .= $name . '__lock/';
        if (!file_exists($this->dossier_lock)) {
            @mkdir($this->dossier_lock);
        }
        $this->name = $name;
        $this->microtime = $this->dossier_cache . 'microtime';
    }

    private function initialisation_cache()
    {
        if (!file_exists($this->microtime)) {
            $this->clear();
        }
    }

    public function read($cle)
    {
        global $mf_cache_volatil;
        if ($mf_cache_volatil->is_set($this->name, $cle)) {
            return $mf_cache_volatil->get($this->name, $cle);
        } else {
            $this->initialisation_cache();
            $r = @file_get_contents($this->microtime);
            if ($r !== false) {
                $microtime = unserialize($r);
                $filename = $this->dossier_cache . md5($cle);
                if (file_exists($filename)) {
                    $r = @file_get_contents($filename);
                    if ($r !== false) {
                        $r = unserialize($r);
                        if ($r['u'] > $microtime) {
                            self::$nb_lectures_disque++;
                            $mf_cache_volatil->set($this->name, $cle, $r['v']);
                            return $mf_cache_volatil->get($this->name, $cle);
                        }
                    }
                }
            }
        }
        return false;
    }

    public function write($cle, $variable)
    {
        if (!MODE_PROD) {
            if (strlen(serialize($variable)) > 16 * 1024 * 1024) {
                global $mf_last_query;
                $sql_Emplacement = '';
                $debug = debug_backtrace();
                foreach ($debug as $t) {
                    if ($sql_Emplacement != '') {
                        $sql_Emplacement = PHP_EOL . '                      ' . $sql_Emplacement;
                    }
                    $sql_Emplacement = (isset($t['file']) ? $t['file'] : '-') . ' # ' . $t['function'] . ' (Ligne ' . (isset($t['line']) ? $t['line'] : '-') . ')' . $sql_Emplacement;
                }

                $txt = '';
                $txt .= 'Date                : ' . get_now() . PHP_EOL;
                $txt .= 'Poids de la réponse : ' . format_nombre(strlen(serialize($variable)), true, 0) . ' octets' . PHP_EOL;
                $txt .= 'Requête             : ' . $mf_last_query . PHP_EOL;
                $txt .= 'Emplacement         : ' . $sql_Emplacement . PHP_EOL;

                echo '<html lang="fr"><head><title>Bah, qu\'est ce qui se passe ?</title></head><body style="background-color: #000000; color: #ffd400; padding: 10px; font-family: monospace; font-size: 16px;"><h1>Requête volumineuse</h1>' . str_replace('  ', '&nbsp; ', str_replace('  ', '&nbsp; ', str_replace(PHP_EOL, '<br>', $txt))) . '</body></html>';
                exit();
            }
        }
        global $mf_cache_volatil;
        $filename = $this->dossier_cache . md5($cle);
        file_put_contents($filename, serialize([
            'u' => microtime(true),
            'v' => $variable
        ]));
        $mf_cache_volatil->set($this->name, $cle, $variable);
    }

    public function clear()
    {
        global $mf_cache_volatil;
        file_put_contents($this->microtime, serialize(microtime(true) + 0.001));
        $mf_cache_volatil->clear($this->name);
    }

    public function add_lock(string $id)
    {
        $filename = $this->dossier_lock . $id;
        $ok = false;
        while (!$ok) {
            if (file_exists($filename)) {
                $r = @file_get_contents($filename);
                if ($r !== false) {
                    $r = unserialize($r);
                    if (!$r['l']) {
                        $ok = true;
                    } elseif ($r['u'] + 120 < microtime(true)) {
                        $ok = true;
                    }
                }
            } else {
                $ok = true;
            }
            if ($ok) {
                $ok = (false !== file_put_contents($filename, serialize([
                        'l' => true,
                        'u' => microtime(true)
                    ])));
            }
            if (!$ok) {
                usleep(50000); // arret pendant 50ms
            }
        }
    }

    public function release_lock(string $id)
    {
        file_put_contents($this->dossier_lock . $id, serialize([
            'l' => false
        ]));
    }
}
