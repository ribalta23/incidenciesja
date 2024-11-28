<?php
    if (!defined('ACCESS_ALLOWED')) {
        header('Location: ../404.php');
        exit;
    }
    include_once '../controllers/IncidenciaController.php';
    $controller = new IncidenciaController();
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=dashboardAdmin"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Incidencies</h2>
        </div>
        <div>
            <button id="btn_filtres"><i class="fa-solid fa-filter"></i></button>
            <a class="btn_afegir" href="../public/index.php?action=crearIncidencia"><button><i class="fa-solid fa-plus"></i></button></a>
        </div>
    </div>
    <?php include_once '../components/filtresincidencies.php'; ?>
    <div class="container_incidencies">
        <?php if ($incidencies && $incidencies->num_rows > 0) : ?>
            <?php while ($incidencia = $incidencies->fetch_assoc()) : ?>
                <a class="incidencia" href="../public/index.php?action=veureIncidencia&idIncidencia=<?= $incidencia['id_incidencia'] ?>">
                    <div class="esquerra">
                        <div class="prioritat p_<?= $incidencia['prioritat'] ?>"><?= $incidencia['prioritat'] ?></div>
                        <?php
                            $fecha = $incidencia['data_creacio'];
                            $diaMes = date('d-m', strtotime($fecha));
                        ?>
                        <span><?= $diaMes ?></span>
                    </div>
                    <div class="dreta">
                        <p><?= $incidencia['tipus_incidencia']?></p>
                        <h3><?= $incidencia['titol']?></h3>
                        <p>Lloc: <?= $incidencia['nom_espai']?></p>
                        <p>Tecnic: <?= $incidencia['nom_usuari_asignat']?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no_incidencies">No hi ha incidències disponibles.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.filtres_container').hide();
        $('#btn_filtres').click(function() {
            $('.filtres_container').slideToggle();
        });
        $('.filtres_container i').click(function() {
            $('.filtres_container').slideUp();
        });
    });
</script>