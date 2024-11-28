<?php
session_start();
include_once '../config/database.php';
include_once '../controllers/MissatgesController.php';

$controller = new MissatgesController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'enviar') {
        $emisor_id = $_POST['emisor_id'];
        $receptor_id = $_POST['receptor_id'];
        $missatge = $_POST['missatge'];
        $controller->enviarMissatge($emisor_id, $receptor_id, $missatge);
    }

    if ($action === 'carregar') {
        $emisor_id = $_POST['emisor_id'];
        $receptor_id = $_POST['receptor_id'];
        $chat_id = $controller->missatges->obtenirOcrearChat($emisor_id, $receptor_id);
        $missatges = $controller->carregarMissatges($chat_id);

        foreach ($missatges as $msg) {
            echo "<div class='missatge'>
                    <strong>{$msg['nom']}</strong> 
                    <p>{$msg['missatge']}</p>
                    <small>{$msg['data_formatada']}</small>
                </div>";
        }
    }
}
?>
