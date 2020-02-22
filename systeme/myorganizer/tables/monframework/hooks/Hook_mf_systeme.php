<?php declare(strict_types=1);

class Hook_mf_systeme
{

    public static function initialisation()
    {
        // Script qui s'exécute à chaque appel de dblayer_light
        // script ici
    }

    /*
     * Fonction qui s'exécute toutes les DELAI_EXECUTION_WORKER secondes si le site est en charge.
     */
    public static function worker(int $num_passe)
    {
        // script ici
    }

    public static function controle_acces_donnees(string $code, int $valeur): bool
    {
        // règles à géfinir ...
        switch ($code) {
            case MF_USER__ID:
                $Code_user = $valeur;
                return true;
            case MF_TASK__ID:
                $Code_task = $valeur;
                return true;
            case MF_LABEL__ID:
                $Code_label = $valeur;
                return true;
            default:
                return true;
        }
    }

    public static function autoriser_connexion(int $Code_user): bool
    {
        // script ici ...
        return true;
    }

    public static function script_connexion(int $Code_user)
    {
        // script ici ...
    }

    public static function script_deconnexion(int $Code_user)
    {
        // script ici ...
    }

    /*
     * Si un email via google ou fb est inexistant, alors il est possible de créer un compte
     * $type : 1=Google, 2=Facebook
     */
    public static function script_inscription_via_compte_oauth2(string $email, string $id, int $type)
    {
        // script ici ...
    }

    /*
     * Règles d'accès aux fichiers
     */
    public static function est_fichier_public(string $n)
    {
        // Si vrai, tout le monde a accès au fichier, connecté ou non. $n est le nom du fichier
        return false;
    }

    public static function controle_acces_fichier(string $n)
    {
        // Dans le cas ou le fichier n'est pas public, $n est le nom du fichier et permet de gérer des accès plus précis
        return true;
    }

    public static function controle_parametres_session(string $name_session, &$value)
    {
        switch ($name_session) {
            default:
                $value = null;
                break;
        }
    }

    public static function controle_acces_controller(string $nom_page): bool
    {
        // Définir la liste accès aux pages
        return true;
    }
}
