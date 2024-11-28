<?php
include_once '../controllers/GestorUsuarisController.php';
$controller = new GestorUsuarisController();
$search = isset($_GET['search']) ? $_GET['search'] : '';
$usuaris = $controller->mostrarUsuaris($search);

if (!empty($usuaris)) {
    foreach ($usuaris as $usuari) {
        $ruta_imagen = "../public/assets/profile/" . $usuari['imatge'];
        $imagen_predeterminada = "../public/assets/profile/default.png";
        $imagen_mostrar = (file_exists($ruta_imagen) && !empty($usuari['imatge'])) ? $ruta_imagen : $imagen_predeterminada;
        echo '<div class="usuaris" onclick="location.href=\'../public/index.php?action=editarUsuaris&id=' . $usuari['id_usuari'] . '\'">';
        echo '<div class="imatge">';
        echo '<img class="profile-img" src="' . $imagen_mostrar . '" alt="Usuari">';
        echo '</div>';
        echo '<div class="dreta">';
        echo '<h3>' . htmlspecialchars($usuari['nom']) . '</h3>';
        echo '<h4>' . htmlspecialchars($usuari['cognoms']) . '</h4>';
        echo '<p>' . htmlspecialchars($usuari['rol']) . '</p>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>No hi ha usuaris disponibles.</p>';
}
?>