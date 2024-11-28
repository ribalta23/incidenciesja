<?php
include '../controllers/ControladorPermisos.php';
$permis = new ControladorPermisos();
?>
<header class="header">
    <nav>
        <a class="img_logo" href="../public/index.php">
            <img src="../public/assets/brand/logo_simbol_white.png" alt="logo">
        </a>
        <label class="burger" for="btn_desplegable_menu">
            <input type="checkbox" id="btn_desplegable_menu">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </nav>
    <div class="desplegable" id="desplegable_menu">
        <div class="user_info">
            <div>
                <img src="../public/assets/profile/<?= $_SESSION['usuari']['imatge'] ?>" alt="user">
                <span>
                    <h3><?= $_SESSION['usuari']['nom'] ?></h3>
                    <p><?= $_SESSION['usuari']['rol'] ?></p>
                </span>
            </div>
            <a href="../public/index.php?action=perfil"><i class="fa-solid fa-chevron-right"></i></a>
        </div>
        <ul>
            <?php include 'opcionsmenu.php'; ?>
        </ul>
    </div>
</header>
<script>
    $(document).ready(function() {
        $('#desplegable_incidencies').hide();
        $('#desplegable_gestiousers').hide();

        $('#btn_desplegable_menu').click(function() {
            $('#desplegable_menu').slideToggle();
        });
    });
</script>