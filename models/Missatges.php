<?php
class Missatges {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function veureUsuaris($search = '') {
        $id_usuari = $_SESSION['usuari']['id'];
        $query = "SELECT * from usuaris u WHERE u.id_usuari != $id_usuari";
        if (!empty($search)) {
            $query .= " AND (u.nom LIKE ? OR u.cognoms LIKE ?)";
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

    public function veureUsuari($id) {
        $query = "SELECT * FROM usuaris u WHERE u.id_usuari = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenirOcrearChat($emisor_id, $receptor_id) {
        // Verificar si ya existe el chat
        $query = "SELECT id FROM chats WHERE (id_usuari_creacio = ? AND id_usuari_receptor = ?) OR (id_usuari_creacio = ? AND id_usuari_receptor = ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiii", $emisor_id, $receptor_id, $receptor_id, $emisor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['id'];
        }

        // Si no existe, crear el chat
        $query = "INSERT INTO chats (id_usuari_creacio, id_usuari_receptor) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $emisor_id, $receptor_id);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function enviarMissatge($emisor_id, $receptor_id, $missatge) {
        $chat_id = $this->obtenirOcrearChat($emisor_id, $receptor_id);

        $query = "INSERT INTO missatges (emisor_id, receptor_id, missatge, timestamp, id_chat) VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iisi", $emisor_id, $receptor_id, $missatge, $chat_id);
        return $stmt->execute();
    }

    public function carregarMissatges($chat_id) {
        $query = "SELECT m.*, u.nom, DATE_FORMAT(m.timestamp, '%m-%d %H:%i') AS data_formatada FROM missatges m 
        INNER JOIN usuaris u on u.id_usuari = m.emisor_id 
        WHERE id_chat = ? ORDER BY timestamp asc";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $chat_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $missatges = [];
        while ($row = $result->fetch_assoc()) {
            $missatges[] = $row;
        }
        return $missatges;
    }
}
?>
