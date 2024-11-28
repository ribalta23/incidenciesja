<?php 
class Recuperar {

    private $conn;
    private $table = 'usuaris';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function existeEmail($email) {
        $query = "SELECT id_usuari FROM usuaris WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function guardarToken($email, $token) {
        $query = "UPDATE usuaris SET token = ?, token_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
    }

    public function verificarToken($token) {
        $query = "SELECT id FROM usuaris WHERE token = ? AND token_expira > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function actualizarContrasenya($token, $nuevaContrasenya) {
        $query = "UPDATE usuaris SET contrasenya = ?, token = NULL, token_expira = NULL WHERE token = ?";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($nuevaContrasenya, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $hashedPassword, $token);
        $stmt->execute();
    }
}
?>