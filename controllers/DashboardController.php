<?php
include_once '../config/database.php';
include_once '../models/Incidencia.php';
include_once '../models/Usuari.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DashboardController {
    private $conn;
    private $incidencia;
    private $usuari;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->incidencia = new Incidencia($this->conn);
        $this->usuari = new Usuari($this->conn);
    }

    public function totalIncidencies() {
        $query = "SELECT count(id_incidencia) FROM incidencia where id_usuari = ".$_SESSION['usuari']['id'];
        return $this->conn->query($query);
    }

    public function incidenciesPendents() {
        $query = "SELECT count(id_incidencia) FROM incidencia where id_usuari = ".$_SESSION['usuari']['id']." and estat = 'pendent'";
        return $this->conn->query($query);
    }

    public function incidenciesEnProces() {
        $query = "SELECT count(id_incidencia) FROM incidencia where id_usuari = ".$_SESSION['usuari']['id']." and estat = 'enproces'";
        return $this->conn->query($query);
    }

    public function incidenciesResoltes() {
        $query = "SELECT count(id_incidencia) FROM incidencia where id_usuari = ".$_SESSION['usuari']['id']." and estat = 'resolta'";
        return $this->conn->query($query);
    }

    public function incideciesAltes() {
        $query = "SELECT count(id_incidencia) FROM incidencia where id_usuari = ".$_SESSION['usuari']['id']." and prioritat = 'alta'";
        return $this->conn->query($query);
    }

    public function incideciesModerades() {
        $query = "SELECT count(id_incidencia) FROM incidencia where id_usuari = ".$_SESSION['usuari']['id']." and prioritat = 'mitjana'";
        return $this->conn->query($query);
    }

    public function incideciesBaixes() {
        $query = "SELECT count(id_incidencia) FROM incidencia where id_usuari = ".$_SESSION['usuari']['id']." and prioritat = 'baixa'";
        return $this->conn->query($query);
    }

    public function ultimesIncidencies() {
        $query = "SELECT i.*, ti.nom as tipus_incidencia, u.nom as nom_usuari_supervisor FROM incidencia i INNER JOIN tipus_incidencia ti ON i.id_tipus_incidencia = ti.id_tipus_incidencia INNER JOIN usuaris u ON i.id_usuari = u.id_usuari
        where i.id_usuari = ".$_SESSION['usuari']['id']."  
        order by data_creacio desc limit 6";
        return $this->conn->query($query);
    }
    
    public function obtenirEstadistiques($periodo, $usuariId = null) {
        $whereClause = "";
        if ($periodo === 'mes') {
            $whereClause = "WHERE MONTH(data_creacio) = MONTH(CURRENT_DATE()) AND YEAR(data_creacio) = YEAR(CURRENT_DATE())";
        } else if ($periodo === 'any') {
            $whereClause = "WHERE YEAR(data_creacio) = YEAR(CURRENT_DATE())";
        }

        if ($usuariId) {
            $whereClause .= $whereClause ? " AND " : "WHERE ";
            $whereClause .= "id_usuari = $usuariId";
        }

        $queryTotals = "SELECT COUNT(id_incidencia) AS total, prioritat 
                        FROM incidencia 
                        $whereClause 
                        GROUP BY prioritat";

        $queryPerEstat = "SELECT COUNT(id_incidencia) AS total, estat 
                          FROM incidencia 
                          $whereClause 
                          GROUP BY estat";

        $totalsResult = $this->conn->query($queryTotals);
        $perEstatResult = $this->conn->query($queryPerEstat);

        return [
            'totals' => $this->formatData($totalsResult),
            'perEstat' => $this->formatDataPerEstat($perEstatResult)
        ];
    }

    private function formatData($result) {
        $data = ['alta' => 0, 'mitjana' => 0, 'baixa' => 0];
        while ($row = $result->fetch_assoc()) {
            $data[$row['prioritat']] = (int)$row['total'];
        }
        return array_values($data);
    }

    private function formatDataPerEstat($result) {
        $data = ['pendent' => 0, 'enproces' => 0, 'tancada' => 0];
        while ($row = $result->fetch_assoc()) {
            $data[$row['estat']] = (int)$row['total'];
        }
        return array_values($data);
    }

    public function obtenir_usuaris() {
        $query = "SELECT id_usuari, nom FROM usuaris";
        return $this->conn->query($query);
    }
    public function ultimesIncidenciesTotes(){
        $query = "SELECT i.*, ti.nom as tipus_incidencia, u.nom as nom_usuari_supervisor FROM incidencia i INNER JOIN tipus_incidencia ti ON i.id_tipus_incidencia = ti.id_tipus_incidencia INNER JOIN usuaris u ON i.id_usuari = u.id_usuari
        order by data_creacio desc limit 3";
        return $this->conn->query($query);

    }
    public function obtenir_nom_usuari($id_usuari){
        $query = "SELECT nom FROM usuaris WHERE id_usuari = $id_usuari";
        return $this->conn->query($query);
    }
}

if (isset($_GET['dades']) && isset($_GET['usuari'])) {
    $dades = $_GET['dades'];
    $usuari = $_GET['usuari'];
    $dashboard = new DashboardController();
    $estadistiques = $dashboard->obtenirEstadistiques($dades, $usuari);
    echo json_encode($estadistiques);
    exit;
}