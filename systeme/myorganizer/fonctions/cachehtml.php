<?php

class Cachehtml
{

    private $dossier_cache;

    private $cache_cle;

    private $cat;

    private $compression_html;

    private function set_cache_cle($cle)
    {
        $this->cache_cle = $this->dossier_cache . md5((isset($_SERVER['HTTPS']) ? 1 : 0) . '_' . $_SERVER['SERVER_NAME'] . '_' . $_SERVER['REQUEST_URI'] . '_' . $this->cat . '_' . $cle);
        if (get_nom_page_courante() != 'mf_fichier.php') {
            $adresse_fichier_log = get_dossier_data('log_seo') . 'log_seo_' . substr(get_now(), 0, 10) . '.txt';
            $sep = "\t";
            $ln = PHP_EOL;
            $parametres = '';
            if (isset($_SESSION[PREFIXE_SESSION]['parametres'])) {
                foreach ($_SESSION[PREFIXE_SESSION]['parametres'] as $name => &$value) {
                    $parametres .= $sep . $name . '=' . $value;
                }
            }
            mf_file_append($adresse_fichier_log, get_now() . $sep . get_ip() . $sep . substr(get_nom_page_courante(), 0, strlen(get_nom_page_courante()) - 4) . $sep . $_SERVER['REQUEST_URI'] . $sep . identification_log() . $sep . (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : '-') . $sep . $_SERVER['HTTP_USER_AGENT'] . $parametres . $ln);
        }
    }

    public function __construct($cat = '', $dossier_cache = '1') // Possibilité de catégoriser les pages ...
    {
        $this->dossier_cache = __DIR__ . '/../cache/';
        if (!file_exists($this->dossier_cache)) {
            mkdir($this->dossier_cache);
        }

        global $mf_get_HTTP_HOST;
        $this->dossier_cache .= "$mf_get_HTTP_HOST/";
        if (!file_exists($this->dossier_cache)) {
            mkdir($this->dossier_cache);
        }

        if (TABLE_INSTANCE != '') {
            $instance = 'inst_' . get_instance();
            $this->dossier_cache .= $instance . '/';
            if (!file_exists($this->dossier_cache)) {
                @mkdir($this->dossier_cache);
            }
        }
        $this->dossier_cache .= $dossier_cache . '/';
        if (!file_exists($this->dossier_cache)) {
            mkdir($this->dossier_cache);
        }
        $this->cat = $cat;
        $this->compression_html = false;
    }

    public function activer_compression_html()
    {
        if (MODE_PROD) {
            $this->compression_html = true;
        }
    }

    public function start($cle = '', $duree = DUREE_CACHE_MINUTES)
    {
        $this->set_cache_cle($cle);
        if (file_exists($this->cache_cle)) {
            if (time() - filemtime($this->cache_cle) < $duree * 60) {
                $r = readfile($this->cache_cle);
                if ($r !== false) {
                    return true;
                }
            }
        }
        ob_start();
        return false;
    }

    public function end()
    {
        $content = ob_get_clean();
        if ($this->compression_html) {
            // suppression des tabulations
            $content = str_replace("\t", ' ', $content);
            // réduction des doubles espaces
            $d1 = 0;
            $d2 = strlen($content);
            while ($d1 != $d2) {
                $d1 = $d2;
                $content = str_replace('  ', ' ', $content);
                $d2 = strlen($content);
            }
            // suppression des retours à la lignes inutiles
            $content = str_replace("\r\n", "\n", $content);
            $content = str_replace("\n ", "\n", $content);
            $d1 = 0;
            $d2 = strlen($content);
            while ($d1 != $d2) {
                $d1 = $d2;
                $content = str_replace("\n\n", "\n", $content);
                $d2 = strlen($content);
            }
            $content = str_replace("\n", PHP_EOL, $content);
        }
        if (MODE_PROD) {
            if (!test_action_formulaire()) {
                file_put_contents($this->cache_cle, $content);
            }
        } else {
            global $desactivation_actualisation_outils_developpeur;
            if (!isset($desactivation_actualisation_outils_developpeur) || !$desactivation_actualisation_outils_developpeur) {
                mise_a_jour_fichier_developpeur();
            }
        }
        echo $content;
    }

    public function clear() // sppression differée
    {
        $files = glob($this->dossier_cache . '*');
        $fichier_a_suppression = true;
        while ($fichier_a_suppression) {
            $fichier_a_suppression = false;
            foreach ($files as $file) {
                $r = unlink($file);
                if ($r === false) {
                    $fichier_a_suppression = true;
                }
            }
        }
        clearstatcache();
    }

    public function clear_current_page($cle = '')
    {
        $this->set_cache_cle($cle);
        if (file_exists($this->cache_cle)) {
            @unlink($this->cache_cle);
        }
        clearstatcache();
    }

    private function mef_logs(&$donnees)
    {
        $tableau = '<table class="table table-dark table-striped table-sm" style="font-size: small">';
        $tableau .= '<tr>';
        $tableau .= '<th scope="col">#</th>';
        $tableau .= '<th scope="col">Date-heure</th>';
        $tableau .= '<th scope="col">IP</th>';
        $tableau .= '<th scope="col">Page</th>';
        $tableau .= '<th scope="col">Adresse</th>';
        $tableau .= '<th scope="col">Utilisateur</th>';
        $tableau .= '<th scope="col">Langue</th>';
        $tableau .= '<th scope="col">User-agent</th>';
        $tableau .= '</tr>';
        $i = 1;
        foreach ($donnees as $ligne) {
            $tableau .= '<tr><th scope="row">' . $i . '</th>';
            foreach ($ligne as $value) {
                $tableau .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $tableau .= '</tr>';
            $i++;
        }
        $tableau .= '</table>';
        return $tableau;
    }

    public function get_log()
    {
        $donnees = [];
        $files = glob(get_dossier_data('log_seo') . '*');
        foreach ($files as $file) {
            $liste = explode(PHP_EOL, file_get_contents($file));
            foreach ($liste as &$value) {
                if ($value != '') {
                    $donnees[] = explode("\t", $value);
                }
            }
        }
        return $this->mef_logs($donnees);
    }

    public function get_statistiques()
    {
        $mf_robot = mf_get_value_session('mf_robot', true);
        $mf_utilisateurs_enregistres = mf_get_value_session('mf_utilisateurs_enregistres', true);
        $retour = '';
        $donnees_utilisateurs = [];
        $est_connecte = [];
        $files = glob(get_dossier_data('log_seo') . '*');
        foreach ($files as $file) {
            $liste = explode(PHP_EOL, file_get_contents($file));
            foreach ($liste as &$ligne) {
                if ($ligne != '') {
                    $value = explode("\t", $ligne);
                    if (isset($value[1])) {
                        $ip = $value[1];
                        $ua = $value[6];
                        if ($mf_robot || stristr($ua, 'bot') === false) {
                            $user = $value[4];
                            $cle = md5($ip);
                            if (!isset($est_connecte[$cle])) {
                                $est_connecte[$cle] = false;
                            }
                            if ($user != 'Non connecté') {
                                $est_connecte[$cle] = true;
                            }
                            $donnees_utilisateurs[$cle][] = $value;
                        }
                    }
                }
            }
        }
        if (!$mf_utilisateurs_enregistres) {
            foreach ($est_connecte as $key => $value) {
                if ($value) {
                    unset($donnees_utilisateurs[$key]);
                }
            }
        }
        $i = 1;
        foreach ($donnees_utilisateurs as $donnees_utilisateur) {
            $ip = $donnees_utilisateur[0][1];
            $retour .= '<h2>IP ' . $ip . ' : ' . count($donnees_utilisateur) . ' requête(s)</h2>';
            $retour .= $this->mef_logs($donnees_utilisateur);
            $i++;
        }
        return $retour;
    }
}
