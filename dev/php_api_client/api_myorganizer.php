<?php declare(strict_types=1);

class Api_myorganizer {

    private $url_api;
    private $mf_token='';
    private $mf_id='';
    private $mf_num_error=0;
    private $mf_errot_lib='';
    private $mf_connector_token='';
    private $mf_instance=0;

    public function __construct($url_api, $mf_connector_token='', $mf_instance=0)
    {
        $this->url_api = $url_api;
        $this->mf_connector_token = $mf_connector_token;
        $this->mf_instance = $mf_instance;
    }

    public function get($appel_api) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $this->url_api.$appel_api );
        curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $r = json_decode(curl_exec($ch), true);
        $this->mf_num_error = (isset($r['error']['number']) ? $r['error']['number'] : 0);
        curl_close( $ch );
        return $r['data'];
    }

    public function post($appel_api, $data) {
        $ch = curl_init($this->url_api.$appel_api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $r = json_decode(curl_exec($ch), true);
        $this->mf_num_error = $r['error']['number'];
        curl_close($ch);
        return $r['data'];
    }

    public function put($appel_api, $data) {
        $ch = curl_init($this->url_api.$appel_api);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $r = json_decode(curl_exec($ch), true);
        $this->mf_num_error = $r['error']['number'];
        curl_close($ch);
        return $r['data'];
    }

    public function delete($appel_api) {
        $ch = curl_init($this->url_api.$appel_api);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $r = json_decode(curl_exec($ch), true );
        curl_close($ch);
        $this->mf_num_error = $r['error']['number'];
        return $r['data'];
    }

    public function connexion($mf_login, $mf_pwd) {
        $r = $this->post('mf_connexion', ['mf_login'=>$mf_login, 'mf_pwd'=>$mf_pwd]);
        if ( $r['error']['number']==0 ) {
            $this->mf_token = $r['data']['mf_token'];
            $this->mf_id = $r['data']['id'];
            return $this->mf_id;
        }
        else
        {
            $this->mf_num_error = $r['error']['number'];
        }
        return false;
    }

    public function get_id_connexion() {
        return $this->mf_id;
    }

    public function get_num_error() {
        return $this->mf_num_error;
    }

    // +------+
    // | user |
    // +------+

    public function user__get($Code_user) {
        return $this->get('user/'.$Code_user.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function user__get_all() {
        $requete = '';
        return $this->get($requete . 'user?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function user__add($user_Login, $user_Password, $user_Email) {
        $data = [
            'user_Login' => $user_Login,
            'user_Password' => $user_Password,
            'user_Email' => $user_Email,
        ];
        return $this->post('user?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function user__edit($Code_user, $user_Login, $user_Password, $user_Email) {
        $data = [
            'user_Login' => $user_Login,
            'user_Password' => $user_Password,
            'user_Email' => $user_Email,
        ];
        return $this->put('user/'.$Code_user.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function user__edit__user_Login($Code_user, $user_Login) {
        $data = ['user_Login' => $user_Login ];
        return $this->put('user/'.$Code_user.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function user__edit__user_Password($Code_user, $user_Password) {
        $data = ['user_Password' => $user_Password ];
        return $this->put('user/'.$Code_user.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function user__edit__user_Email($Code_user, $user_Email) {
        $data = ['user_Email' => $user_Email ];
        return $this->put('user/'.$Code_user.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function user__delete($Code_user) {
        return $this->delete('user/'.$Code_user.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    // +------+
    // | task |
    // +------+

    public function task__get($Code_task) {
        return $this->get('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function task__get_all(?int $Code_user = null) {
        $requete = '';
        $Code_user = (int) $Code_user;
        if ($Code_user != 0) { $requete .= 'user/' . $Code_user . '/'; }
        return $this->get($requete . 'task?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function task__add($Code_user, $task_Name, $task_Date_creation, $task_Description, $task_Workflow) {
        $data = [
            'task_Name' => $task_Name,
            'task_Date_creation' => $task_Date_creation,
            'task_Description' => $task_Description,
            'task_Workflow' => $task_Workflow,
            'Code_user' => $Code_user,
        ];
        return $this->post('task?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function task__edit($Code_task, $Code_user, $task_Name, $task_Date_creation, $task_Description, $task_Workflow) {
        $data = [
            'task_Name' => $task_Name,
            'task_Date_creation' => $task_Date_creation,
            'task_Description' => $task_Description,
            'task_Workflow' => $task_Workflow,
            'Code_user' => $Code_user,
        ];
        return $this->put('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function task__edit__task_Name($Code_task, $task_Name) {
        $data = ['task_Name' => $task_Name ];
        return $this->put('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function task__edit__task_Date_creation($Code_task, $task_Date_creation) {
        $data = ['task_Date_creation' => $task_Date_creation ];
        return $this->put('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function task__edit__task_Description($Code_task, $task_Description) {
        $data = ['task_Description' => $task_Description ];
        return $this->put('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function task__edit__task_Workflow($Code_task, $task_Workflow) {
        $data = ['task_Workflow' => $task_Workflow ];
        return $this->put('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function task__edit__user($Code_task, $user) {
        $data = ['user' => $user ];
        return $this->put('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function task__delete($Code_task) {
        return $this->delete('task/'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    // +-------+
    // | label |
    // +-------+

    public function label__get($Code_label) {
        return $this->get('label/'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function label__get_all() {
        $requete = '';
        return $this->get($requete . 'label?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function label__add($label_Name) {
        $data = [
            'label_Name' => $label_Name,
        ];
        return $this->post('label?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function label__edit($Code_label, $label_Name) {
        $data = [
            'label_Name' => $label_Name,
        ];
        return $this->put('label/'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function label__edit__label_Name($Code_label, $label_Name) {
        $data = ['label_Name' => $label_Name ];
        return $this->put('label/'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function label__delete($Code_label) {
        return $this->delete('label/'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    // +--------------+
    // | a_task_label |
    // +--------------+

    public function a_task_label__get($Code_task, $Code_label) {
        return $this->get('a_task_label/'.$Code_task.'-'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function a_task_label__get_all(?int $Code_task = null, ?int $Code_label = null) {
        $requete = '';
        $Code_task = (int) $Code_task;
        if ($Code_task != 0) { $requete .= 'task/' . $Code_task . '/'; }
        $Code_label = (int) $Code_label;
        if ($Code_label != 0) { $requete .= 'label/' . $Code_label . '/'; }
        return $this->get($requete . 'a_task_label?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function a_task_label__add($Code_task, $Code_label, $a_task_label_Link) {
        $data = [
            'a_task_label_Link' => $a_task_label_Link,
            'Code_task' => $Code_task,
            'Code_label' => $Code_label,
        ];
        return $this->post('a_task_label?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function a_task_label__edit($Code_task, $Code_label, $a_task_label_Link) {
        $data = [
            'a_task_label_Link' => $a_task_label_Link,
        ];
        return $this->put('a_task_label/'.$Code_task.'-'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function a_task_label__edit__a_task_label_Link($Code_task, $Code_label, $a_task_label_Link) {
        $data = ['a_task_label_Link' => $a_task_label_Link ];
        return $this->put('a_task_label/'.$Code_task.'-'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function a_task_label__delete($Code_task, $Code_label) {
        return $this->delete('a_task_label/'.$Code_task.'-'.$Code_label.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    // +-------------+
    // | a_user_task |
    // +-------------+

    public function a_user_task__get($Code_user, $Code_task) {
        return $this->get('a_user_task/'.$Code_user.'-'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function a_user_task__get_all(?int $Code_user = null, ?int $Code_task = null) {
        $requete = '';
        $Code_user = (int) $Code_user;
        if ($Code_user != 0) { $requete .= 'user/' . $Code_user . '/'; }
        $Code_task = (int) $Code_task;
        if ($Code_task != 0) { $requete .= 'task/' . $Code_task . '/'; }
        return $this->get($requete . 'a_user_task?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

    public function a_user_task__add($Code_user, $Code_task, $a_user_task_Link) {
        $data = [
            'a_user_task_Link' => $a_user_task_Link,
            'Code_user' => $Code_user,
            'Code_task' => $Code_task,
        ];
        return $this->post('a_user_task?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function a_user_task__edit($Code_user, $Code_task, $a_user_task_Link) {
        $data = [
            'a_user_task_Link' => $a_user_task_Link,
        ];
        return $this->put('a_user_task/'.$Code_user.'-'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function a_user_task__edit__a_user_task_Link($Code_user, $Code_task, $a_user_task_Link) {
        $data = ['a_user_task_Link' => $a_user_task_Link ];
        return $this->put('a_user_task/'.$Code_user.'-'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance, $data);
    }

    public function a_user_task__delete($Code_user, $Code_task) {
        return $this->delete('a_user_task/'.$Code_user.'-'.$Code_task.'?mf_token='.$this->mf_token.'&mf_connector_token='.$this->mf_connector_token.'&mf_instance='.$this->mf_instance);
    }

}
