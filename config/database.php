<?php
class Database {
    private $host = 'srv966.hstgr.io';
    private $db_name = 'u505002782_incidenciesja';
    private $username = 'u505002782_administrador';
    private $password = 'uD&i8rx6XeBH8AeGrdr!eY';
    public $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>