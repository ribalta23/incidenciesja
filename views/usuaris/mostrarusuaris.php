<?php
include_once '../controllers/ControladorPermisos.php';
$permis = new ControladorPermisos();
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
} else if(!$permis->tePermisAdmin()) {
    header('Location: ../public/index.php?action=incidencies');
    exit;
}
include_once '../controllers/GestorUsuarisController.php';
$controller = new GestorUsuarisController();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$usuaris = $controller->mostrarUsuaris($search);
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=dashboard"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Usuaris</h2>
        </div>
        <div>
            <a class="btn_afegir" href="../public/index.php?action=afegirUsuaris"><button><i class="fa-solid fa-user-plus"></i></button></a>
        </div>
    </div>
    <div class="buscador">
        <input type="text" id="search" placeholder="Buscar per nom..." class="input-buscador" value="<?php echo htmlspecialchars($search ?? ''); ?>">
        <button type="button" class="btn-lupa" onclick="buscarUsuaris()">
            <i class="fa-solid fa-search"></i>
        </button>
    </div>
    <div class="container_usuaris" id="resultados">
        <?php if (!empty($usuaris)) : ?>
            <?php foreach ($usuaris as $usuari) : ?>
                <div class="usuaris" onclick="location.href='../public/index.php?action=editarUsuaris&id=<?= $usuari['id_usuari'] ?>'">
                    <div class="imatge">
                        <?php
                        $ruta_imagen = "../public/assets/profile/" . $usuari['imatge'];
                        $imagen_predeterminada = "../public/assets/profile/default.png";
                        $imagen_mostrar = (file_exists($ruta_imagen) && !empty($usuari['imatge'])) ? $ruta_imagen : $imagen_predeterminada;
                        ?>
                        <img class="profile-img" src="<?php echo $imagen_mostrar; ?>" alt="Usuari">
                    </div>
                    <div class="dreta">
                        <h3><?= htmlspecialchars($usuari['nom']) ?></h3>
                        <h4><?= htmlspecialchars($usuari['cognoms']) ?></h4>
                        <p><?= htmlspecialchars($usuari['rol']) ?></p>
                    </div>
                </div>
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
    xhr.open('GET', `../components/buscarusuaris.php?search=${encodeURIComponent(search)}`, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('resultados').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

document.getElementById('search').addEventListener('input', buscarUsuaris);
</script>