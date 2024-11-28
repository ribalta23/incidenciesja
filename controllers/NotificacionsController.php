<?php
include_once '../config/database.php';
include_once '../models/Notificacions.php';

class NotificacionsController {
    private $notificacio;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->notificacio = new Notificacions($db);
    }

    public function crearNotificacio($id_incidencia, $tipus) {
        return $this->notificacio->crearNotificacio($id_incidencia, $tipus);
    }

    public function obtenirNotificacions($usuari_id) {
        return $this->notificacio->obtenirNotificacions($usuari_id);
    }

    public function marcarComLlegida($id) {
        return $this->notificacio->marcarComLlegida($id);
    }
}

if (isset($_REQUEST['action'])) {
    $controller = new NotificacionsController();
    switch ($_REQUEST['action']) {
        case 'marcarComLlegida':
            $id = $_GET['id'];
            $result = $controller->marcarComLlegida($id);
            echo json_encode(['success' => $result]);
            break;
    }
}
?>