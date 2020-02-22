<?php declare(strict_types=1);

class Hook_a_user_task
{

    public static function initialisation() // première instanciation
    {
        // ici le code
    }

    public static function actualisation() // à chaque instanciation
    {
        // ici le code
    }

    public static function pre_controller(bool &$a_user_task_Link, int &$Code_user, int &$Code_task, bool $add_function)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_ajouter(?int $Code_user = null, ?int $Code_task = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['a_user_task__AJOUTER'] = false;
        // actualisation uniquement pour l'affichage
        $mf_droits_defaut['a_user_task__CREER'] = false;
        // Mise à jour des droits
        // ici le code
    }

    public static function autorisation_ajout(bool $a_user_task_Link, int $Code_user, int $Code_task)
    {
        return true;
    }

    public static function data_controller(bool &$a_user_task_Link, int $Code_user, int $Code_task, bool $add_function)
    {
        // ici le code
    }

    public static function ajouter(int $Code_user, int $Code_task)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_modifier(?int $Code_user = null, ?int $Code_task = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['a_user_task__MODIFIER'] = false;
        $mf_droits_defaut['api_modifier__a_user_task_Link'] = false;
        // Mise à jour des droits
        // ici le code
    }

    public static function autorisation_modification(int $Code_user, int $Code_task, bool $a_user_task_Link__new)
    {
        return true;
    }

    public static function data_controller__a_user_task_Link(bool $old, bool &$new, int $Code_user, int $Code_task)
    {
        // ici le code
    }

    /*
     * modifier : $Code_..., permettent de se référer à la données modifiée
     * les autres paramètres booléens ($modif...) permettent d'identifier les champs qui ont été modifiés
     */
    public static function modifier(int $Code_user, int $Code_task, bool $bool__a_user_task_Link)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_supprimer(?int $Code_user = null, ?int $Code_task = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['a_user_task__SUPPRIMER'] = false;
        // Mise à jour des droits
        // Ici le code
    }

    public static function autorisation_suppression(int $Code_user, int $Code_task)
    {
        return true;
    }

    public static function supprimer(array $copie__liste_a_user_task)
    {
        // ici le code
    }

    public static function completion(array &$donnees, int $recursive_level)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_user']
         * $donnees['Code_task']
         * $donnees['a_user_task_Link']
         */
        // ici le code
    }

    // API callbacks
    // -------------------
    public static function callback_post(int $Code_user, int $Code_task)
    {
        return null;
    }

    public static function callback_put(int $Code_user, int $Code_task)
    {
        return null;
    }
}
