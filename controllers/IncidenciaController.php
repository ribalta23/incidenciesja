<?php
include_once '../config/database.php';
include_once '../models/Incidencia.php';
include_once 'NotificacionsController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class IncidenciaController {
    private $conn;
    private $incidencia;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->incidencia = new Incidencia($this->conn);
    }

    // ---------- FUNCIONS DE GESTIÓ --------------
    // | 1. obtenir_totes
    public function obtenir_totes($id_usuari, $rol) {
        $this->incidencia->id_usuari = $id_usuari;
        $this->incidencia->rol = $rol;
        return $this->incidencia->obtenir_totes();
    }
    
    public function obtenir_per_id($id) {
        $this->incidencia->id_incidencia = $id;
        return $this->incidencia->obtenir_per_id();
    }

    // | 2. Crear una incidència
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->incidencia->titol = $_POST['titol'];
            $this->incidencia->descripcio = $_POST['descripcio'];
            $this->incidencia->prioritat = $_POST['prioritat'];
            $this->incidencia->estat = "pendent";
            if(isset($_POST['id_usuari'])){
                $this->incidencia->id_usuari = $_POST['id_usuari'];
            }
            $this->incidencia->id_tipo_incidencia = $_POST['id_tipo_incidencia'];
            $this->incidencia->id_usuari_creacio = $_POST['id_usuari_creacio'];
            
            if(isset($_POST['aula'])){
                $this->incidencia->id_espai = $_POST['aula'];
            } else if (isset($_POST['altres'])) {
                $this->incidencia->id_espai = $_POST['altres'];
            }
            
            $upload_names = [];
            if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] == UPLOAD_ERR_OK) {
                $upload_nom_base = strtolower(str_replace(' ', '_', $_POST['titol']));
                foreach ($_FILES['upload']['name'] as $index => $name) {
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $upload_nom = "{$upload_nom_base}_{$index}.{$extension}";
                    $upload_tmp = $_FILES['upload']['tmp_name'][$index];
                    if (move_uploaded_file($upload_tmp, "../public/assets/upload/" . $upload_nom)) {
                        $upload_names[] = $upload_nom;
                    }
                }
                $this->incidencia->upload = json_encode($upload_names);
            } else {
                $this->incidencia->upload = '';
            }
            $id_incidencia = $this->incidencia->crear();
            if ($id_incidencia) {
                $notificacioController = new NotificacionsController();
                $notificacioController->crearNotificacio($id_incidencia, 'creada');
                header('Location: ../public/index.php?action=incidencies');
                exit();
            } else {
                echo "<script>alert('Error al crear la incidencia.');</script>";
            }
        }
    }    

    // | 3. Trobar l'ID de l'espai


    public function actualitzar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->incidencia->id_incidencia = $_POST['id_incidencia'];
            $this->incidencia->titol = $_POST['titol'];
            $this->incidencia->id_tipo_incidencia = $_POST['id_tipo_incidencia'];
            $this->incidencia->id_usuari = $_POST['id_usuari'];
            $this->incidencia->prioritat = $_POST['prioritat'];
            $this->incidencia->estat = $_POST['estat'];
            $this->incidencia->descripcio = $_POST['descripcio'];

            // Fetch existing uploads
            $existing = $this->incidencia->obtenir_per_id();
            $existing_uploads = json_decode($existing['upload'], true) ?: [];

            $upload_names = [];
            if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] == UPLOAD_ERR_OK) {
                $upload_nom_base = strtolower(str_replace(' ', '_', $_POST['titol']));
                foreach ($_FILES['upload']['name'] as $index => $name) {
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $upload_nom = "{$upload_nom_base}_{$index}.{$extension}";
                    $upload_tmp = $_FILES['upload']['tmp_name'][$index];
                    if (move_uploaded_file($upload_tmp, "../public/assets/upload/" . $upload_nom)) {
                        $upload_names[] = $upload_nom;
                    }
                }
            }

            // Merge new uploads with existing ones, avoiding duplicates
            $all_uploads = array_unique(array_merge($existing_uploads, $upload_names));
            $this->incidencia->upload = json_encode($all_uploads);

            if ($this->incidencia->actualitzar()) {
                $notificacioController = new NotificacionsController();
                $notificacioController->crearNotificacio($_POST['id_incidencia'], 'modificada');
                header('Location: ../public/index.php?action=incidencies');
                exit();
            } else {
                echo "<script>alert('Error al actualitzar la incidencia.');</script>";
            }
        }
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->incidencia->id_incidencia = $_POST['id_incidencia'];

            if ($this->incidencia->eliminar()) {
                header('Location: ../public/index.php?action=incidencies');
                exit();
            } else {
                echo "<script>alert('Error al eliminar la incidencia.');</script>";
            }
        }
    }


    public function obtenir_espai($id) {
        $query = "SELECT * FROM espais WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function obtenir_tecnic($id) {
        $query = "SELECT * FROM usuaris WHERE id_usuari = '$id'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // ---------- FUNCIONS DE VISTA --------------
    public function contadorTasques($count){
        return $this->incidencia->contadorTasques($count);
    }

    public function obtenir_tipus_incidencia() {
        $query = "SELECT * FROM tipus_incidencia";
        return $this->conn->query($query);
    }
    public function obtenir_usuaris() {
        $query = "SELECT id_usuari, nom FROM usuaris WHERE rol = 'tecnic' OR rol = 'administrador'";
        return $this->conn->query($query);
    }
    public function obtenir_nom_usuari($idUsuari) {
        $query = "SELECT nom FROM usuaris WHERE id_usuari = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $idUsuari);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuari = $result->fetch_assoc();
        return $usuari ? $usuari['nom'] : null;
    }

    public function filtrar_incidencies() {
        $filters = [];
        if (!empty($_POST['usuari'])) {
            $filters['usuari'] = $_POST['usuari'];
        }
        if (!empty($_POST['tipus'])) {
            $filters['tipus'] = $_POST['tipus'];
        }
        if (!empty($_POST['prioritat'])) {
            $filters['prioritat'] = $_POST['prioritat'];
        }
        if (!empty($_POST['estat'])) {
            $filters['estat'] = $_POST['estat'];
        }
        if (!empty($_POST['data'])) {
            $filters['data'] = $_POST['data'];
        }
        if (!empty($_POST['aula'])){
            $filters['espai'] = $_POST['aula'];
        }
        if (!empty($_POST['altres'])){
            $filters['espai'] = $_POST['altres'];
        }
        return $this->incidencia->obtenir_incidencies_filtrades($filters);
    }
    public function filtrar_incidencies_usuari() {
       
        $filters = [];
        $filters['usuari'] = $_SESSION['usuari']['id']; 
    
        if (!empty($_POST['tipus'])) {
            $filters['tipus'] = $_POST['tipus'];
        }
        if (!empty($_POST['prioritat'])) {
            $filters['prioritat'] = $_POST['prioritat'];
        }
        if (!empty($_POST['estat'])) {
            $filters['estat'] = $_POST['estat'];
        }
        if (!empty($_POST['data'])) {
            $filters['data'] = $_POST['data'];
        }
        return $this->incidencia->obtenir_incidencies_filtrades_usuari($filters);
    }

    public function obtenir_pis() {
        $query = "SELECT DISTINCT pis FROM espais WHERE pis IS NOT NULL";
        return $this->conn->query($query);
    }
    
    public function obtenir_aules($pisId) {
        $query = "SELECT id, aula, espai FROM espais WHERE pis = '$pisId'";
        return $this->conn->query($query);
    }

    public function obtenir_tecnics($idTipusIncidencia) {
        $query = "SELECT u.id_usuari, u.nom, u.rol FROM usuaris u
        WHERE u.id_usuari IN (
            SELECT s.id_usuari FROM usuaris_in_sector s
            WHERE s.id_tipus = ?
        ) AND (u.rol = 'tecnic' OR u.rol = 'administrador')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $idTipusIncidencia);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenir_altres() {
        $query = "SELECT id, espai FROM espais WHERE aula IS NULL AND pis IS NULL";
        return $this->conn->query($query);
    }

    public function obtenirEsdeveniments() {
        $incidencias = $this->incidencia->obtenir_totes();
        $eventos = [];
    
        while ($row = $incidencias->fetch_assoc()) {
            $eventos[] = [
                'id' => $row['id_incidencia'],
                'prioritat' => $row['prioritat'],
                'title' => $row['titol'],
                'start' => $row['data_creacio']
            ];
        }
    
        header('Content-Type: application/json');
        echo json_encode($eventos);
    }
}

// ---------- CRIDES ----------

if (isset($_REQUEST['action'])) {
    $controller = new IncidenciaController();

    switch ($_REQUEST['action']) {
        // ---------- CRIDES INICIALS ----------
        case 'incidencies':
            $incidencies = $controller->obtenir_totes($_SESSION['usuari']['id'], $_SESSION['usuari']['rol']);
            break;
        case 'filtres':
            $incidencies = $controller->filtrar_incidencies();
            break;
        case 'filtres_usuari':
            $incidencies = $controller->filtrar_incidencies_usuari();
            break;

        // ---------- CRIDES DE FORMULARIS ----------
        case 'crearIncidencia':
            break;
        case 'veureIncidencia':
            break;
        case 'crearTasca':
            break;
        case 'visualitzarIncidencia':
            break;
        
        // ---------- CRIDES DE GESTIÓ ----------
        case 'crear':
            $controller->crear();
            break;
        case 'actualitzar':
            $controller->actualitzar();
            break;
        case 'eliminar':
            $controller->eliminar();
            break;
        
        // ---------- CRIDES AJAX ----------
        case 'select_pis':
            $pis = $controller->obtenir_pis();
            $result = [];
            while ($row = $pis->fetch_assoc()) {
                $result[] = $row;
            }
            echo json_encode($result);
            break;
        case 'obtenir_aules':
            $pisId = $_GET['pisId'];
            $aules = $controller->obtenir_aules($pisId);
            $result = [];
            while ($row = $aules->fetch_assoc()) {
                $result[] = $row;
            }
            echo json_encode($result);
            break;
        case 'obtenir_tecnics':
            $idTipusIncidencia = $_GET['id_tipus_incidencia'];
            $tecnics = $controller->obtenir_tecnics($idTipusIncidencia);
            $result = [];
            while ($row = $tecnics->fetch_assoc()) {
                $result[] = $row;
            }
            echo json_encode($result);
            break;
        case 'obtenir_altres':
            $altres = $controller->obtenir_altres();
            $result = [];
            while ($row = $altres->fetch_assoc()) {
                $result[] = $row;
            }
            echo json_encode($result);
            break;
        case 'obtenirEsdeveniments':
            $controller->obtenirEsdeveniments();
            break;
        case 'dashboardAdmin':

            break;
        // ---------- ACCIÓ NO RECONEGUDA ----------
        default:
        $accion_no_reconeguda = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'desconeguda';
        echo "<script>alert('Acció no reconeguda: $accion_no_reconeguda');</script>";
        break;
    }
}
