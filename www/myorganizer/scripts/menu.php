<?php declare(strict_types=1);
$pages_menu = [];

$categorie_menu = '<span class="fa fa-cogs"></span> Mon menu';

$pages_menu[$categorie_menu][] = [
    'nom' => 'user',
    'icone' => '<span class="fa fa-cogs"></span>',
    'adresse' => 'user.php'
];
$pages_menu[$categorie_menu][] = [
    'nom' => 'task',
    'icone' => '<span class="fa fa-cogs"></span>',
    'adresse' => 'task.php'
];
$pages_menu[$categorie_menu][] = [
    'nom' => 'label',
    'icone' => '<span class="fa fa-cogs"></span>',
    'adresse' => 'label.php'
];
$pages_menu[$categorie_menu][] = [
    'nom' => 'a_task_label',
    'icone' => '<span class="fa fa-cogs"></span>',
    'adresse' => 'a_task_label.php'
];
$pages_menu[$categorie_menu][] = [
    'nom' => 'a_user_task',
    'icone' => '<span class="fa fa-cogs"></span>',
    'adresse' => 'a_user_task.php'
];

unset($categorie_menu);

function generer_menu_principal()
{
    global $pages_menu, $fil_ariane;
    $code_menu = '<nav><ul id="navigation">';
    foreach ($pages_menu as $rubrique => $liste) {
        $code_menu .= '<li><span class="categorie_menu">' . $rubrique . '</span><ul>';
        foreach ($liste as $value) {
            $code_menu .= '<li class="' . (get_nom_page_courante() == $value['adresse'] ? 'active' : '') . '"><a href="' . $value['adresse'] . '">' . htmlspecialchars($value['nom']) . '</a></li>';
            if (get_nom_page_courante() == $value['adresse']) {
                $fil_ariane->ajouter_titre($value['nom'], $value['adresse']);
            }
        }
        $code_menu .= '</ul></li>';
    }
    $code_menu .= '</ul></nav>';
    return $code_menu;
}

function generer_menu_principal_bootstrap()
{
    if (VERSION_BOOTSTRAP == 4) {
        return menu_bt_4();
    }
    global $pages_menu, $fil_ariane;
    $code_menu = '';
    $code_menu .= '<ul class="nav navbar-nav">';
    foreach ($pages_menu as $rubrique => $liste) {
        if (count($liste) > 1) {
            $active = false;
            foreach ($liste as $value) {
                if (get_nom_page_courante() == $value['adresse']) {
                    $active = true;
                }
            }
            $code_menu .= '<li class="dropdown' . ($active ? ' active' : '') . '">';
            $code_menu .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#">' . htmlspecialchars($rubrique) . '<span class="caret"></span></a>';
            $code_menu .= '<ul class="dropdown-menu">';
        }
        foreach ($liste as $value) {
            $code_menu .= '<li class="' . (get_nom_page_courante() == $value['adresse'] ? 'active' : '') . '"><a href="' . $value['adresse'] . '">' . htmlspecialchars($value['nom']) . '</a></li>';
            if (get_nom_page_courante() == $value['adresse']) {
                $fil_ariane->ajouter_titre($value['nom'], $value['adresse']);
            }
        }
        if (count($liste) > 1) {
            $code_menu .= '</ul>';
            $code_menu .= '</li>';
        }
    }
    $code_menu .= '</ul>';
    $code_menu .= '<ul class="nav navbar-nav navbar-right">';
    if (isset($_SESSION[PREFIXE_SESSION]['token'])) {
        $db = new DB();
        $code_menu .= '<li class="dropdown">';
        $code_menu .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> ' . htmlspecialchars(get_titre_ligne_table('user', get_user_courant())) . (get_instance() != 0 ? ' <i>' . htmlspecialchars('(' . get_titre_ligne_table(TABLE_INSTANCE, $db->mf_table(TABLE_INSTANCE)->mf_get_2(get_instance())) . ')') . '</i>' : '') . '<span class="caret"></span></a>';
        $code_menu .= '<ul class="dropdown-menu">';
        $code_menu .= '<li><a href=user.php?act=apercu_user&Code_user=' . get_user_courant('Code_user') . '><span class="glyphicon glyphicon-cog"></span> Mon compte</a></li>';
        $code_menu .= '<li><a href="?act=deconnexion"><span class="glyphicon glyphicon-log-out"></span> Déconnexion</a></li>';
        $code_menu .= '<li><a href="#" onclick="Fullscreen();"><span class="glyphicon glyphicon-fullscreen"></span> Page en pleine écran</a>';
        $code_menu .= '<li><a href="?act=vider_cache"><span class="glyphicon glyphicon-flash"></span> Vider le cache</a></li>';
        $code_menu .= '</ul>';
        $code_menu .= '</li>';
    }
    $code_menu .= '<script>';
    $code_menu .= 'var mode_full_screen=false;';
    $code_menu .= 'function Fullscreen() {
      var elem = document.documentElement;
      if (mode_full_screen)
      {
        if (elem.requestFullscreen) {
          document.exitFullscreen();
        } else if (elem.webkitRequestFullscreen) {
          document.webkitExitFullscreen();
        } else if (elem.mozRequestFullScreen) {
          document.mozCancelFullScreen();
        } else if (elem.msRequestFullscreen) {
          document.msExitFullscreen();
        }
        mode_full_screen = false;
      }
      else
      {
        if (elem.requestFullscreen) {
          elem.requestFullscreen();
        } else if (elem.msRequestFullscreen) {
          elem.msRequestFullscreen();
        } else if (elem.mozRequestFullScreen) {
          elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
          elem.webkitRequestFullscreen();
        }
        mode_full_screen = true;
      }
    }';
    $code_menu .= '</script>';
    $code_menu .= '</li>';
    $code_menu .= '</ul>';
    return $code_menu;
}

function menu_bt_4()
{
    global $pages_menu, $fil_ariane;
    $code_menu = '';
    $code_menu .= '<ul class="navbar-nav mr-auto">';
    foreach ($pages_menu as $rubrique => $liste) {
        $active = false;
        foreach ($liste as $value) {
            if (get_nom_page_courante() == $value['adresse']) {
                $active = true;
            }
        }
        $code_menu .= '<li class="nav-item ' . ($active ? ' active' : '') . (count($liste) > 1 ? ' dropdown' : '') . '"><a class="nav-link' . (count($liste) > 1 ? ' dropdown-toggle' : '') . '" ' . (count($liste) > 1 ? 'href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'href="' . $liste[0]['adresse'] . '"') . '>' . (count($liste) > 1 ? $rubrique : $liste[0]['icone'] . ' ' . htmlspecialchars($liste[0]['nom'])) . '</a>';
        if (count($liste) > 1) {
            $code_menu .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
            foreach ($liste as $value) {
                $code_menu .= '<a class="dropdown-item' . (get_nom_page_courante() == $value['adresse'] ? ' active' : '') . '" href="' . $value['adresse'] . '">' . $value['icone'] . ' ' . htmlspecialchars($value['nom']) . '</a>';
                if (get_nom_page_courante() == $value['adresse']) {
                    $fil_ariane->ajouter_titre($value['nom'], $value['adresse']);
                }
            }
            $code_menu .= '</div>';
        } elseif (get_nom_page_courante() == $liste[0]['adresse']) {
            $fil_ariane->ajouter_titre($liste[0]['nom'], $liste[0]['adresse']);
        }
        $code_menu .= '</li>';
    }
    $code_menu .= '</ul>';

    // Menu utilisateurs
    $code_menu .= '<ul class="navbar-nav justify-content-end">';
    $code_menu .= '<li class="nav-item dropdown">';
    $code_menu .= '<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="fa fa-user"></span> ' . htmlspecialchars(get_titre_ligne_table('user', get_user_courant())) . (get_instance() != 0 ? ' <i>' . htmlspecialchars('(' . get_titre_ligne_table(TABLE_INSTANCE, $db->mf_table(TABLE_INSTANCE)->mf_get_2(get_instance())) . ')') . '</i>' : '') . '</a>';
    $code_menu .= '<div class="dropdown-menu dropdown-menu-md-right" aria-labelledby="navbarDropdown">';
    $code_menu .= '<a class="dropdown-item" href="user.php?act=apercu_user&Code_user=' . get_user_courant('Code_user') . '"><span class="fa fa-sliders"></span> Mon compte</a>';
    $code_menu .= '<a class="dropdown-item" href="?act=deconnexion"><span class="fa fa-sign-out"></span> Déconnexion</a>';
    $code_menu .= '<a class="dropdown-item" href="#" onclick="Fullscreen();"><span class="fa fa-expand"></span> Page en pleine écran</a>';
    $code_menu .= '<a class="dropdown-item" href="?act=vider_cache"><span class="fa fa-cogs"></span> Vider le cache</a>';
    $code_menu .= '</div>';
    $code_menu .= '</li>';
    $code_menu .= '</ul>';

    // Script pleine écran
    $code_menu .= '<script>';
    $code_menu .= 'var mode_full_screen=false;';
    $code_menu .= 'function Fullscreen() {';
    $code_menu .= 'var elem = document.documentElement;';
    $code_menu .= 'if (mode_full_screen)';
    $code_menu .= '{';
    $code_menu .= 'if (elem.requestFullscreen) {';
    $code_menu .= 'document.exitFullscreen();';
    $code_menu .= '} else if (elem.webkitRequestFullscreen) {';
    $code_menu .= 'document.webkitExitFullscreen();';
    $code_menu .= '} else if (elem.mozRequestFullScreen) {';
    $code_menu .= 'document.mozCancelFullScreen();';
    $code_menu .= '} else if (elem.msRequestFullscreen) {';
    $code_menu .= 'document.msExitFullscreen();';
    $code_menu .= '}';
    $code_menu .= 'mode_full_screen = false;';
    $code_menu .= '} else {';
    $code_menu .= 'if (elem.requestFullscreen) {';
    $code_menu .= 'elem.requestFullscreen();';
    $code_menu .= '} else if (elem.msRequestFullscreen) {';
    $code_menu .= 'elem.msRequestFullscreen();';
    $code_menu .= '} else if (elem.mozRequestFullScreen) {';
    $code_menu .= 'elem.mozRequestFullScreen();';
    $code_menu .= '} else if (elem.webkitRequestFullscreen) {';
    $code_menu .= 'elem.webkitRequestFullscreen();';
    $code_menu .= '}';
    $code_menu .= 'mode_full_screen = true;';
    $code_menu .= '}';
    $code_menu .= '}';
    $code_menu .= '</script>';

    return $code_menu;
}

function generer_menu_bandeau()
{
    global $pages_menu;
    $l = [
        1 => 12,
        2 => 6,
        3 => 4,
        4 => 3
    ];
    $c = count($pages_menu);
    $n = (isset($l[$c]) ? $l[$c] : 2);
    $code_menu = '';
    $code_menu .= '<div class="row">';
    foreach ($pages_menu as $rubrique => $liste) {
        $code_menu .= '<div class="col-sm-' . $n . '">';
        $code_menu .= '<div class="card">';
        $code_menu .= '<div class="card-body">';
        $code_menu .= '<h5 class="card-title">' . $rubrique . '</h5>';
        $i = 0;
        foreach ($liste as $value) {
            $i ++;
            $code_menu .= '<a role="button" class="btn btn-info btn-block btn-lg" href="' . $value['adresse'] . '">' . $value['icone'] . ' ' . htmlspecialchars($value['nom']) . '</a>';
        }
        $code_menu .= '</div>';
        $code_menu .= '</div>';
        $code_menu .= '</div>';
    }
    $code_menu .= '</div>';
    return $code_menu;
}

if (USE_BOOTSTRAP)
    echo generer_menu_principal_bootstrap();
else
    echo generer_menu_principal();
