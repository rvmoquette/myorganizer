<?php declare(strict_types=1);

/*
    +------------------------------+
    |  NE PAS MODIFIER CE FICHIER  |
    +------------------------------+
*/

class DB
{

    private $user = null;
    private $task = null;
    private $label = null;
    private $a_task_label = null;
    private $a_user_task = null;

    public function __construct()
    {
    }

    /**
     * @return user
     */
    public function user(): user
    {
        if ($this->user == null) {
            $this->user = new user();
        }
        return $this->user;
    }

    /**
     * @return task
     */
    public function task(): task
    {
        if ($this->task == null) {
            $this->task = new task();
        }
        return $this->task;
    }

    /**
     * @return label
     */
    public function label(): label
    {
        if ($this->label == null) {
            $this->label = new label();
        }
        return $this->label;
    }

    /**
     * @return a_task_label
     */
    function a_task_label(): a_task_label
    {
        if ($this->a_task_label == null) {
            $this->a_task_label = new a_task_label();
        }
        return $this->a_task_label;
    }

    /**
     * @return a_user_task
     */
    function a_user_task(): a_user_task
    {
        if ($this->a_user_task == null) {
            $this->a_user_task = new a_user_task();
        }
        return $this->a_user_task;
    }


    static function mf_raz_instance()
    {
        user::mf_raz_instance();
        task::mf_raz_instance();
        label::mf_raz_instance();
        a_task_label::mf_raz_instance();
        a_user_task::mf_raz_instance();
    }

    public function mf_table($nom_table)
    {
        switch ($nom_table) {
            case 'user':
                return $this->user();
                break;
            case 'task':
                return $this->task();
                break;
            case 'label':
                return $this->label();
                break;
            case 'a_task_label':
                return $this->a_task_label();
                break;
            case 'a_user_task':
                return $this->a_user_task();
                break;
            default:
                return null;
        }
    }

}
