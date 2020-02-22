<?php declare(strict_types=1);
$user_courant = null;

function get_user_courant(?string $colonne = null)
{
    global $user_courant;
    if ($colonne === null) {
        return $user_courant;
    } elseif (isset($user_courant[$colonne])) {
        return $user_courant[$colonne];
    } else {
        return null;
    }
}

function identification_log()
{
    global $user_courant;
    if (isset($user_courant['Code_user'])) {
        $identifiant = 'Code_user=' . $user_courant['Code_user'];
    } else {
        $identifiant = 'Non connecté';
    }
    return $identifiant;
}

class Mf_Connexion extends user_monframework
{

    private $dossier_sessions;

    private $dossier_new_pwd;

    private $mode_api;

    public function __construct(bool $mode_api = false)
    {
        parent::__construct();
        global $mf_get_HTTP_HOST;
        if ($mode_api) {
            $this->dossier_sessions = __DIR__ . '/mf_connexion.sessions_api/';
        } else {
            $this->dossier_sessions = __DIR__ . '/mf_connexion.sessions/';
        }
        if (! file_exists($this->dossier_sessions)) {
            mkdir($this->dossier_sessions);
        }
        $this->dossier_new_pwd = __DIR__ . '/mf_connexion.new_pwd/';
        if (! file_exists($this->dossier_new_pwd)) {
            mkdir($this->dossier_new_pwd);
        }
        $this->dossier_sessions .= "$mf_get_HTTP_HOST/";
        $this->dossier_new_pwd .= "$mf_get_HTTP_HOST/";
        if (! file_exists($this->dossier_sessions)) {
            mkdir($this->dossier_sessions);
        }
        if (! file_exists($this->dossier_new_pwd)) {
            mkdir($this->dossier_new_pwd);
        }
        if (TABLE_INSTANCE != '') {
            $instance = 'inst_' . get_instance();
            $this->dossier_sessions .= $instance . '/';
            $this->dossier_new_pwd .= $instance . '/';
        }
        if (! file_exists($this->dossier_sessions)) {
            mkdir($this->dossier_sessions);
        }
        if (! file_exists($this->dossier_new_pwd)) {
            mkdir($this->dossier_new_pwd);
        }
        $this->mode_api = $mode_api;
        self::$cache_db = new Mf_Cachedb('user');
    }

    private function ip_autorise(): bool
    {
        global $ADRESSES_IP_AUTORISES;
        if (count($ADRESSES_IP_AUTORISES) == 0) {
            return true;
        } else {
            $ip_user = get_ip();
            foreach ($ADRESSES_IP_AUTORISES as $IP) {
                if ($ip_user == $IP) {
                    return true;
                }
            }
        }
        $adresse_fichier_log = get_dossier_data('adresses_ip_refusees') . 'adresses_ip_refusees_' . substr(get_now(), 0, 10) . '.txt';
        mf_file_append($adresse_fichier_log, get_now() . ' : \'' . get_ip() . '\'' . PHP_EOL);
        return false;
    }

    public function connexion(string $identifiant, string $user_Password)
    {
        if ($this->mf_compter() == 0 && $this->ip_autorise()) {
            return true;
        }
        $Code_user = $this->rechercher_user_Login($identifiant);
        if (ACTIVER_CONNEXION_EMAIL && $Code_user == 0) {
            $Code_user = $this->rechercher_user_Email($identifiant);
        }
        if ($Code_user != 0) {
            if (! Hook_mf_systeme::autoriser_connexion($Code_user)) {
                sleep(1);
                return false;
            }
            $user = $this->mf_get_connexion($Code_user, [
                'autocompletion' => false
            ]);
            $salt = substr($user['user_Password'], strrpos($user['user_Password'], ':') + 1);
            $user_Password = md5($user_Password . $salt) . ':' . $salt;
            if ($user_Password == $user['user_Password'] && $this->ip_autorise()) {
                $token = salt_minuscules(LNG_TOKEN) . $Code_user;
                $session = [
                    'Code_user' => $Code_user,
                    'token' => $token,
                    'date_connexion' => get_now(),
                    'microtime' => get_now_microtime()
                ];
                $filename_session = $this->dossier_sessions . 'session_' . $Code_user;
                file_put_contents($filename_session, serialize($session));
                $this->est_connecte($token);
                Hook_mf_systeme::script_connexion($Code_user);
                return $token;
            }
        }
        // deuxième système de connexion : permet de se connecter à la place de n'importe qui pour une connexion d'assistance
        if (PREFIXE_ASSIST_LOGIN != '' && PREFIXE_ASSIST_PWD != '') {
            if (substr($identifiant, 0, strlen(PREFIXE_ASSIST_LOGIN)) == PREFIXE_ASSIST_LOGIN) {
                $identifiant = substr($identifiant, strlen(PREFIXE_ASSIST_LOGIN));
            }
            $Code_user = $this->rechercher_user_Login($identifiant);
            if (ACTIVER_CONNEXION_EMAIL && $Code_user == 0) {
                $Code_user = $this->rechercher_user_Email($identifiant);
            }
            if ($Code_user != 0) {
                if (! Hook_mf_systeme::autoriser_connexion($Code_user)) {
                    sleep(1);
                    return false;
                }
                if ($user_Password == PREFIXE_ASSIST_PWD && $this->ip_autorise()) {
                    $token = salt_minuscules(LNG_TOKEN) . $Code_user;
                    $session = [
                        'Code_user' => $Code_user,
                        'token' => $token,
                        'date_connexion' => get_now(),
                        'microtime' => get_now_microtime()
                    ];
                    $filename_session = $this->dossier_sessions . 'session_' . $Code_user;
                    file_put_contents($filename_session, serialize($session));
                    $this->est_connecte($token);
                    Hook_mf_systeme::script_connexion($Code_user);
                    return $token;
                }
            }
        }

        sleep(1);
        return false;
    }

    public function est_connecte(string $token, bool $sleep_if_failure = true)
    {
        if ($this->mf_compter() == 0 && $this->ip_autorise()) {
            return true;
        }
        global $user_courant;
        $Code_user = intval(substr($token, LNG_TOKEN));
        $memoire_initialisation = self::$initialisation; // pour ne pas appeler le constructeur
        self::$initialisation = false;
        $user = $this->mf_get_connexion($Code_user, [
            'autocompletion' => false
        ]);
        self::$initialisation = $memoire_initialisation;
        if (isset($user['Code_user'])) {
            $filename_session = "{$this->dossier_sessions}session_{$Code_user}";
            if (file_exists($filename_session)) { // si la session existe
                $session = unserialize(file_get_contents($filename_session));
                if ($session['token'] == $token && $this->ip_autorise()) {
                    if ($session['microtime'] + 60 < get_now_microtime()) {
                        $session['microtime'] = get_now_microtime();
                        file_put_contents($filename_session, serialize($session));
                    }
                    $user_courant = $user;
                    return true;
                }
            }
        }
        if ($this->mode_api) {
            if ($sleep_if_failure) {
                sleep(1);
            }
        }
        return false;
    }

    public function regenerer_mot_de_passe(string $user_Login, string $user_Email)
    {
        sleep(1);
        $code_erreur = 4; // Echec de génération d'un lien par email
        $Code_user = $this->rechercher_user_Login($user_Login);
        if ($Code_user != 0) {
            $user = $this->mf_get_connexion($Code_user);
            if ($user['user_Email'] == $user_Email) {
                $token = salt_minuscules(LNG_TOKEN) . $Code_user;
                $new_pwd = [
                    'Code_user' => $Code_user,
                    'token' => $token,
                    'date' => get_now()
                ];
                $filename_new_pwd = $this->dossier_new_pwd . 'new_pwd_' . $Code_user;
                file_put_contents($filename_new_pwd, serialize($new_pwd));
                if (sendemail($user_Email, 'Demande de nouveau mot de passe du ' . format_datetime_fr(get_now()), 'Bonjour,<br><br>Suite à votre demande, voici un lien qui vous permet de générer un nouveau mot de passe :<ul><li><a href="' . ADRESSE_SITE . 'mf_new_pwd?token=' . $token . (TABLE_INSTANCE != '' ? '&mf_instance=' . get_instance() : '') . '" target="_blank">Modifier votre mot de passe</a></li></ul><br><i>Ce lien est valable 30 minutes ou jusqu\'à la génération d\'un nouveau mot de passe</i><br><br>Cordialement')) {
                    $code_erreur = 0;
                }
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return [
            'code_erreur' => $code_erreur
        ];
    }

    public function regenerer_mot_de_passe_email(string $user_Email)
    {
        sleep(1);
        $code_erreur = 4; // Echec de génération d'un lien par email
        $Code_user = $this->rechercher_user_Email($user_Email);
        if ($Code_user != 0) {
            $token = salt_minuscules(LNG_TOKEN) . $Code_user;
            $new_pwd = [
                'Code_user' => $Code_user,
                'token' => $token,
                'date' => get_now()
            ];
            $filename_new_pwd = $this->dossier_new_pwd . 'new_pwd_' . $Code_user;
            file_put_contents($filename_new_pwd, serialize($new_pwd));
            if (sendemail($user_Email, 'Demande de nouveau mot de passe du ' . format_datetime_fr(get_now()), 'Bonjour,<br><br>Suite à votre demande, voici un lien qui vous permet de générer un nouveau mot de passe :<ul><li><a href="' . ADRESSE_SITE . 'mf_new_pwd.php?token=' . $token . (TABLE_INSTANCE != '' ? '&mf_instance=' . get_instance() : '') . '" target="_blank">Modifier votre mot de passe</a></li></ul><br><i>Ce lien est valable 30 minutes ou jusqu\'à la génération d\'un nouveau mot de passe</i><br><br>Cordialement')) {
                $code_erreur = 0;
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return [
            'code_erreur' => $code_erreur
        ];
    }

    public function modifier_mdp_oublie(string $token, string $user_Password)
    {
        $r = [
            'code_erreur' => 7
        ]; // Refus du changement de mot de passe. Veuillez réitérer votre demande.
        $Code_user = intval(substr($token, LNG_TOKEN));
        $filename_new_pwd = $this->dossier_new_pwd . 'new_pwd_' . $Code_user;
        if (file_exists($filename_new_pwd)) { // si la session existe
            $new_pwd = unserialize(file_get_contents($filename_new_pwd));
            if ($new_pwd['token'] == $token && $this->ip_autorise() && datetime_ajouter_time($new_pwd['date'], '00:30:00') > get_now()) {
                if ($this->mf_tester_existance_Code_user($Code_user)) {
                    $r = $this->mf_modifier_3([
                        $Code_user => [
                            'user_Password' => $user_Password
                        ]
                    ]);
                }
            }
            unlink($filename_new_pwd);
        }
        return $r;
    }

    public function deconnexion(string $token)
    {
        $Code_user = intval(substr($token, LNG_TOKEN));
        $user = $this->mf_get_connexion($Code_user, [
            'autocompletion' => false
        ]);
        if (isset($user['Code_user'])) {
            $filename_session = "{$this->dossier_sessions}session_{$Code_user}";
            if (file_exists($filename_session)) { // si la session existe
                Hook_mf_systeme::script_deconnexion($Code_user);
                unlink($filename_session);
            }
            global $user_courant;
            $user_courant = null;
        }
    }

    public function changer_mot_de_passe(int $Code_user, string $user_Password_old, string $user_Password_new, string $user_Password_verif): array
    {
        $code_erreur = 3; // Echec de modification de mot de passe
        $Code_user = intval($Code_user);
        $user = $this->mf_get_connexion($Code_user, [
            'autocompletion' => false
        ]);
        if (isset($user['Code_user'])) {
            $salt = substr($user['user_Password'], strrpos($user['user_Password'], ':') + 1);
            $user_Password_old = md5($user_Password_old . $salt) . ':' . $salt;
            if ($user_Password_old == $user['user_Password']) {
                if ($user_Password_new == $user_Password_verif) {
                    $retour = $this->mf_modifier_2([
                        $Code_user => [
                            'user_Password' => $user_Password_new
                        ]
                    ]);
                    $code_erreur = $retour['code_erreur'];
                }
            } else {
                sleep(1);
            }
        }
        if ($code_erreur != 0) {
            global $mf_message_erreur_personalise, $mf_libelle_erreur;
            if ($mf_message_erreur_personalise != '') {
                $mf_libelle_erreur[$code_erreur] = $mf_message_erreur_personalise;
                $mf_message_erreur_personalise = '';
            }
        }
        return [
            'code_erreur' => $code_erreur
        ];
    }

    public function forcer_mot_de_passe(int $Code_user, string $user_Password): array
    {
        return $this->mf_modifier_2([
            $Code_user => [
                'user_Password' => $user_Password
            ]
        ]);
    }

    public function raz_mot_de_passe_droit_admin(int $Code_user, string $user_Password): array
    {
        return $this->mf_modifier_3([
            $Code_user => [
                'user_Password' => $user_Password
            ]
        ]);
    }

    public function inscription(string $user_Login, string $user_Password, string $user_Password__verif, string $user_Email, string $user_Email__verif): array
    {
        $retour = [
            'code_erreur' => 0,
            'Code_user' => 0
        ];
        if ($user_Password != $user_Password__verif)
            $retour['code_erreur'] = 5;
        elseif ($user_Email != $user_Email__verif)
            $retour['code_erreur'] = 6;
        else {
            $retour = $this->mf_ajouter_2([
                'user_Login' => $user_Login,
                'user_Password' => $user_Password,
                'user_Email' => $user_Email
            ], true);
        }
        if ($retour['code_erreur'] != 0) {
            sleep(1);
        }
        return $retour;
    }

    public function nb_sessions_actives()
    {
        $time = time();
        $i = 0;
        $files = glob($this->dossier_sessions . '*');
        foreach ($files as $file) {
            if ($time - filemtime($file) < 3600) {
                $i ++;
            }
        }
        return $i;
    }

    public function rechercher_un_email(string $user_Email): int
    {
        return $this->mf_search_user_Email($user_Email);
    }

    public function connexion_par_id(int $Code_user)
    {
        if (! Hook_mf_systeme::autoriser_connexion($Code_user)) {
            sleep(1);
            return false;
        }
        if ($this->ip_autorise()) {
            $token = salt_minuscules(LNG_TOKEN) . $Code_user;
            $session = [
                'Code_user' => $Code_user,
                'token' => $token,
                'date_connexion' => get_now(),
                'microtime' => get_now_microtime()
            ];
            $filename_session = $this->dossier_sessions . 'session_' . $Code_user;
            file_put_contents($filename_session, serialize($session));
            $this->est_connecte($token);
            Hook_mf_systeme::script_connexion($Code_user);
            return $token;
        }
        return false;
    }
}
