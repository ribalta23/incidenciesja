<?php
    if (!defined('ACCESS_ALLOWED')) {
        header('Location: ../404.php');
        exit;
    }

    include_once '../controllers/UsuariController.php';
    $controller = new UsuariController();
    $perfil = $controller->meuPerfil();
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=dashboard"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Perfil</h2>
        </div>
    </div>
    <img src="../public/assets/profile/<?= $perfil['imatge']?>" alt="">
    <h3><?= $_SESSION['usuari']['rol'] ?></h3>
    <form action="../controllers/UsuariController.php?action=actualitzarPerfil" method="post">
        <div class="form_element">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="<?= $perfil['nom'] ?>">
        </div>
        <div class="form_element">
            <label for="cognoms">Cognoms</label>
            <input type="text" name="cognoms" id="cognoms" value="<?= $perfil['cognoms'] ?>">
        </div>
        <div class="form_element">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?= $perfil['email'] ?>">
        </div>
        <!-- <?php if($_SESSION['usuari']['rol'] != 'usuari') { ?>
            <div class="form_element">
                <label for="sector">Sector</label>
                <input type="text" name="sector" id="sector" value="<?= $_SESSION['usuari']['sector'] ?>" readonly>
            </div>
        <?php } ?> -->
        <div class="form_element">
            <label for="telefon">Telefon</label>
            <input type="text" name="telefon" id="telefon" value="<?= $perfil['telefono'] ?>">
        </div>
        <div class="form_element">
            <button type="submit">Actualitzar perfil</button>
        </div>
    </form>
    <form action="../public/index.php">
        <div class="form_element">
            <button type="submit" name="action" value="actualitzarContrasenya">Actualitzar contrasenya</button>
        </div>
    </form>
</div>