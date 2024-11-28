<?php
class Usuari {
    private $conn;
    private $table = 'usuaris';

    public $id_usuari;
    public $nom;
    public $cognoms;
    public $email;
    public $contrasenya;
    public $telefono;
    public $nova_contrasenya;
    public $token;
    public function __construct($db) {
        $this->conn = $db;
    }

    // public function registre() {
    //     $query = "INSERT INTO " . $this->table . " (nom, cognoms, email, contrasenya) VALUES (?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($query);

    //     if ($stmt) {
    //         $hashedPassword = password_hash($this->contrasenya, PASSWORD_DEFAULT);
    //         $stmt->bind_param("ssss", $this->nom, $this->cognoms, $this->email, $hashedPassword);
    //         return $stmt->execute();
    //     }

    //     return false;
    // }

    public function actualitzarContrasenya(){
        $query = "UPDATE " . $this->table . " SET contrasenya = ? WHERE id_usuari = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $hashedPassword = password_hash($this->nova_contrasenya, PASSWORD_DEFAULT);
            $stmt->bind_param("si", $hashedPassword, $this->id_usuari);
            return $stmt->execute();
        }
        return false;
    }
    
    public function login() {
        $query = "SELECT u.id_usuari, u.nom, u.cognoms, u.email, u.contrasenya, u.rol, u.imatge, u.telefono, ti.nom as sector FROM " . $this->table . " u
        INNER JOIN tipus_incidencia ti ON ti.id_tipus_incidencia = u.id_sector WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            $stmt->bind_param("s", $this->email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
    
            if ($user && password_verify($this->contrasenya, $user['contrasenya'])) {
                return $user;
            }
        }
    
        return false;
    }

    public function veurePerfil() {
        $query = "SELECT u.nom, u.cognoms, u.email, u.rol, u.imatge, u.telefono, ti.nom as sector FROM " . $this->table . " u
        INNER JOIN tipus_incidencia ti ON ti.id_tipus_incidencia = u.id_sector WHERE id_usuari = ?";
        $stmt = $this->conn->prepare($query);
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id_usuari);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function actualitzarPerfil(){
        $query = "UPDATE " . $this->table . " SET nom = ?, cognoms = ?, email = ?, telefono = ? WHERE id_usuari = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ssssi", $this->nom, $this->cognoms, $this->email, $this->telefono, $this->id_usuari);
            return $stmt->execute();
        }

        return false;
    }

    public function addToken($email, $token) {
        $query = "UPDATE " . $this->table . " SET token = ? WHERE email = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ss", $token, $email);
            return $stmt->execute();
        }
        return false;
    }

    public function verifyEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }

    public function verifyToken($token) {
        $query = "SELECT * FROM " . $this->table . " WHERE token = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }

    public function resetPassword($token, $newPassword) {
        $user = $this->verifyToken($token);
        if ($user) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->table . " SET contrasenya = ?, token = NULL WHERE token = ?";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("ss", $hashedPassword, $token);
                return $stmt->execute();
            }
        }
        return false;
    }
}
