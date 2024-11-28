<?php
class Incidencia {
    private $conn;
    private $table = 'incidencia';

    public $id_incidencia;
    public $titol;
    public $descripcio;
    public $prioritat;
    public $data_creacio;
    public $estat;
    public $rol;
    public $id_usuari;
    public $id_tipo_incidencia;
    public $id_usuari_creacio;
    public $upload;
    public $id_espai;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . " (titol, descripcio, prioritat, estat, data_creacio, id_usuari, id_tipus_incidencia, id_usuari_creacio, upload, espai) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssssssss", $this->titol, $this->descripcio, $this->prioritat, $this->estat, $this->id_usuari, $this->id_tipo_incidencia, $this->id_usuari_creacio, $this->upload, $this->id_espai);
        if ($stmt->execute()) {
            return $this->conn->insert_id; 
        } else {
            return false;
        }
    
    }

    public function actualitzar() {
        // Fetch existing data
        $existing = $this->obtenir_per_id();

        // Append new uploads to existing ones
        $existing_uploads = json_decode($existing['upload'], true) ?: [];
        $new_uploads = json_decode($this->upload, true) ?: [];
        $all_uploads = array_unique(array_merge($existing_uploads, $new_uploads));
        $this->upload = json_encode($all_uploads);

        // Check if there are any changes
        if ($existing['titol'] == $this->titol &&
            $existing['descripcio'] == $this->descripcio &&
            $existing['prioritat'] == $this->prioritat &&
            $existing['estat'] == $this->estat &&
            $existing['id_usuari'] == $this->id_usuari &&
            $existing['id_tipo_incidencia'] == $this->id_tipo_incidencia &&
            $existing['upload'] == $this->upload) {
            return true; // No changes, return true
        }

        if($this->estat == 'resolta'){
            $query = "UPDATE " . $this->table . " SET titol = ?, descripcio = ?, prioritat = ?, estat = ?, id_usuari = ?, id_tipus_incidencia = ?, id_usuari = ?, upload = ?, darrera_modificacio = NOW() WHERE id_incidencia = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssssssi", $this->titol, $this->descripcio, $this->prioritat, $this->estat, $this->id_usuari, $this->id_tipo_incidencia, $this->id_usuari, $this->upload,$this->id_incidencia);
            return $stmt->execute();
        } else {
            $query = "UPDATE " . $this->table . " SET titol = ?, descripcio = ?, prioritat = ?, estat = ?, id_usuari = ?, id_tipus_incidencia = ?, upload = ?,id_usuari = ?, darrera_modificacio = NOW() WHERE id_incidencia = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssssssi", $this->titol, $this->descripcio, $this->prioritat, $this->estat, $this->id_usuari, $this->id_tipo_incidencia, $this->upload, $this->id_usuari, $this->id_incidencia);
            return $stmt->execute();
        }
    }

    public function obtenir_totes() {
        if($this->rol == 'usuari'){
            $query = "SELECT i.*, ti.nom as tipus_incidencia,
                            IFNULL(u.nom, 'No hi ha usuari assignat') as nom_usuari_asignat,
                            e.espai as nom_espai
                    FROM " . $this->table . " i 
                    INNER JOIN tipus_incidencia ti ON i.id_tipus_incidencia = ti.id_tipus_incidencia 
                    LEFT JOIN usuaris u ON i.id_usuari = u.id_usuari
                    INNER JOIN espais e ON e.id = i.espai
                    WHERE i.id_usuari_creacio = ? 
                        AND (i.estat = 'pendent' OR i.estat = 'enproces')
                    ORDER BY i.data_creacio DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $this->id_usuari);
            $stmt->execute();
            return $stmt->get_result();
        } else if($this->rol == 'tecnic'){
            $query = "SELECT i.*, ti.nom as tipus_incidencia, 
                            IFNULL(u.nom, 'No hi ha usuari assignat') as nom_usuari_asignat,
                            e.espai as nom_espai
                    FROM " . $this->table . " i 
                    INNER JOIN tipus_incidencia ti ON i.id_tipus_incidencia = ti.id_tipus_incidencia 
                    LEFT JOIN usuaris u ON i.id_usuari = u.id_usuari
                    INNER JOIN espais e ON e.id = i.espai
                    WHERE i.id_usuari = ? 
                        AND (i.estat = 'pendent' OR i.estat = 'enproces')
                    ORDER BY i.data_creacio DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $this->id_usuari);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $query = "SELECT i.*, ti.nom as tipus_incidencia, 
                            IFNULL(u.nom, 'No hi ha usuari assignat') as nom_usuari_asignat,
                            e.espai as nom_espai
                    FROM " . $this->table . " i 
                    INNER JOIN tipus_incidencia ti ON i.id_tipus_incidencia = ti.id_tipus_incidencia 
                    LEFT JOIN usuaris u ON i.id_usuari = u.id_usuari
                    INNER JOIN espais e ON e.id = i.espai
                    WHERE i.estat = 'pendent' OR i.estat = 'enproces'
                    ORDER BY i.data_creacio DESC";
            return $this->conn->query($query);
        }
    }

    public function contadorTasques(&$count) {
        $query = "SELECT COUNT(*) FROM tasques WHERE id_incidencia = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id_incidencia);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }

    public function eliminar() {
        // Fetch existing data to get the uploads
        $existing = $this->obtenir_per_id();
        $uploads = json_decode($existing['upload'], true) ?: [];

        // Delete the record from the database
        $query = "DELETE FROM " . $this->table . " WHERE id_incidencia = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id_incidencia);
        $result = $stmt->execute();

        // If the record was deleted, delete the associated files
        if ($result) {
            foreach ($uploads as $upload) {
                $file_path = "../public/assets/upload/" . $upload;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }

        return $result;
    }

    public function obtenir_per_id() {
        $query = "SELECT i.*, 
                        IFNULL(u.nom, 'No hi ha usuari assignat') as NomAsignat, 
                        uc.nom as NomCreacio,
                        e.espai as nom_espai
                FROM " . $this->table . " i
                LEFT JOIN usuaris u ON u.id_usuari = i.id_usuari
                INNER JOIN usuaris uc ON uc.id_usuari = i.id_usuari_creacio
                INNER JOIN espais e ON e.id = i.espai
                WHERE i.id_incidencia = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id_incidencia);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenir_tasca_per_id() {
        $query = "SELECT * FROM tasques WHERE id_incidencia = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id_incidencia);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenir_incidencies_filtrades($filters) {
        $query = "SELECT i.*, ti.nom as tipus_incidencia, IFNULL(u.nom, 'No hi ha usuari assignat') as nom_usuari_asignat,
                        e.espai as nom_espai
                        FROM " . $this->table . " i 
                        INNER JOIN tipus_incidencia ti ON i.id_tipus_incidencia = ti.id_tipus_incidencia 
                        LEFT JOIN usuaris u ON i.id_usuari = u.id_usuari 
                        INNER JOIN espais e ON e.id = i.espai
                        WHERE 1=1";
        
        if (isset($filters['usuari'])) {
            $query .= " AND i.id_usuari = " . $filters['usuari'];
        }
        if (isset($filters['tipus'])) {
            $query .= " AND i.id_tipus_incidencia = " . $filters['tipus'];
        }
        if (isset($filters['prioritat'])) {
            $query .= " AND i.prioritat = '" . $filters['prioritat'] . "'";
        }
        if (isset($filters['estat'])) {
            $query .= " AND i.estat = '" . $filters['estat'] . "'";
        }
        if (isset($filters['data'])) {
            $query .= " AND DATE(i.data_creacio) = '" . $filters['data'] . "'";
        }
        if (isset($filters['espai'])) {
            $query .= " AND i.espai = " . $filters['espai'];
        }
        $query .= " ORDER BY data_creacio DESC";
        return $this->conn->query($query);
    }
    
public function obtenir_incidencies_filtrades_usuari($filters) {
    $query = "SELECT i.*, ti.nom as tipus_incidencia, 
                IFNULL(u.nom, 'No hi ha usuari assignat') as nom_usuari_asignat,
                e.espai as nom_espai
                FROM " . $this->table . " i 
                INNER JOIN tipus_incidencia ti ON i.id_tipus_incidencia = ti.id_tipus_incidencia 
                LEFT JOIN usuaris u ON i.id_usuari = u.id_usuari 
                INNER JOIN espais e ON e.id = i.espai 
                WHERE 1=1";
    
    if (isset($filters['usuari'])) {
        $query .= " AND i.id_usuari = " . $filters['usuari'];
    }
    if (isset($filters['tipus'])) {
        $query .= " AND i.id_tipus_incidencia = " . $filters['tipus'];
    }
    if (isset($filters['prioritat'])) {
        $query .= " AND i.prioritat = '" . $filters['prioritat'] . "'";
    }
    if (isset($filters['estat'])) {
        $query .= " AND i.estat = '" . $filters['estat'] . "'";
    }
    if (isset($filters['data'])) {
        $query .= " AND DATE(i.data_creacio) = '" . $filters['data'] . "'";
    }
    $query .= " ORDER BY data_creacio DESC";
    return $this->conn->query($query);
}
    
    
}
