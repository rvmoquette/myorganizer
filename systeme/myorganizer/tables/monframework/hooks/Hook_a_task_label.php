<?php declare(strict_types=1);

class Hook_a_task_label
{

    public static function initialisation() // première instanciation
    {
        // ici le code
    }

    public static function actualisation() // à chaque instanciation
    {
        // ici le code
    }

    public static function pre_controller(bool &$a_task_label_Link, int &$Code_task, int &$Code_label, bool $add_function)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_ajouter(?int $Code_task = null, ?int $Code_label = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['a_task_label__AJOUTER'] = false;
        // actualisation uniquement pour l'affichage
        $mf_droits_defaut['a_task_label__CREER'] = false;
        // Mise à jour des droits
        // ici le code
    }

    public static function autorisation_ajout(bool $a_task_label_Link, int $Code_task, int $Code_label)
    {
        return true;
    }

    public static function data_controller(bool &$a_task_label_Link, int $Code_task, int $Code_label, bool $add_function)
    {
        // ici le code
    }

    public static function ajouter(int $Code_task, int $Code_label)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_modifier(?int $Code_task = null, ?int $Code_label = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['a_task_label__MODIFIER'] = false;
        $mf_droits_defaut['api_modifier__a_task_label_Link'] = false;
        // Mise à jour des droits
        // ici le code
        $db = new DB();
        $task = $db -> task() -> mf_get($Code_task);
        if ($task[MF_TASK_CODE_USER] == get_user_courant(MF_USER__ID) || is_admin()) {
            $mf_droits_defaut['api_modifier__a_task_label_Link'] = true;
        }
    }

    public static function autorisation_modification(int $Code_task, int $Code_label, bool $a_task_label_Link__new)
    {
        return true;
    }

    public static function data_controller__a_task_label_Link(bool $old, bool &$new, int $Code_task, int $Code_label)
    {
        // ici le code
    }

    /*
     * modifier : $Code_..., permettent de se référer à la données modifiée
     * les autres paramètres booléens ($modif...) permettent d'identifier les champs qui ont été modifiés
     */
    public static function modifier(int $Code_task, int $Code_label, bool $bool__a_task_label_Link)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_supprimer(?int $Code_task = null, ?int $Code_label = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['a_task_label__SUPPRIMER'] = false;
        // Mise à jour des droits
        // Ici le code
    }

    public static function autorisation_suppression(int $Code_task, int $Code_label)
    {
        return true;
    }

    public static function supprimer(array $copie__liste_a_task_label)
    {
        // ici le code
    }

    public static function completion(array &$donnees, int $recursive_level)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_task']
         * $donnees['Code_label']
         * $donnees['a_task_label_Link']
         */
        // ici le code
    }

    // API callbacks
    // -------------------
    public static function callback_post(int $Code_task, int $Code_label)
    {
        return null;
    }

    public static function callback_put(int $Code_task, int $Code_label)
    {
        return null;
    }
}
