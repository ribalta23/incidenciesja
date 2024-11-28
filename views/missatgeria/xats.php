<?php
    if (!defined('ACCESS_ALLOWED')) {
        header('Location: ../404.php');
        exit;
    }
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=dashboardAdmin"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Els teus xats</h2>
        </div>
        <div>
            <a href="../public/index.php?action=nouXat"><button id="btn_filtres">Nou xat <i class="fa-regular fa-comments"></i></button></a>
        </div>
    </div>
</div>