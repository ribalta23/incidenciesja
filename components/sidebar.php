<div class="sidebar">
    <a class="img_logo" href="../public/index.php">
        <img src="../public/assets/brand/logo_simbol_white.png" alt="logo" class="logo_image">
    </a>
    <ul>
        <?php include 'opcionsmenu.php'; ?>
    </ul>
    <div class="user_info">
        <div>
            <img src="../public/assets/profile/<?php echo $_SESSION['usuari']['imatge'] ?>" alt="user" class="user_image">
            <span>
                <h3><?php echo $_SESSION['usuari']['nom'] ?></h3>
                <p><?php echo $_SESSION['usuari']['rol'] ?></p>
            </span>
        </div>
        <a href="../public/index.php?action=perfil"><i class="fa-solid fa-chevron-right"></i></a>
    </div>
</div>