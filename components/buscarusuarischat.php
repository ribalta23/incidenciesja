<?php
include_once '../controllers/MissatgesController.php';
$controller = new MissatgesController();
$search = isset($_GET['search']) ? $_GET['search'] : '';
$usuaris = $controller->veureUsuaris($search);

if (!empty($usuaris)) {
    foreach ($usuaris as $usuari) {
            echo '<a class="a_user" href="">';
            echo '<div class="user">';
            echo '<img src="../public/assets/profile/'.$usuari['imatge'].'" alt="imatge_usuari" class="user-img">';
            echo '<p>'.$usuari['nom'].' '. $usuari['cognoms'].'</p>';
            echo '</div>';
            echo '</a>';
    }
} else {
    echo '<p>No hi ha usuaris disponibles.</p>';
}
?>