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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$usuaris = $controller->veureUsuaris($search);
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=xats">
                <i class="fa-solid fa-angle-left"></i>
            </a>
            <h2>Nou xat</h2>
        </div>
    </div>
    <input type="text" id="search" placeholder="Buscar per nom..." class="input-buscador" value="<?php echo htmlspecialchars($search ?? ''); ?>">
    <div class="users_container" id="resultados">
    <?php if (!empty($usuaris)) : ?>
            <?php foreach ($usuaris as $usuari) : ?>
                <a class="a_user" href="../public/index.php?action=xat&id=<?= $usuari['id_usuari']?>">
                    <div class="user">
                        <img src="../public/assets/profile/<?= $usuari['imatge']?>" alt="imatge_usuari" class="user-img">
                        <p><?= $usuari['nom']." ". $usuari['cognoms']; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hi ha usuaris disponibles.</p>
        <?php endif; ?>
    </div>
</div>
<script>
function buscarUsuaris() {
    const search = document.getElementById('search').value;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `../components/buscarusuarischat.php?search=${encodeURIComponent(search)}`, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('resultados').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

document.getElementById('search').addEventListener('input', buscarUsuaris);
</script>