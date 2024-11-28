<?php
    if (!defined('ACCESS_ALLOWED')) {
        header('Location: ../404.php');
        exit;
    }
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=perfil"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Actualitzar contrasenya</h2>
        </div>
    </div>
    <form action="../controllers/UsuariController.php?action=actualitzarContrasenya" method="POST">
        <div class="form_element">
            <label for="contrasenya">Contrasenya actual</label>
            <input type="password" name="contrasenya" id="contrasenya" required>
        </div>
        <div class="form_element">
            <label for="nova_contrasenya">Nova contrasenya</label>
            <input type="password" name="nova_contrasenya" id="nova_contrasenya" required>
        </div>
        <div class="form_element">
            <button type="submit">Actualitzar contrasenya</button>
        </div>
    </form>
</div>