<?php
include_once '../config/database.php';
include_once '../models/Missatges.php';

class MissatgesController {
    private $conn;
    public $missatges;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->missatges = new Missatges($this->conn);
    }

    public function veureUsuaris($search = '') {
        return $this->missatges->veureUsuaris($search);
    }

    public function veureUsuari($id) {
        return $this->missatges->veureUsuari($id);
    }

    public function enviarMissatge($emisor_id, $receptor_id, $missatge) {
        return $this->missatges->enviarMissatge($emisor_id, $receptor_id, $missatge);
    }

    public function carregarMissatges($chat_id) {
        return $this->missatges->carregarMissatges($chat_id);
    }
}
?>
