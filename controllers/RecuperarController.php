<?php
include_once '../config/Database.php';
include_once '../models/Recuperar.php';

class RecuperarController {
    private $recuperar;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->recuperar = new Recuperar($db);
    }

    public function solicitarToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            // Verificar si el email está registrado en la base de datos
            if ($this->recuperar->existeEmail($email)) {
                // Generar un token único
                $token = bin2hex(random_bytes(16));

                // Guardar el token en la base de datos con una validez de tiempo
                $this->recuperar->guardarToken($email, $token);

                // Enviar el token al correo electrónico del usuario
                $this->enviarEmail($email, $token);

                // Redirigir a una página de confirmación
                header('Location: ../views/login.php');
                exit;
            } else {
                // Redirigir a la página de solicitud de token con un mensaje de error
                header('Location: ../views/solicitartoken.php?error=email_no_registrado');
                exit;
            }
        }
    }

    public function recuperarContrasenya() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $nuevaContrasenya = $_POST['new_password'];

            // Verificar si el token es válido
            if ($this->recuperar->verificarToken($token)) {
                // Actualizar la contraseña
                $this->recuperar->actualizarContrasenya($token, $nuevaContrasenya);

                // Redirigir a la página de login con un mensaje de éxito
                header('Location: ../views/login.php?success=contrasenya_actualizada');
                exit;
            } else {
                // Redirigir a la página de recuperación con un mensaje de error
                header('Location: ../views/recuperarcontrasenya.php?error=token_invalido');
                exit;
            }
        }
    }

    private function enviarEmail($email, $token) {
        $subject = "Recuperación de contraseña";
        $domain = $_SERVER['HTTP_HOST'];
        $message = "Utiliza el siguiente enlace para recuperar tu contraseña: ";
        $message .= "http://$domain/public/recuperarcontrasenya.php?token=$token";
        $headers = "From: admin@aleixribalta.cat";

        mail($email, $subject, $message, $headers);
    }
}

if (isset($_REQUEST['action'])) {
    $controller = new RecuperarController();
    
    switch ($_REQUEST['action']) {
        case 'solicitarToken':
            $controller->solicitarToken();
            break;
        case 'recuperar_contrasenya':
            $controller->recuperarContrasenya();
            break;
    }
    $controller->$action();
}