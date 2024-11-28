<?php
session_start();
define('ACCESS_ALLOWED', true);

if (!isset($_SESSION['usuari']) && isset($_COOKIE['usuari_id'])) {
    $_SESSION['usuari'] = [
        'id' => $_COOKIE['usuari_id'],
        'nom' => $_COOKIE['usuari_nom'],
        'cognoms' => $_COOKIE['usuari_cognoms'],
        'rol' => $_COOKIE['usuari_rol'],
        'imatge' => $_COOKIE['usuari_imatge'] ?? null,
        'email' => $_COOKIE['usuari_email'],
        'sector' => $_COOKIE['usuari_sector'] ?? null,
        'telefon' => $_COOKIE['usuari_telefon'] ?? null,
    ];
}
$action = $_GET['action'] ?? 'dashboardAdmin';
if (!isset($_SESSION['usuari'])) {
    $action = 'login';
}

switch ($action) {
    case 'login':
        include '../views/login.php';
        break;
    // ---------------- DASHBOARD ----------------
    case 'dashboardAdmin':
        $title = "Dashboard IncidenciesJa!";
        $content = "../views/dashboard/dashboardadmin.php";
        $styles = [
            "../public/css/dashboard.css"
        ];
        include '../views/layout.php';
        break;
    case 'dashboard':
        $title = "Dashboard - ".$_SESSION['usuari']['nom'];
        $content = "../views/dashboard/dashboard.php";
        $styles = [
            "../public/css/dashboard.css"
        ];
        include '../views/layout.php';
        break;

    // ---------------- PERFIL ----------------
    case 'perfil':
        $title = "Perfil - ".$_SESSION['usuari']['nom'];
        $content = "../views/perfil/perfil.php";
        $styles = [
            "../public/css/perfil.css"
        ];
        include '../views/layout.php';
        break;

    case 'actualitzarContrasenya':
        $title = "Actualitzar contrasenya - ".$_SESSION['usuari']['nom'];
        $content = "../views/perfil/actualitzarcontrasenya.php";
        $styles = [
            "../public/css/perfil.css"
        ];
        include '../views/layout.php';
        break;
    
    case 'veurePerfil':
        $title = "Perfil";
        $content = "../views/perfil/veurePerfil.php";
        $styles = [
            "../public/css/perfil.css"
        ];
        include '../views/layout.php';
        break;

    // ---------------- INCIDENCIES ----------------

    case 'incidencies':
        $title = "Incidencies ".$_SESSION['usuari']['nom'];
        $content = "../views/incidencies/incidencies.php";
        $styles = [
            "../public/css/incidencies.css"
        ];
        include '../views/layout.php';
        break;
    case 'filtres':
        $title = "Incidencies filtrades";
        $content = "../views/incidencies/incidencies.php";
        $styles = [
            "../public/css/incidencies.css"
        ];
        include '../views/layout.php';
        break;
    case 'filtres_usuari':
        $title = "Incidencies filtrades";
        $content = "../views/incidencies/incidencies.php";
        $styles = [
            "../public/css/incidencies.css"
        ];
        include '../views/layout.php';
        break;

    case 'crearIncidencia':
        $title = "Crear incidencia";
        $content = "../views/incidencies/crearincidencia.php";
        $styles = [
            "../public/css/incidencies.css"
        ];
        include '../views/layout.php';
        break;

    case 'veureIncidencia':
        $title = "Incidencia #".$_GET['idIncidencia'];
        $content = "../views/incidencies/veureincidencia.php";
        $styles = [
            "../public/css/incidencies.css"
        ];
        include '../views/layout.php';
        break;
    case 'crearTasca':
        $title = "Crear tasca";
        $content = "../views/incidencies/creartasca.php";
        $styles = [
            "../public/css/incidencies.css"
        ];
        include '../views/layout.php';
        break;
    case 'visualitzarIncidencia':
        $title = "Incidencia #".$_GET['idIncidencia'];
        $content = "../views/incidencies/visualitzacioincidencia.php";
        $styles = [
            "../public/css/incidencies.css"
        ];
        include '../views/layoutW.php';
        break;
    
    // ---------------- USUARIS ----------------

    case 'afegirUsuaris':
        $title = "Afegir usuaris";
        $content = "../views/usuaris/afegirusuaris.php";
        $styles = [
            "../public/css/gestionarusuaris.css"
        ];
        include '../views/layout.php';
        break;
    case 'mostrarUsuaris':
        $title = "Gestio usuaris";
        $content = "../views/usuaris/mostrarusuaris.php";
        $styles = [
            "../public/css/gestionarusuaris.css"
        ];
        include '../views/layout.php';
        break;
    case 'editarUsuaris':
        $title = "Editar usuari";
        $content = "../views/usuaris/editarusuaris.php";
        $styles = [
            "../public/css/gestionarusuaris.css"
        ];
        include '../views/layout.php';
        break;

    // ---------------- MISSATGES ----------------
    case 'xats':
        $title = "Els teus xats";
        $content = "../views/missatgeria/xats.php";
        $styles = [
            "../public/css/xat.css"
        ];
        include '../views/layout.php';
        break;
    case 'xat':
        $title = "Xat";
        $content = "../views/missatgeria/xat.php";
        $styles = [
            "../public/css/xat.css"
        ];
        include '../views/layout.php';
        break;
    case 'nouXat':
        $title = "Nou xat";
        $content = "../views/missatgeria/nouxat.php";
        $styles = [
            "../public/css/xat.css"
        ];
        include '../views/layout.php';
        break;
    
    // ---------------- CONFIGURACIO ----------------
    case 'configuracio':
        $title = "Configuracio";
        $content = "../views/configuracio/configuracio.php";
        $styles = [
            "../public/css/configuracio.css"
        ];
        include '../views/layout.php';
        break;
        
    //---------------- NOTIFICACIONS ----------------
    case 'notificacions':
        $title = "Notificacions";
        $content = "../views/notificacions/notificacions.php";
        $styles = [
            "../public/css/notificacions.css"
        ];
        include '../views/layout.php';
        break;
    
    // ---------------- CALENDARI ----------------
    case 'calendari':
        $title = "Calendari";
        $content = "../views/calendari/calendari.php";
        $styles = [
            "../public/css/calendari.css"
        ];
        include '../views/layout.php';
        break;
}
?>
