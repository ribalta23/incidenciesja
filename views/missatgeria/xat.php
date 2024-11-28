<?php
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
}
if(!isset($_SESSION)){
    session_start();
}
include_once '../controllers/MissatgesController.php';
$controller = new MissatgesController();
$receptor = $controller->veureUsuari($_GET['id']);
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=xats"><i class="fa-solid fa-angle-left"></i></a>
            <a class="link_perfil" href="../public/index.php?action=veurePerfil&idUsuari=<?= $receptor['id_usuari'] ?>">
                <img src="../public/assets/profile/<?= $receptor['imatge'] ?>" alt="">
                <h2><?= $receptor['nom']?></h2>
            </a>
        </div>
    </div>
    <div class="missatges_container">
        <div id="missatges_content" class="missatges_content"></div>
        <div id="form_enviar">
            <input type="text" id="missatge" placeholder="Escriu un missatge..." class="input-missatge">
            <button id="btn_enviar" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const emisor_id = <?php echo $_SESSION['usuari']['id']; ?>;
        const receptor_id = <?php echo $_GET['id']; ?>;
        $('#btn_enviar').click(function() {
            const missatge = $('#missatge').val();
            if (missatge.trim() !== "") {
                $.ajax({
                    url: '../controllers/missatges_handler.php',
                    type: 'POST',
                    data: {
                        action: 'enviar',
                        emisor_id: emisor_id,
                        receptor_id: receptor_id,
                        missatge: missatge
                    },
                    success: function() {
                        $('#missatge').val('');
                        cargarMissatges();
                    }
                });
            }
        });

        function cargarMissatges() {
            $.ajax({
                url: '../controllers/missatges_handler.php',
                type: 'POST',
                data: {
                    action: 'carregar',
                    emisor_id: emisor_id,
                    receptor_id: receptor_id
                },
                success: function(data) {
                    $('#missatges_content').html(data);
                }
            });
        }
        setInterval(cargarMissatges, 200000);
    });
</script>
