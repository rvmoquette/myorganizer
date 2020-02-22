<?php declare(strict_types=1);

class Hook_label
{

    public static function initialisation() // première instanciation
    {
        // ici le code
    }

    public static function actualisation() // à chaque instanciation
    {
        // ici le code
    }

    public static function pre_controller(string &$label_Name, ?int $Code_label = null)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_ajouter()
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['label__AJOUTER'] = false;
        // actualisation uniquement pour l'affichage
        $mf_droits_defaut['label__CREER'] = false;
        // Mise à jour des droits
        // ici le code
        if (is_admin()) {
            $mf_droits_defaut['label__AJOUTER'] = true;
        }
    }

    public static function autorisation_ajout(string $label_Name)
    {
        return true;
    }

    public static function data_controller(string &$label_Name, ?int $Code_label = null)
    {
        // ici le code
    }

    public static function calcul_signature(string $label_Name): string
    {
        return md5("$label_Name");
    }

    public static function calcul_cle_unique(string $label_Name): string
    {
        // La méthode POST de l'API REST utilise cette fonction pour en déduire l'unicité de la données. Dans le cas contraire, la données est alors mise à jour
        // Attention au risque de collision
        return sha1("$label_Name");
    }

    public static function ajouter(int $Code_label)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_modifier(?int $Code_label = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['label__MODIFIER'] = false;
        $mf_droits_defaut['api_modifier__label_Name'] = false;
        // Mise à jour des droits
        // ici le code
        if (is_admin()) {
            $mf_droits_defaut['api_modifier__label_Name'] = true;
        }
    }

    public static function autorisation_modification(int $Code_label, string $label_Name__new)
    {
        return true;
    }

    public static function data_controller__label_Name(string $old, string &$new, int $Code_label)
    {
        // ici le code
    }

    /*
     * modifier : $Code_label permet de se référer à la données modifiée
     * les autres paramètres booléens ($modif...) permettent d'identifier les champs qui ont été modifiés
     */
    public static function modifier(int $Code_label, bool $bool__label_Name)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_supprimer(?int $Code_label = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['label__SUPPRIMER'] = false;
        // Mise à jour des droits
        // Ici le code
        if (is_admin()) {
            $mf_droits_defaut['label__SUPPRIMER'] = true;
        }
    }

    public static function autorisation_suppression(int $Code_label)
    {
        return true;
    }

    public static function supprimer(array $copie__label)
    {
        // ici le code
    }

    public static function supprimer_2(array $copie__liste_label)
    {
        foreach ($copie__liste_label as &$copie__label) {
            self::supprimer($copie__label);
        }
        unset($copie__label);
    }

    public static function est_a_jour(array &$donnees)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_label']
         * $donnees['label_Name']
         */
        return true;
    }

    public static function mettre_a_jour(array $liste_label)
    {
        // ici le code
    }

    public static function completion(array &$donnees, int $recursive_level)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_label']
         * $donnees['label_Name']
         */
        // ici le code
    }

    // API callbacks
    // -------------------
    public static function callback_post(int $Code_label)
    {
        return null;
    }

    public static function callback_put(int $Code_label)
    {
        return null;
    }
}
