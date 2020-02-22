Option Explicit

Dim mf_token As String

Function connexion(ByVal mf_login As String, ByVal mf_pwd As String) As Boolean
    requete.nettoyage
    mf_login = requete.convert_encode_url(mf_login)
    mf_pwd = requete.convert_encode_url(mf_pwd)
    requete.requete_serveur "connexion.php?mf_login=" & mf_login & "&mf_pwd=" & mf_pwd & "&vue=tableau"
    requete.vider_le_cache
    connexion = requete.retour_ok()
    If connexion Then
        mf_token = requete.Cells(2, 2)
    End If
End Function

Function deconnexion() As Boolean
    Code_user = parametres.get_Code_user
    user_cle_de_connexion = parametres.get_user_cle_de_connexion()
    requete.requete_serveur "deconnexion.php?mf_token=" & mf_token & "&vue=tableau"
    requete.vider_le_cache
    requete.nettoyage
End Function

'   +------+
'   | user |
'   +------+

Function user__ajouter(ByVal user_Login As String, ByVal user_Password As String, ByVal user_Email As String) As Long
    user_Login = requete.convert_encode_url(user_Login)
    user_Password = requete.convert_encode_url(user_Password)
    user_Email = requete.convert_encode_url(user_Email)
    requete.requete_serveur "user/ajouter.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&user_Login=" & user_Login & "&user_Password=" & user_Password & "&user_Email=" & user_Email
    user__ajouter = requete.retour_ok()
End Function

Function user__modifier(ByVal Code_user As String, ByVal user_Login As String, ByVal user_Password As String, ByVal user_Email As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    user_Login = requete.convert_encode_url(user_Login)
    user_Password = requete.convert_encode_url(user_Password)
    user_Email = requete.convert_encode_url(user_Email)
    requete.requete_serveur "user/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&user_Login=" & user_Login & "&user_Password=" & user_Password & "&user_Email=" & user_Email
    user__modifier = requete.retour_ok()
End Function

Function user__modifier__user_Login(ByVal Code_user As String, ByVal user_Login As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    user_Login = requete.convert_encode_url(user_Login)
    requete.requete_serveur "user/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&user_Login=" & user_Login
    user__modifier__user_Login = requete.retour_ok()
End Function

Function user__modifier__user_Password(ByVal Code_user As String, ByVal user_Password As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    user_Password = requete.convert_encode_url(user_Password)
    requete.requete_serveur "user/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&user_Password=" & user_Password
    user__modifier__user_Password = requete.retour_ok()
End Function

Function user__modifier__user_Email(ByVal Code_user As String, ByVal user_Email As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    user_Email = requete.convert_encode_url(user_Email)
    requete.requete_serveur "user/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&user_Email=" & user_Email
    user__modifier__user_Email = requete.retour_ok()
End Function

Function user__supprimer(ByVal Code_user As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    requete.requete_serveur "user/supprimer.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user
    user__supprimer = requete.retour_ok()
End Function

Function user__lister() As Long
    requete.requete_serveur "user/lister.php?" & "vue=tableau" & "&mf_token=" & mf_token
    user__lister = requete.retour_ok()
End Function

'   +------+
'   | task |
'   +------+

Function task__ajouter(ByVal Code_user As String, ByVal task_Name As String, ByVal task_Date_creation As String, ByVal task_Description As String, ByVal task_Workflow As String) As Long
    task_Name = requete.convert_encode_url(task_Name)
    task_Date_creation = requete.convert_encode_url(task_Date_creation)
    task_Description = requete.convert_encode_url(task_Description)
    task_Workflow = requete.convert_encode_url(task_Workflow)
    Code_user = requete.convert_encode_url(Code_user)
    requete.requete_serveur "task/ajouter.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&task_Name=" & task_Name & "&task_Date_creation=" & task_Date_creation & "&task_Description=" & task_Description & "&task_Workflow=" & task_Workflow
    task__ajouter = requete.retour_ok()
End Function

Function task__modifier(ByVal Code_task As String, ByVal Code_user As String, ByVal task_Name As String, ByVal task_Date_creation As String, ByVal task_Description As String, ByVal task_Workflow As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    task_Name = requete.convert_encode_url(task_Name)
    task_Date_creation = requete.convert_encode_url(task_Date_creation)
    task_Description = requete.convert_encode_url(task_Description)
    task_Workflow = requete.convert_encode_url(task_Workflow)
    Code_user = requete.convert_encode_url(Code_user)
    requete.requete_serveur "task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&Code_user=" & Code_user & "&task_Name=" & task_Name & "&task_Date_creation=" & task_Date_creation & "&task_Description=" & task_Description & "&task_Workflow=" & task_Workflow
    task__modifier = requete.retour_ok()
End Function

Function task__modifier__task_Name(ByVal Code_task As String, ByVal task_Name As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    task_Name = requete.convert_encode_url(task_Name)
    requete.requete_serveur "task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&task_Name=" & task_Name
    task__modifier__task_Name = requete.retour_ok()
End Function

Function task__modifier__task_Date_creation(ByVal Code_task As String, ByVal task_Date_creation As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    task_Date_creation = requete.convert_encode_url(task_Date_creation)
    requete.requete_serveur "task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&task_Date_creation=" & task_Date_creation
    task__modifier__task_Date_creation = requete.retour_ok()
End Function

Function task__modifier__task_Description(ByVal Code_task As String, ByVal task_Description As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    task_Description = requete.convert_encode_url(task_Description)
    requete.requete_serveur "task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&task_Description=" & task_Description
    task__modifier__task_Description = requete.retour_ok()
End Function

Function task__modifier__task_Workflow(ByVal Code_task As String, ByVal task_Workflow As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    task_Workflow = requete.convert_encode_url(task_Workflow)
    requete.requete_serveur "task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&task_Workflow=" & task_Workflow
    task__modifier__task_Workflow = requete.retour_ok()
End Function

Function task__modifier__Code_user(ByVal Code_task As String, ByVal Code_user As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    Code_user = requete.convert_encode_url(Code_user)
    requete.requete_serveur "task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&Code_user=" & Code_user
    task__modifier__task_Name = requete.retour_ok()
End Function

Function task__supprimer(ByVal Code_task As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    requete.requete_serveur "task/supprimer.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task
    task__supprimer = requete.retour_ok()
End Function

Function task__lister(ByVal Code_user As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    requete.requete_serveur "task/lister.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user
    task__lister = requete.retour_ok()
End Function

'   +-------+
'   | label |
'   +-------+

Function label__ajouter(ByVal label_Name As String) As Long
    label_Name = requete.convert_encode_url(label_Name)
    requete.requete_serveur "label/ajouter.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&label_Name=" & label_Name
    label__ajouter = requete.retour_ok()
End Function

Function label__modifier(ByVal Code_label As String, ByVal label_Name As String) As Long
    Code_label = requete.convert_encode_url(Code_label)
    label_Name = requete.convert_encode_url(label_Name)
    requete.requete_serveur "label/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_label=" & Code_label & "&label_Name=" & label_Name
    label__modifier = requete.retour_ok()
End Function

Function label__modifier__label_Name(ByVal Code_label As String, ByVal label_Name As String) As Long
    Code_label = requete.convert_encode_url(Code_label)
    label_Name = requete.convert_encode_url(label_Name)
    requete.requete_serveur "label/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_label=" & Code_label & "&label_Name=" & label_Name
    label__modifier__label_Name = requete.retour_ok()
End Function

Function label__supprimer(ByVal Code_label As String) As Long
    Code_label = requete.convert_encode_url(Code_label)
    requete.requete_serveur "label/supprimer.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_label=" & Code_label
    label__supprimer = requete.retour_ok()
End Function

Function label__lister() As Long
    requete.requete_serveur "label/lister.php?" & "vue=tableau" & "&mf_token=" & mf_token
    label__lister = requete.retour_ok()
End Function

'   +--------------+
'   | a_task_label |
'   +--------------+

Function a_task_label__ajouter(ByVal Code_task As String, ByVal Code_label As String, ByVal a_task_label_Link As String) As Long
    a_task_label_Link = requete.convert_encode_url(a_task_label_Link)
    Code_task = requete.convert_encode_url(Code_task)
    Code_label = requete.convert_encode_url(Code_label)
    requete.requete_serveur "a_task_label/ajouter.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&Code_label=" & Code_label & "&a_task_label_Link=" & a_task_label_Link
    a_task_label__ajouter = requete.retour_ok()
End Function

Function a_task_label__modifier(ByVal Code_task As String, ByVal Code_label As String, ByVal a_task_label_Link As String) As Long
    Code_a_task_label = requete.convert_encode_url(Code_a_task_label)
    a_task_label_Link = requete.convert_encode_url(a_task_label_Link)
    Code_task = requete.convert_encode_url(Code_task)
    Code_label = requete.convert_encode_url(Code_label)
    requete.requete_serveur "a_task_label/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&Code_label=" & Code_label & "&a_task_label_Link=" & a_task_label_Link
    a_task_label__modifier = requete.retour_ok()
End Function

Function a_task_label__modifier__a_task_label_Link(ByVal Code_task As String, ByVal Code_label As String, ByVal a_task_label_Link As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    Code_label = requete.convert_encode_url(Code_label)
    a_task_label_Link = requete.convert_encode_url(a_task_label_Link)
    requete.requete_serveur "a_task_label/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&Code_label=" & Code_label & "&a_task_label_Link=" & a_task_label_Link
    a_task_label__modifier__a_task_label_Link = requete.retour_ok()
End Function

Function a_task_label__supprimer(ByVal Code_task As String, ByVal Code_label As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    Code_label = requete.convert_encode_url(Code_label)
    requete.requete_serveur "a_task_label/supprimer.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&Code_label=" & Code_label
    a_task_label__supprimer = requete.retour_ok()
End Function

Function a_task_label__lister(ByVal Code_task As String, ByVal Code_label As String) As Long
    Code_task = requete.convert_encode_url(Code_task)
    Code_label = requete.convert_encode_url(Code_label)
    requete.requete_serveur "a_task_label/lister.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_task=" & Code_task & "&Code_label=" & Code_label
    a_task_label__lister = requete.retour_ok()
End Function

'   +-------------+
'   | a_user_task |
'   +-------------+

Function a_user_task__ajouter(ByVal Code_user As String, ByVal Code_task As String, ByVal a_user_task_Link As String) As Long
    a_user_task_Link = requete.convert_encode_url(a_user_task_Link)
    Code_user = requete.convert_encode_url(Code_user)
    Code_task = requete.convert_encode_url(Code_task)
    requete.requete_serveur "a_user_task/ajouter.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&Code_task=" & Code_task & "&a_user_task_Link=" & a_user_task_Link
    a_user_task__ajouter = requete.retour_ok()
End Function

Function a_user_task__modifier(ByVal Code_user As String, ByVal Code_task As String, ByVal a_user_task_Link As String) As Long
    Code_a_user_task = requete.convert_encode_url(Code_a_user_task)
    a_user_task_Link = requete.convert_encode_url(a_user_task_Link)
    Code_user = requete.convert_encode_url(Code_user)
    Code_task = requete.convert_encode_url(Code_task)
    requete.requete_serveur "a_user_task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&Code_task=" & Code_task & "&a_user_task_Link=" & a_user_task_Link
    a_user_task__modifier = requete.retour_ok()
End Function

Function a_user_task__modifier__a_user_task_Link(ByVal Code_user As String, ByVal Code_task As String, ByVal a_user_task_Link As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    Code_task = requete.convert_encode_url(Code_task)
    a_user_task_Link = requete.convert_encode_url(a_user_task_Link)
    requete.requete_serveur "a_user_task/modifier.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&Code_task=" & Code_task & "&a_user_task_Link=" & a_user_task_Link
    a_user_task__modifier__a_user_task_Link = requete.retour_ok()
End Function

Function a_user_task__supprimer(ByVal Code_user As String, ByVal Code_task As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    Code_task = requete.convert_encode_url(Code_task)
    requete.requete_serveur "a_user_task/supprimer.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&Code_task=" & Code_task
    a_user_task__supprimer = requete.retour_ok()
End Function

Function a_user_task__lister(ByVal Code_user As String, ByVal Code_task As String) As Long
    Code_user = requete.convert_encode_url(Code_user)
    Code_task = requete.convert_encode_url(Code_task)
    requete.requete_serveur "a_user_task/lister.php?" & "vue=tableau" & "&mf_token=" & mf_token & "&Code_user=" & Code_user & "&Code_task=" & Code_task
    a_user_task__lister = requete.retour_ok()
End Function

