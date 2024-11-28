<?php
    if (!defined('ACCESS_ALLOWED')) {
        header('Location: ../404.php');
        exit;
    }
    require_once '../controllers/UsuariController.php';
    $controller = new UsuariController();
    $perfil = $controller->veurePerfil($_GET['idUsuari']);
?>

<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=incidencies"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Perfil d'usuari</h2>
        </div>
    </div>
    <img src="../public/assets/profile/<?= $perfil['imatge']?>" alt="">
    <h3><?= $perfil['rol'] ?></h3>
    <form>
        <div class="form_element">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="<?= $perfil['nom'] ?>" readonly>
        </div>
        <div class="form_element">
            <label for="cognoms">Cognoms</label>
            <input type="text" name="cognoms" id="cognoms" value="<?= $perfil['cognoms'] ?>" readonly>
        </div>
        <div class="form_element">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?= $perfil['email'] ?>" readonly>
        </div>
        <?php if($perfil['rol'] != 'usuari') { ?>
            <div class="form_element">
                <label for="sector">Sector</label>
                <input type="text" name="sector" id="sector" value="<?= $perfil['sector'] ?>" readonly>
            </div>
        <?php } ?>
        <div class="form_element">
            <label for="telefon">Telefon</label>
            <input type="text" name="telefon" id="telefon" value="<?= $perfil['telefono'] ?>" readonly>
        </div>
    </form>
</div>