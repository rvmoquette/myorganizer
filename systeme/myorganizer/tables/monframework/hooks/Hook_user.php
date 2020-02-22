<?php declare(strict_types=1);

class Hook_user
{

    public static function initialisation() // première instanciation
    {
        // ici le code
    }

    public static function actualisation() // à chaque instanciation
    {
        // ici le code
    }

    public static function pre_controller(string &$user_Login, string &$user_Password, string &$user_Email, ?int $Code_user = null)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_ajouter()
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['user__AJOUTER'] = false;
        // actualisation uniquement pour l'affichage
        $mf_droits_defaut['user__CREER'] = false;
        // Mise à jour des droits
        // ici le code
    }

    public static function autorisation_ajout(string $user_Login, string $user_Password, string $user_Email)
    {
        return true;
    }

    public static function data_controller(string &$user_Login, string &$user_Password, string &$user_Email, ?int $Code_user = null)
    {
        // ici le code
    }

    public static function calcul_signature(string $user_Login, string $user_Email): string
    {
        return md5("$user_Login-$user_Email");
    }

    public static function calcul_cle_unique(string $user_Login, string $user_Email): string
    {
        // La méthode POST de l'API REST utilise cette fonction pour en déduire l'unicité de la données. Dans le cas contraire, la données est alors mise à jour
        // Attention au risque de collision
        return sha1("$user_Login-$user_Email");
    }

    public static function ajouter(int $Code_user)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_modifier(?int $Code_user = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['user__MODIFIER'] = false;
        $mf_droits_defaut['user__MODIFIER_PWD'] = false;
        $mf_droits_defaut['api_modifier__user_Login'] = false;
        $mf_droits_defaut['api_modifier__user_Password'] = false;
        $mf_droits_defaut['api_modifier__user_Email'] = false;
        // Mise à jour des droits
        // ici le code
    }

    public static function autorisation_modification(int $Code_user, string $user_Login__new, string $user_Password__new, string $user_Email__new)
    {
        return true;
    }

    public static function data_controller__user_Login(string $old, string &$new, int $Code_user)
    {
        // ici le code
    }

    public static function data_controller__user_Email(string $old, string &$new, int $Code_user)
    {
        // ici le code
    }

    /*
     * modifier : $Code_user permet de se référer à la données modifiée
     * les autres paramètres booléens ($modif...) permettent d'identifier les champs qui ont été modifiés
     */
    public static function modifier(int $Code_user, bool $bool__user_Login, bool $bool__user_Password, bool $bool__user_Email)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_supprimer(?int $Code_user = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['user__SUPPRIMER'] = false;
        // Mise à jour des droits
        // Ici le code
        if ($Code_user != 0 && $mf_droits_defaut['user__SUPPRIMER']) {
            $table_task = new task();
            $mf_droits_defaut['user__SUPPRIMER'] = $table_task->mfi_compter(['Code_user' => $Code_user]) == 0;
        }
    }

    public static function autorisation_suppression(int $Code_user)
    {
        return true;
    }

    public static function supprimer(array $copie__user)
    {
        // ici le code
    }

    public static function supprimer_2(array $copie__liste_user)
    {
        foreach ($copie__liste_user as &$copie__user) {
            self::supprimer($copie__user);
        }
        unset($copie__user);
    }

    public static function est_a_jour(array &$donnees)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_user']
         * $donnees['user_Login']
         * $donnees['user_Password']
         * $donnees['user_Email']
         */
        return true;
    }

    public static function mettre_a_jour(array $liste_user)
    {
        // ici le code
    }

    public static function completion(array &$donnees, int $recursive_level)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_user']
         * $donnees['user_Login']
         * $donnees['user_Password']
         * $donnees['user_Email']
         */
        // ici le code
    }

    // API callbacks
    // -------------------
    public static function callback_post(int $Code_user)
    {
        return null;
    }

    public static function callback_put(int $Code_user)
    {
        return null;
    }
}
