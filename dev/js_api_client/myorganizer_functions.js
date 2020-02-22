function worker() {
    execution_passe();
    setTimeout(worker, PERIODE_EXECUTION);
}

$(function(){worker();});

var chrono_promesse = 0;
var collection_promesses = [];
var nb_requetes_en_cours = 0;

function ajouter_action(type, methode, data) {
    nb_requetes_en_cours++;
    var num_promesse = ++chrono_promesse;
    $.ajax({
        url: API_ADDRESS + methode,
        type: type,
        dataType: 'json',
        contentType: 'application/json',
        processData: true,
        headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
        data: data,
        success: function(data) {
            collection_promesses[num_promesse] = data;
            nb_requetes_en_cours--;
        },
        error: function(data) {
            console.log(data.responseText);
            collection_promesses[num_promesse] = false;
            nb_requetes_en_cours--;
        }
    });
    return num_promesse;
}

function promesse(num_promesse) {
    if ( collection_promesses[num_promesse] ) {
        var $p = collection_promesses[num_promesse];
        collection_promesses[num_promesse] = false;
        return $p;
    } else {
        return false;
    }
}

// Functions api

var mf_token = "";

var id_promesse__connexion = 0;
function connexion(mf_login, mf_pwd) { id_promesse__connexion = ajouter_action( "POST", "mf_connexion?mf_instance=" + mf_instance, JSON.stringify( { mf_login: mf_login, mf_pwd: mf_pwd } ) ); }
function r__connexion() { var r = promesse(id_promesse__connexion); if ( r ) { mf_token = r['data']['mf_token']; } return r; }

var id_promesse__inscription = 0;
function inscription(mf_login, mf_pwd, mf_pwd_2, mf_email, mf_email_2) { id_promesse__inscription = ajouter_action( "POST", "mf_inscription?mf_instance=" + mf_instance, JSON.stringify( { mf_login: mf_login, mf_pwd: mf_pwd, mf_pwd_2: mf_pwd_2, mf_email: mf_email, mf_email_2: mf_email_2 } ) ); }
function r__inscription() { return promesse(id_promesse__inscription); }

var id_promesse__maj_mdp = 0;
function maj_mdp(Code_user, mf_current_pwd, mf_new_pwd, mf_conf_pwd) { id_promesse__maj_mdp = ajouter_action( "PUT", "mf_change_password/" + Code_user + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify( { mf_current_pwd: mf_current_pwd, mf_new_pwd: mf_new_pwd, mf_conf_pwd: mf_conf_pwd } ) ); }
function r__maj_mdp() { return promesse(id_promesse__maj_mdp); }

var id_promesse__demande_nouv_mdp = 0;
function demande_nouv_mdp(mf_login, mf_email) { id_promesse__demande_nouv_mdp = ajouter_action( "POST", "mf_new_password?mf_instance=" + mf_instance, JSON.stringify( { mf_login: mf_login, mf_email: mf_email } ) ); }
function r__demande_nouv_mdp() { return promesse(id_promesse__demande_nouv_mdp); }

// +------+
// | user |
// +------+

var id_promesse__user__get = 0;
var ref_promesse__user__get = '';
function user__get(Code_user, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__user__get = ref; id_promesse__user__get = ajouter_action( "GET", "user/" + Code_user + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__user__get(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__user__get == ref ) { return promesse(id_promesse__user__get); } else { return false; } }

var id_promesse__user__get_all = 0;
var ref_promesse__user__get_all = '';
function user__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__user__get_all = ref; id_promesse__user__get_all = ajouter_action( "GET", "user?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__user__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__user__get_all == ref ) { return promesse(id_promesse__user__get_all); } else { return false; } }

/*
  json_data = {
    user_Login: …,
    user_Password: …,
    user_Email: …,
    user_Admin: …,
  }
*/
var id_promesse__user__post = 0;
var ref_promesse__user__post = '';
function user__post(json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__user__post = ref; id_promesse__user__post = ajouter_action( "POST", "user?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__user__post(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__user__post == ref ) { return promesse(id_promesse__user__post); } else { return false; } }

/*
  json_data = {
    user_Login: …,
    user_Password: …,
    user_Email: …,
    user_Admin: …,
  }
*/
var id_promesse__user__put = 0;
var ref_promesse__user__put = '';
function user__put(Code_user, json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__user__put = ref; id_promesse__user__put = ajouter_action( "PUT", "user/" + Code_user + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__user__put(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__user__put == ref ) { return promesse(id_promesse__user__put); } else { return false; } }

var id_promesse__user__delete = 0;
var ref_promesse__user__delete = '';
function user__delete(Code_user, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__user__delete = ref; id_promesse__user__delete = ajouter_action( "DELETE", "user/" + Code_user + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth ); }
function r__user__delete(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__user__delete == ref ) { return promesse(id_promesse__user__delete); } else { return false; } }

// +------+
// | task |
// +------+

var id_promesse__task__get = 0;
var ref_promesse__task__get = '';
function task__get(Code_task, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__task__get = ref; id_promesse__task__get = ajouter_action( "GET", "task/" + Code_task + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__task__get(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__task__get == ref ) { return promesse(id_promesse__task__get); } else { return false; } }

var id_promesse__task__get_all = 0;
var ref_promesse__task__get_all = '';
function task__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__task__get_all = ref; id_promesse__task__get_all = ajouter_action( "GET", "task?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__task__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__task__get_all == ref ) { return promesse(id_promesse__task__get_all); } else { return false; } }

/*
  json_data = {
    task_Name: …,
    task_Date_creation: …,
    task_Description: …,
    task_Workflow: …,
    Code_user: …,
  }
*/
var id_promesse__task__post = 0;
var ref_promesse__task__post = '';
function task__post(json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__task__post = ref; id_promesse__task__post = ajouter_action( "POST", "task?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__task__post(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__task__post == ref ) { return promesse(id_promesse__task__post); } else { return false; } }

/*
  json_data = {
    task_Name: …,
    task_Date_creation: …,
    task_Description: …,
    task_Workflow: …,
    Code_user: …,
  }
*/
var id_promesse__task__put = 0;
var ref_promesse__task__put = '';
function task__put(Code_task, json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__task__put = ref; id_promesse__task__put = ajouter_action( "PUT", "task/" + Code_task + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__task__put(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__task__put == ref ) { return promesse(id_promesse__task__put); } else { return false; } }

var id_promesse__task__delete = 0;
var ref_promesse__task__delete = '';
function task__delete(Code_task, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__task__delete = ref; id_promesse__task__delete = ajouter_action( "DELETE", "task/" + Code_task + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth ); }
function r__task__delete(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__task__delete == ref ) { return promesse(id_promesse__task__delete); } else { return false; } }

// +-------+
// | label |
// +-------+

var id_promesse__label__get = 0;
var ref_promesse__label__get = '';
function label__get(Code_label, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__label__get = ref; id_promesse__label__get = ajouter_action( "GET", "label/" + Code_label + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__label__get(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__label__get == ref ) { return promesse(id_promesse__label__get); } else { return false; } }

var id_promesse__label__get_all = 0;
var ref_promesse__label__get_all = '';
function label__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__label__get_all = ref; id_promesse__label__get_all = ajouter_action( "GET", "label?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__label__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__label__get_all == ref ) { return promesse(id_promesse__label__get_all); } else { return false; } }

/*
  json_data = {
    label_Name: …,
  }
*/
var id_promesse__label__post = 0;
var ref_promesse__label__post = '';
function label__post(json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__label__post = ref; id_promesse__label__post = ajouter_action( "POST", "label?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__label__post(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__label__post == ref ) { return promesse(id_promesse__label__post); } else { return false; } }

/*
  json_data = {
    label_Name: …,
  }
*/
var id_promesse__label__put = 0;
var ref_promesse__label__put = '';
function label__put(Code_label, json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__label__put = ref; id_promesse__label__put = ajouter_action( "PUT", "label/" + Code_label + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__label__put(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__label__put == ref ) { return promesse(id_promesse__label__put); } else { return false; } }

var id_promesse__label__delete = 0;
var ref_promesse__label__delete = '';
function label__delete(Code_label, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__label__delete = ref; id_promesse__label__delete = ajouter_action( "DELETE", "label/" + Code_label + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth ); }
function r__label__delete(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__label__delete == ref ) { return promesse(id_promesse__label__delete); } else { return false; } }

// +--------------+
// | a_task_label |
// +--------------+

var id_promesse__a_task_label__get = 0;
var ref_promesse__a_task_label__get = '';
function a_task_label__get(Code_task, Code_label, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_task_label__get = ref; id_promesse__a_task_label__get = ajouter_action( "GET", "a_task_label/" + Code_task + '-' + Code_label + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__a_task_label__get(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_task_label__get == ref ) { return promesse(id_promesse__a_task_label__get); } else { return false; } }

var id_promesse__a_task_label__get_all = 0;
var ref_promesse__a_task_label__get_all = '';
function a_task_label__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_task_label__get_all = ref; id_promesse__a_task_label__get_all = ajouter_action( "GET", "a_task_label?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__a_task_label__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_task_label__get_all == ref ) { return promesse(id_promesse__a_task_label__get_all); } else { return false; } }

/*
  json_data = {
    a_task_label_Link: …,
    Code_task: …,
    Code_label: …,
  }
*/
var id_promesse__a_task_label__post = 0;
var ref_promesse__a_task_label__post = '';
function a_task_label__post(json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_task_label__post = ref; id_promesse__a_task_label__post = ajouter_action( "POST", "a_task_label?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__a_task_label__post(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_task_label__post == ref ) { return promesse(id_promesse__a_task_label__post); } else { return false; } }

/*
  json_data = {
    a_task_label_Link: …,
  }
*/
var id_promesse__a_task_label__put = 0;
var ref_promesse__a_task_label__put = '';
function a_task_label__put(Code_task, Code_label, json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_task_label__put = ref; id_promesse__a_task_label__put = ajouter_action( "PUT", "a_task_label/" + Code_task + '-' + Code_label + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__a_task_label__put(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_task_label__put == ref ) { return promesse(id_promesse__a_task_label__put); } else { return false; } }

var id_promesse__a_task_label__delete = 0;
var ref_promesse__a_task_label__delete = '';
function a_task_label__delete(Code_task, Code_label, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_task_label__delete = ref; id_promesse__a_task_label__delete = ajouter_action( "DELETE", "a_task_label/" + Code_task + '-' + Code_label + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth ); }
function r__a_task_label__delete(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_task_label__delete == ref ) { return promesse(id_promesse__a_task_label__delete); } else { return false; } }

// +-------------+
// | a_user_task |
// +-------------+

var id_promesse__a_user_task__get = 0;
var ref_promesse__a_user_task__get = '';
function a_user_task__get(Code_user, Code_task, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_user_task__get = ref; id_promesse__a_user_task__get = ajouter_action( "GET", "a_user_task/" + Code_user + '-' + Code_task + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__a_user_task__get(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_user_task__get == ref ) { return promesse(id_promesse__a_user_task__get); } else { return false; } }

var id_promesse__a_user_task__get_all = 0;
var ref_promesse__a_user_task__get_all = '';
function a_user_task__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_user_task__get_all = ref; id_promesse__a_user_task__get_all = ajouter_action( "GET", "a_user_task?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, "" ); }
function r__a_user_task__get_all(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_user_task__get_all == ref ) { return promesse(id_promesse__a_user_task__get_all); } else { return false; } }

/*
  json_data = {
    a_user_task_Link: …,
    Code_user: …,
    Code_task: …,
  }
*/
var id_promesse__a_user_task__post = 0;
var ref_promesse__a_user_task__post = '';
function a_user_task__post(json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_user_task__post = ref; id_promesse__a_user_task__post = ajouter_action( "POST", "a_user_task?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__a_user_task__post(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_user_task__post == ref ) { return promesse(id_promesse__a_user_task__post); } else { return false; } }

/*
  json_data = {
    a_user_task_Link: …,
  }
*/
var id_promesse__a_user_task__put = 0;
var ref_promesse__a_user_task__put = '';
function a_user_task__put(Code_user, Code_task, json_data, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_user_task__put = ref; id_promesse__a_user_task__put = ajouter_action( "PUT", "a_user_task/" + Code_user + '-' + Code_task + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth, JSON.stringify(json_data) ); }
function r__a_user_task__put(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_user_task__put == ref ) { return promesse(id_promesse__a_user_task__put); } else { return false; } }

var id_promesse__a_user_task__delete = 0;
var ref_promesse__a_user_task__delete = '';
function a_user_task__delete(Code_user, Code_task, ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; ref_promesse__a_user_task__delete = ref; id_promesse__a_user_task__delete = ajouter_action( "DELETE", "a_user_task/" + Code_user + '-' + Code_task + "?mf_instance=" + mf_instance + "&mf_token=" + mf_token + "&auth=" + auth ); }
function r__a_user_task__delete(ref) { var ref = (typeof ref !== 'undefined') ? ref : ''; if ( ref_promesse__a_user_task__delete == ref ) { return promesse(id_promesse__a_user_task__delete); } else { return false; } }

