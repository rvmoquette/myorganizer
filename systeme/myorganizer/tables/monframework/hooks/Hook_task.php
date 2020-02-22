<?php declare(strict_types=1);

class Hook_task
{

    public static function initialisation() // première instanciation
    {
        // ici le code
    }

    public static function actualisation() // à chaque instanciation
    {
        // ici le code
    }

    public static function pre_controller(string &$task_Name, string &$task_Date_creation, string &$task_Description, ?int &$task_Workflow, int &$Code_user, ?int $Code_task = null)
    {
        // ici le code
        // No one can add a task for someone else.
        if ($Code_task == 0) {
            $Code_user = get_user_courant(MF_USER__ID);
            $task_Workflow = TASK_WORKFLOW_CREE;
            $task_Date_creation = get_now();
        }
    }

    public static function hook_actualiser_les_droits_ajouter(?int $Code_user = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['task__AJOUTER'] = false;
        // actualisation uniquement pour l'affichage
        $mf_droits_defaut['task__CREER'] = false;
        // Mise à jour des droits
        // ici le code
        // everobody can add a task
        if (is_connected()) {
            $mf_droits_defaut['task__AJOUTER'] = true;
        }
    }

    public static function autorisation_ajout(string $task_Name, string $task_Date_creation, string $task_Description, ?int $task_Workflow, int $Code_user)
    {
        return true;
    }

    public static function data_controller(string &$task_Name, string &$task_Date_creation, string &$task_Description, ?int &$task_Workflow, int &$Code_user, ?int $Code_task = null)
    {
        // ici le code
    }

    public static function calcul_signature(string $task_Name, string $task_Date_creation, string $task_Description, ?int $task_Workflow, int $Code_user): string
    {
        return md5("$task_Name-$task_Date_creation-$task_Description-$task_Workflow-$Code_user");
    }

    public static function calcul_cle_unique(string $task_Name, string $task_Date_creation, string $task_Description, ?int $task_Workflow, int $Code_user): string
    {
        // La méthode POST de l'API REST utilise cette fonction pour en déduire l'unicité de la données. Dans le cas contraire, la données est alors mise à jour
        // Attention au risque de collision
        return "$Code_user".sha1("$task_Name-$task_Date_creation-$task_Description-$task_Workflow");
    }

    public static function ajouter(int $Code_task)
    {
        // ici le code
        $db = new DB();
        $db -> a_user_task() -> mfi_ajouter_auto([
            'Code_task' => $Code_task
        ]);
        $db -> a_task_label() -> mfi_ajouter_auto([
            'Code_task' => $Code_task
        ]);
    }

    public static function hook_actualiser_les_droits_modifier(?int $Code_task = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['task__MODIFIER'] = false;
        $mf_droits_defaut['api_modifier__task_Name'] = false;
        $mf_droits_defaut['api_modifier__task_Date_creation'] = false;
        $mf_droits_defaut['api_modifier__task_Description'] = false;
        $mf_droits_defaut['api_modifier__task_Workflow'] = false;
        $mf_droits_defaut['api_modifier_ref__task__Code_user'] = false;
        // Mise à jour des droits
        // ici le code
        $db = new DB();
        $task = $db -> task() -> mf_get($Code_task);
        if ($task[MF_TASK_CODE_USER] == get_user_courant(MF_USER__ID) || is_admin()) {
            if ($task[MF_TASK_WORKFLOW] == TASK_WORKFLOW_CREE) {
                $mf_droits_defaut['api_modifier__task_Description'] = true;
                $mf_droits_defaut['api_modifier__task_Name'] = true;
            }
            $mf_droits_defaut['api_modifier__task_Workflow'] = true;
        }
    }

    public static function autorisation_modification(int $Code_task, string $task_Name__new, string $task_Date_creation__new, string $task_Description__new, ?int $task_Workflow__new, int $Code_user__new)
    {
        return true;
    }

    /**
     * A partir de la valeur $task_Workflow, liste des états autorisés
     * Cette opéraion est effectuée en ammont.
     * @param int $task_Workflow
     * @return array
     */
    public static function workflow__task_Workflow(int $task_Workflow): array
    {
        // Par défaut, l'ensemble des choix sont permi
        global $lang_standard;
        return lister_cles($lang_standard['task_Workflow_']);
    }

    public static function data_controller__task_Name(string $old, string &$new, int $Code_task)
    {
        // ici le code
    }

    public static function data_controller__task_Date_creation(string $old, string &$new, int $Code_task)
    {
        // ici le code
    }

    public static function data_controller__task_Description(string $old, string &$new, int $Code_task)
    {
        // ici le code
    }

    public static function data_controller__task_Workflow(?int $old, ?int &$new, int $Code_task)
    {
        // ici le code
    }

    public static function data_controller__Code_user(int $old, int &$new, int $Code_task)
    {
        // ici le code
    }

    /*
     * modifier : $Code_task permet de se référer à la données modifiée
     * les autres paramètres booléens ($modif...) permettent d'identifier les champs qui ont été modifiés
     */
    public static function modifier(int $Code_task, bool $bool__task_Name, bool $bool__task_Date_creation, bool $bool__task_Description, bool $bool__task_Workflow, bool $bool__Code_user)
    {
        // ici le code
    }

    public static function hook_actualiser_les_droits_supprimer(?int $Code_task = null)
    {
        global $mf_droits_defaut;
        // Initialisation des droits
        $mf_droits_defaut['task__SUPPRIMER'] = false;
        // Mise à jour des droits
        // Ici le code
        $db = new DB();
        $task = $db -> task() -> mf_get($Code_task);
        if ($task[MF_TASK_CODE_USER]==get_user_courant(MF_USER__ID) && $task[MF_TASK_WORKFLOW]==TASK_WORKFLOW_CREE || is_admin()) {
            $mf_droits_defaut['task__SUPPRIMER'] = true;
        }
    }

    public static function autorisation_suppression(int $Code_task)
    {
        return true;
    }

    public static function supprimer(array $copie__task)
    {
        // ici le code
    }

    public static function supprimer_2(array $copie__liste_task)
    {
        foreach ($copie__liste_task as &$copie__task) {
            self::supprimer($copie__task);
        }
        unset($copie__task);
    }

    public static function est_a_jour(array &$donnees)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_task']
         * $donnees['Code_user']
         * $donnees['task_Name']
         * $donnees['task_Date_creation']
         * $donnees['task_Description']
         * $donnees['task_Workflow']
         */
        return true;
    }

    public static function mettre_a_jour(array $liste_task)
    {
        // ici le code
    }

    public static function completion(array &$donnees, int $recursive_level)
    {
        /*
         * Balises disponibles :
         * $donnees['Code_task']
         * $donnees['Code_user']
         * $donnees['task_Name']
         * $donnees['task_Date_creation']
         * $donnees['task_Description']
         * $donnees['task_Workflow']
         */
        // ici le code
    }

    // API callbacks
    // -------------------
    public static function callback_post(int $Code_task)
    {
        return null;
    }

    public static function callback_put(int $Code_task)
    {
        return null;
    }
}
