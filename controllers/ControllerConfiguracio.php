<?php
include_once '../config/database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ConfiguracioController {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function obtenirTipusIncidencia() {
        $query = "SELECT * FROM tipus_incidencia";
        return $this->conn->query($query);
    }

    public function afegirTipusIncidencia() {
        $nom = $_POST['nom'];
        $descripcio = $_POST['descripcio'];
        $query = "INSERT INTO tipus_incidencia (nom, descripcio) VALUES ('$nom', '$descripcio')";
        return $this->conn->query($query);
    }

    public function eliminarTipus() {
        $id = $_GET['id'];
        $query = "DELETE FROM tipus_incidencia WHERE id_tipus_incidencia = $id";
        return $this->conn->query($query);
    }
    public function obtenirEspais() {
        $sql = "SELECT id, aula, pis, espai FROM espais ORDER BY pis ASC, aula ASC, espai ASC";
        return $this->conn->query($sql);
    }    

    public function afegirEspai() {
        if(isset($_POST['aula']) && isset($_POST['pis'])) {
            $aula = $_POST['aula'];
            $pis = $_POST['pis'];
            $nom = $_POST['nom'];
            $sql = "INSERT INTO espais (aula, pis, espai) VALUES ($aula, $pis, '$nom')";
            return $this->conn->query($sql);
        } else if (isset($_POST['espai'])) {
            $espai = $_POST['espai'];
            $sql = "INSERT INTO espais (espai) VALUES ('$espai')";
            return $this->conn->query($sql);
        }
    }

    public function eliminarEspai() {
        $id = $_GET['id'];
        $sql1 = "DELETE FROM incidencia WHERE espai = $id";
        $sql2 = "DELETE FROM espais WHERE id = $id";
        $this->conn->query($sql1);
        return $this->conn->query($sql2);
    }
}

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    $controller = new ConfiguracioController();
    switch ($action) {
        case 'afegirTipusIncidencia':
            $controller->afegirTipusIncidencia();
            header('Location: ../public/index.php?action=configuracio');
            break;
        case 'eliminarTipus':
            $controller->eliminarTipus();
            header('Location: ../public/index.php?action=configuracio');
            break;
        case 'afegirEspai':
            $controller->afegirEspai();
            header('Location: ../public/index.php?action=configuracio');
            break;
        case 'eliminarEspai':
            $controller->eliminarEspai();
            header('Location: ../public/index.php?action=configuracio');
            break;
    }
}