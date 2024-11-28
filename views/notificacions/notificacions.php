<?php

include_once '../controllers/NotificacionsController.php';
$controller = new NotificacionsController();
$usuari_id = $_SESSION['usuari']['id'];
$notificacions = $controller->obtenirNotificacions($usuari_id);
?>

<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=dashboard"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Notificacions</h2>
        </div>
        <img class="img_user" id="preview" src="#" alt="Previsualización de la imagen" style="display: none;">
    </div>
    <div class="contenedor-notificacions">        
        <?php if ($notificacions && $notificacions->num_rows > 0): ?>
            <?php while ($notificacio = $notificacions->fetch_assoc()): ?>
                <a class="notificacio" href="../public/index.php?action=veureIncidencia&idIncidencia=<?= $notificacio['id_incidencia'] ?>" onclick="marcarLlegida(<?= $notificacio['id']?>)">
                <div class="esquerra <?= $notificacio['llegida'] ? 'llegida' : 'no-llegida' ?>">
                    <div class="icono">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                </div>
                <div class="dreta">
                    <p><strong>Incidencia <?= ucfirst($notificacio['tipus']) ?></strong> </p>
                    <h3><?= $notificacio['titol'] ?></h3>
                    <p><small><?= $notificacio['data'] ?></small></p>
                    
                </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no_notificacions">No tens notificacions.</p>
        <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function marcarLlegida(id){
        fetch(`../controllers/NotificacionsController.php?action=marcarComLlegida&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if(data.success){
                document.querySelector(`.notificacio[data-id="${id}"]`).classList.add('llegida');
            }
        });
    }
</script>