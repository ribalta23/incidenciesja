<?php 
class Notificacions {

    private $conn;
    private $table = 'notificacions';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearNotificacio($id_incidencia, $tipus) {
        $query = "INSERT INTO " . $this->table . " (id_incidencia, tipus) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $id_incidencia, $tipus);
        return $stmt->execute();
    }

    public function obtenirNotificacions($usuari_id) {
        $query = "SELECT n.*, i.titol, i.data_creacio, i.darrera_modificacio, i.id_incidencia 
                  FROM " . $this->table . " n 
                  INNER JOIN incidencia i ON n.id_incidencia = i.id_incidencia 
                  WHERE i.id_usuari_creacio = ? OR i.id_usuari = ? 
                  ORDER BY n.data DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $usuari_id, $usuari_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function marcarComLlegida($id) {
        $query = "UPDATE " . $this->table . " SET llegida = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>