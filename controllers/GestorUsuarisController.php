<?php

if(!isset($_SESSION)){
    session_start();
}


include_once '../config/database.php';
include_once '../models/GestorUsuaris.php';

class GestorUsuarisController{
    private $conn;
    private $model;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->model = new GestorUsuaris($this->conn);
        
    }

    public function mostrarUsuaris($search = '') {
        return $this->model->mostrarUsuaris($search);
    }
    public function buscarUsuaris() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $usuaris = $this->model->mostrarUsuaris($search);
        foreach ($usuaris as $usuari) {
            $ruta_imagen = "../public/assets/profile/" . $usuari['imatge'];
            $imagen_predeterminada = "../public/assets/profile/default.png";
            $imagen_mostrar = (file_exists($ruta_imagen) && !empty($usuari['imatge'])) ? $ruta_imagen : $imagen_predeterminada;
            echo '<div class="usuaris" onclick="location.href=\'../public/index.php?action=editarUsuaris&id=' . $usuari['id_usuari'] . '\'">';
            echo '<div class="imatge">';
            echo '<img class="profile-img" src="' . $imagen_mostrar . '" alt="Usuari">';
            echo '</div>';
            echo '<div class="dreta">';
            echo '<h3>' . htmlspecialchars($usuari['nom']) . '</h3>';
            echo '<h4>' . htmlspecialchars($usuari['cognoms']) . '</h4>';
            echo '<p>' . htmlspecialchars($usuari['rol']) . '</p>';
            echo '</div>';
            echo '</div>';
        }
    }
    public function afegirUsuaris() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = $_POST['nom'];
            $cognoms = $_POST['cognoms'];
            $email = $_POST['email'];
            $rol = $_POST['rol'];

            $password = $_POST['password'];
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $telefono = $_POST['telefon'];
            $tipus_id = $_POST['tipus'];

            if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] == UPLOAD_ERR_OK) {
                $imatge_nom = strtolower(str_replace(' ', '_', $nom . '_' . $cognoms));
                $imatge_tmp = $_FILES['imatge']['tmp_name'];

                
                $imatge_webp = $imatge_nom . '.webp';
                $this->convertirAWebP($imatge_tmp, '../public/assets/profile/' . $imatge_webp);

                $imatge = $imatge_webp;
            } else {
                
                $imatge = 'default.png';
            }

            try {
               
                if ($this->model->insertarUsuari($nom, $cognoms, $email, $rol, $imatge, $password_hash, $tipus_id, $telefono)) {
                    $this->enviarCorreu($nom, $email, $password,$congoms);
                    header('Location: ../public/index.php?action=mostrarUsuaris');
                    exit();
                } else {
                    echo "<script>alert('Error al crear el usuario.');</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('" . $e->getMessage() . "');</script>";
            }
        }
    }

    private function convertirAWebP($origen, $destino) {
        if (!extension_loaded('gd')) {
            throw new Exception('La extensión GD no está habilitada');
        }

        $info = getimagesize($origen);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($origen);
                break;
            case 'image/png':
                $image = imagecreatefrompng($origen);
                // Convertir a imagen true color si es una imagen con paleta de colores
                if (imageistruecolor($image) === false) {
                    $trueColorImage = imagecreatetruecolor(imagesx($image), imagesy($image));
                    imagecopy($trueColorImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    imagedestroy($image);
                    $image = $trueColorImage;
                }
                break;
            case 'image/gif':
                $image = imagecreatefromgif($origen);
                break;
            default:
                throw new Exception('Formato de imagen no soportado');
        }

        imagewebp($image, $destino, 80); 
        imagedestroy($image);
    }

    public function obtenirRol() {
        return $this->model->obtenirRol();
    }
    public function obtenirTipus() {
        return $this->model->obtenirTipus();
    }
    public function obtenirUsuari($id) {
        return $this->model->obtenirUsuari($id);
    }

    public function editarUsuaris() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $nom = $_POST['nom'];
            $cognoms = $_POST['cognoms'];
            $email = $_POST['email'];
            $rol = $_POST['rol'];
            $password = $_POST['password'];
            $imatge = $_POST['imatge_actual'];
            $telefono = (int)$_POST['telefono'];
         
            $tipus_id = $_POST['tipus'];
    
        
            if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] === UPLOAD_ERR_OK) {
                $imatge_nom = strtolower(str_replace(' ', '_', $nom . '_' . $cognoms));
                $imatge_tmp = $_FILES['imatge']['tmp_name'];
            
                
                $imatge_webp = $imatge_nom . '.webp';
            
                try {
                    
                    $this->convertirAWebP($imatge_tmp, '../public/assets/profile/' . $imatge_webp);
                    $imatge = $imatge_webp;
            
                   
                    if (!empty($_POST['imatge_actual']) && $_POST['imatge_actual'] !== 'default.png') {
                        $ruta_imagen_anterior = '../public/assets/profile/' . $_POST['imatge_actual'];
                        if (file_exists($ruta_imagen_anterior)) {
                            unlink($ruta_imagen_anterior);
                        }
                    }
                } catch (Exception $e) {
                    echo "<script>alert('" . $e->getMessage() . "');</script>";
                }
            }
            
    
            
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $password_hash = $this->model->obtenirContrasenyaActual($id);
            }
    
            
            $cambios = [];
            if ($nom !== $usuari_actual['nom']) $cambios['nom'] = $nom;
            if ($cognoms !== $usuari_actual['cognoms']) $cambios['cognoms'] = $cognoms;
            if ($email !== $usuari_actual['email']) $cambios['email'] = $email;
            if ($rol !== $usuari_actual['rol']) $cambios['rol'] = $rol;
            if ($imatge !== $usuari_actual['imatge']) $cambios['imatge'] = $imatge;
            if ($password_hash !== $usuari_actual['contrasenya']) $cambios['contrasenya'] = $password_hash;
            if ($telefono !== $usuari_actual['telefono']) $cambios['telefono'] = $telefono;
           
            if ($tipus_id !== $usuari_actual['id_sector']) $cambios['id_sector'] = $tipus_id;
    
            
            if (!empty($cambios)) {
                if ($this->model->actualitzarUsuari($id, $cambios)) {
                    header('Location: ../public/index.php?action=mostrarUsuaris');
                    exit();
                } else {
                    echo "<script>alert('Error al actualizar el usuario.');</script>";
                }
            } else {
                
                header('Location: ../public/index.php?action=mostrarUsuaris');
                exit();
            }
        }
    }

    public function eliminarUsuari() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($this->model->eliminarUsuari($id)) {
                header('Location: ../public/index.php?action=mostrarUsuaris');
                exit();
            } else {
                echo "<script>alert('Error al eliminar el usuario.');</script>";
            }
        } else {
            echo "<script>alert('ID de usuario no proporcionado.');</script>";
        }
    }
    public function nouXat() {
        
        $this->mostrarUsuaris();
    }
    public function verificarCorreu() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['email'])) {
            $email = $_GET['email'];
            $existe = $this->model->obtenirCorreu($email);
            echo json_encode(['existe' => $existe]);
        }
    }
    public function enviarCorreu($nom, $email, $password,$cognoms) {
        
        
            $domain = $_SERVER['HTTP_HOST'];
            $resetLink = "http://$domain/incidenciesja/public/index.php?action=veureIncidencia&idIncidencia=$id_incidencia";
            $subject = "Usuari creat - IncidenciesJa!";
            $message = "
            <html>
            <head>
                <title>Usuari creat a IncidenciesJa</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                    }
                    .container {
                        width: 80%;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        background-color: #f9f9f9;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .header img {
                        max-width: 150px;
                        height: auto;
                    }
                    .content {
                        text-align: left;
                    }
                    .content p {
                        margin-bottom: 20px;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 20px;
                        font-size: 0.9em;
                        color: #777;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <img src='http://$domain/incidenciesja/public/assets/brand/logo_tot_black.png' alt='Imatge de recuperació'>
                    </div>
                    <div class='content'>
                        <p>Hola <strong>$nom $cognoms</strong>,</p>
                        <p>S'et a creat un usuari a incidenciesJa.</p>
                        <p>Les teves dades d'accés són:</p>
                        <p>Usuari: $email</p>
                        <p>Contrasenya: $password</p>
                        
                        <p>Gràcies</p>
                    </div>
                    <div class='footer'>
                        <p>IncidenciesJa! - Tots els drets reservats</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@incidenciesja.com" . "\r\n";
            mail($email, $subject, $message, $headers);
        
        
        
    }




}
if (isset($_REQUEST['action'])) {
    $controller = new GestorUsuarisController();
    $action = $_REQUEST['action'];
    $controller->$action();
}