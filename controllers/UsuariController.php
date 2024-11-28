<?php
if(!isset($_SESSION)){
    session_start();
}
include_once '../config/database.php';
include_once '../models/Usuari.php';

class UsuariController {
    private $conn;
    private $usuari;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->usuari = new Usuari($this->conn);
    }

    // public function registre() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $this->usuari->nom = $_POST['nom'];
    //         $this->usuari->cognoms = $_POST['cognoms'];
    //         $this->usuari->email = $_POST['email'];
    //         $this->usuari->contrasenya = $_POST['contrasenya'];

    //         if ($this->usuari->registre()) {
    //             header('Location: ../views/login.php');
    //             exit();
    //         } else {
    //             echo "Error al registrar.";
    //         }
    //     }
    // }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->usuari->email = $_POST['email'];
            $this->usuari->contrasenya = $_POST['contrasenya'];
            $user = $this->usuari->login();
    
            if ($user) {
                $_SESSION['usuari'] = [
                    'id' => $user['id_usuari'],
                    'nom' => $user['nom'],
                    'cognoms' => $user['cognoms'],
                    'rol' => $user['rol'],
                    'imatge' => $user['imatge'],
                    'email' => $user['email'],
                    'sector' => $user['sector'],
                    'telefon' => $user['telefono']
                ];
    
                // Establecer cookies con un tiempo de expiración de 30 días
                setcookie('usuari_id', $user['id_usuari'], time() + (86400 * 30), "/");
                setcookie('usuari_nom', $user['nom'], time() + (86400 * 30), "/");
                setcookie('usuari_cognoms', $user['cognoms'], time() + (86400 * 30), "/");
                setcookie('usuari_rol', $user['rol'], time() + (86400 * 30), "/");
                setcookie('usuari_email', $user['email'], time() + (86400 * 30), "/");
                setcookie('usuari_sector', $user['sector'], time() + (86400 * 30), "/");
                setcookie('usuari_telefon', $user['telefono'], time() + (86400 * 30), "/");
                setcookie('usuari_imatge', $user['imatge'], time() + (86400 * 30), "/");
                header('Location: ../public/index.php');
                exit();
            } else {
                echo "<script>alert('Usuari o contrasenya incorrectes.');</script>";
                header('Location: ../public/index.php?action=login');
            }
        }
    }
    
    public function actualitzarContrasenya(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->usuari->id_usuari = $_SESSION['usuari']['id'];
            $this->usuari->contrasenya = $_POST['contrasenya'];
            $this->usuari->nova_contrasenya = $_POST['nova_contrasenya'];
            if ($this->usuari->actualitzarContrasenya()) {
                echo "<script>alert('Contrasenya actualitzada.');</script>";
                header('Location: ../public/index.php?action=perfil');
                exit();
            } else {
                echo "<script>alert('Error al actualitzar la contrasenya.');</script>";
                header('Location: ../public/index.php?action=actualitzarContrasenya');
            }
        }
    }
    
    public function veurePerfil($id) {
        $this->usuari->id_usuari = $id;
        return $this->usuari->veurePerfil();
    }
    public function logout() {
        session_destroy();
        setcookie('usuari_id', '', time() - 3600, "/");
        setcookie('usuari_nom', '', time() - 3600, "/");
        setcookie('usuari_cognoms', '', time() - 3600, "/");
        setcookie('usuari_rol', '', time() - 3600, "/");
        setcookie('usuari_email', '', time() - 3600, "/");
        setcookie('usuari_sector', '', time() - 3600, "/");
        setcookie('usuari_telefon', '', time() - 3600, "/");
        setcookie('usuari_imatge', '', time() - 3600, "/");
        header('Location: ../public/index.php?action=login');
        exit();
    }

    public function meuPerfil() {
        $this->usuari->id_usuari = $_SESSION['usuari']['id'];
        return $this->usuari->veurePerfil();
    }

    public function actualitzarPerfil(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->usuari->id_usuari = $_SESSION['usuari']['id'];
            $this->usuari->nom = $_POST['nom'];
            $this->usuari->cognoms = $_POST['cognoms'];
            $this->usuari->email = $_POST['email'];
            $this->usuari->telefono = $_POST['telefon'];
            if ($this->usuari->actualitzarPerfil()) {
                echo "<script>alert('Perfil actualitzat.');</script>";
                header('Location: ../public/index.php?action=perfil');
                exit();
            } else {
                echo "<script>alert('Error al actualitzar el perfil.');</script>";
                header('Location: ../public/index.php?action=perfil');
            }
        }
    }

    public function sendRecoveryEmail() {
        $email = $_POST['email'];
        $user = $this->usuari->verifyEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(50));
            if ($this->usuari->addToken($email, $token)) {
                $domain = $_SERVER['HTTP_HOST'];
                $resetLink = "http://$domain/incidenciesja/views/reset_password.php?token=$token";
                $subject = "Recuperar Contrasenya - IncidenciesJa!";
                $message = "Hola,\n\nPer favor, fes clic en l'enllaç següent per restablir la teva contrasenya:\n\n$resetLink\n\nSi no has sol·licitat un restabliment de contrasenya, ignora aquest correu electrònic.";
                $headers = "From: no-reply@incidenciesja.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "<script>alert('Correu electrònic de recuperació enviat.');</script>";
                    echo '<script>window.location.href = "../public/index.php";</script>';
                } else {
                    echo "<script>alert('Error en enviar el correu electrònic.');</script>";
                }
            }
        } else {
            echo "<script>alert('Correu electrònic no trobat.');</script>";
        }
        header('Location: ../views/index.php');
        exit();
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['new_password'];
            if ($this->usuari->resetPassword($token, $newPassword)) {
                echo "<script>alert('Contrasenya restablerta correctament.');</script>";
                echo '<script>window.location.href = "../public/index.php";</script>';
                exit();
            } else {
                echo "<script>alert('Error al restablir la contrasenya.');</script>";
                header('Location: ../views/reset_password.php?token=' . $token);
            }
        }
    }

    public function existeisToken($token) {
        return $this->usuari->verifyToken($token);
    }
}

if (isset($_GET['action'])) {
    $controller = new UsuariController();
    
    switch ($_GET['action']) {
        case 'login':
            $controller->login();
            break;
        case 'logout':
            $controller->logout();
            break;
        case 'actualitzarContrasenya':
            $controller->actualitzarContrasenya();
            break;
        case 'veurePerfil':
            break;
        case 'perfil':
            break;
        case 'actualitzarPerfil':
            $controller->actualitzarPerfil();
            break;
        case 'sendRecoveryEmail':
            $controller->sendRecoveryEmail();
            break;
        case 'resetPassword':
            $controller->resetPassword();
            break;
        default:
            echo "Acció no vàlida.";
            break;

    }
} else {
    echo "No s'ha especificat cap acció.";
}
