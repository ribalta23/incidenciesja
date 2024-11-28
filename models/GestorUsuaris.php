<?php 
class GestorUsuaris {

    private $conn;
    private $table = 'usuaris';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function mostrarUsuaris($search = '') {
        $query = "SELECT u.*, ti.nom AS tipus_nom, ti.id_tipus_incidencia
                  FROM usuaris u 
                  LEFT JOIN tipus_incidencia ti ON u.id_sector = ti.id_tipus_incidencia";
        if (!empty($search)) {
            $query .= " WHERE u.nom LIKE ? OR u.cognoms LIKE ?";
        }
        $stmt = $this->conn->prepare($query);
        if (!empty($search)) {
            $search_param = '%' . $search . '%';
            $stmt->bind_param("ss", $search_param, $search_param);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $usuaris = array();

        while ($row = $result->fetch_assoc()) {
            $usuaris[] = $row;
        }

        return $usuaris;
    }


    public function insertarUsuari($nom, $cognoms, $email, $rol, $imatge, $password_hash, $tipus_id, $telefono) {
        $stmt = $this->conn->prepare("INSERT INTO usuaris (nom, cognoms, email, rol, imatge, contrasenya, id_sector, telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssis", $nom, $cognoms, $email, $rol, $imatge, $password_hash, $tipus_id, $telefono);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function obtenirRol() {
        $result = $this->conn->query("SHOW COLUMNS FROM usuaris LIKE 'rol'");
        $row = $result->fetch_assoc();
        $enum = $row['Type'];
        preg_match("/^enum\((.*)\)$/", $enum, $matches);
        $enum = str_getcsv($matches[1], ',', "'");
        $enum = array_filter($enum, function($value) { return !empty($value); }); // Filtrar valores vacíos
        return $enum;
    }
    public function obtenirTipus() {
        $result = $this->conn->query("SELECT id_tipus_incidencia, nom FROM tipus_incidencia");
        $tipus = array();
        while ($row = $result->fetch_assoc()) {
            $tipus[] = $row;
        }
        return $tipus;
    }

    public function obtenirUsuari($id) {
        $stmt = $this->conn->prepare("SELECT u.*, ti.nom AS tipus_nom FROM usuaris u LEFT JOIN tipus_incidencia ti ON u.id_sector = ti.id_tipus_incidencia WHERE u.id_usuari = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualitzarUsuari($id, $cambios) {
        $set_clause = [];
        $params = [];
        $types = '';
    
        foreach ($cambios as $campo => $valor) {
            $set_clause[] = "$campo = ?";
            $params[] = $valor;
    
            
            switch ($campo) {
                case 'id_usuari':
                case 'telefon':
                case 'id_sector':
                    $types .= 'i'; 
                    break;
                case 'nom':
                case 'cognoms':
                case 'email':
                case 'rol':
                case 'imatge':
                case 'direccion':
                case 'contrasenya':
                    $types .= 's';
                    break;
                default:
                    $types .= 's';
                    break;
            }
        }
    
        $params[] = $id;
        $types .= 'i'; 
    
        $set_clause = implode(', ', $set_clause);
        $stmt = $this->conn->prepare("UPDATE usuaris SET $set_clause WHERE id_usuari = ?");
        $stmt->bind_param($types, ...$params);
    
        return $stmt->execute();
    }
    
    public function obtenirContrasenyaActual($id) {
        $stmt = $this->conn->prepare("SELECT contrasenya FROM usuaris WHERE id_usuari = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['password'];
    }
    
    public function eliminarUsuari($id) {
        
        $stmt = $this->conn->prepare("SELECT imatge FROM usuaris WHERE id_usuari = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $imatge = $result['imatge'];

   
        $stmt = $this->conn->prepare("DELETE FROM usuaris WHERE id_usuari = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();

        
        if ($success && $imatge !== 'default.png') {
            $ruta_imagen = "../public/assets/profile/" . $imatge;
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }

        return $success;
    }
    public function obtenirCorreu($email) {
        
            $stmt = $this->conn->prepare("SELECT email FROM usuaris WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        
    }
}
