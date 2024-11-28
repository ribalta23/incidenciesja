<?php
include_once '../controllers/ControladorPermisos.php';
$permis = new ControladorPermisos();
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
} else if($permis->tePermisUsuari()) {
    echo '<script>window.location.href = "../public/index.php?action=incidencies";</script>';
    exit;
}
include_once '../controllers/DashboardController.php';
$dashboard = new DashboardController();
$ultimesIncidencies = $dashboard->ultimesIncidencies();
$usuari_id=$_SESSION['usuari']['id']
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <h2>Les meves incidencies</h2>
        </div>
        <?php if($permis->tePermisAdmin()) { ?>
            <div>
                <a href="../public/index.php?action=dashboardAdmin"><button><i class="fa-solid fa-user-shield"></i></button></a>
            </div>
        <?php } ?>
    </div>
    <div class="contador_incidencia">
    <a href="#" class="contador_link" onclick="sendPostRequest('usuari', '<?= $usuari_id ?>')">
        <p><?php echo $dashboard->totalIncidencies()->fetch_row()[0]; ?></p>
        <h2>Total incidencies</h2>
    </a>
</div>
<div class="incidencies_contadors">
    <div class="contador_incidencia">
        <a href="#" class="contador_link" onclick="sendPostRequest('estat', 'pendent')">
            <p><?php echo $dashboard->incidenciesPendents()->fetch_row()[0]; ?></p>
            <h2>Pendents</h2>
        </a>
    </div>
    <div class="contador_incidencia">
        <a href="#" class="contador_link" onclick="sendPostRequest('estat', 'enproces')">
            <p><?php echo $dashboard->incidenciesEnProces()->fetch_row()[0]; ?></p>
            <h2>En procés</h2>
        </a>
    </div>
    <div class="contador_incidencia">
        <a href="#" class="contador_link" onclick="sendPostRequest('estat', 'resolta')">
            <p><?php echo $dashboard->incidenciesResoltes()->fetch_row()[0]; ?></p>
            <h2>Resoltes</h2>
        </a>
    </div>
</div>
<div class="incidencies_contadors">
    <div class="contador_incidencia a">
        <a href="#" class="contador_link" onclick="sendPostRequest('prioritat', 'alta')">
            <p><?php echo $dashboard->incideciesAltes()->fetch_row()[0]; ?></p>
            <h2>Alta</h2>
        </a>
    </div>
    <div class="contador_incidencia m">
        <a href="#" class="contador_link" onclick="sendPostRequest('prioritat', 'mitjana')">
            <p><?php echo $dashboard->incideciesModerades()->fetch_row()[0]; ?></p>
            <h2>Moderada</h2>
        </a>
    </div>
    <div class="contador_incidencia b">
        <a href="#" class="contador_link" onclick="sendPostRequest('prioritat', 'baixa')">
            <p><?php echo $dashboard->incideciesBaixes()->fetch_row()[0]; ?></p>
            <h2>Baixa</h2>
        </a>
    </div>
</div>
    <div class="ultimes_incidencies">
        <h2>Ultimes incidencies</h2>
        <div class="container_incidencies">
        <?php if ($ultimesIncidencies && $ultimesIncidencies->num_rows > 0) : ?>
            <?php while ($incidencia = $ultimesIncidencies->fetch_assoc()) : ?>
                <a class="incidencia" href="../public/index.php?action=veureIncidencia&idIncidencia=<?= $incidencia['id_incidencia'] ?>">
                    <div class="esquerra">
                        <div class="prioritat p_<?= $incidencia['prioritat'] ?>"><?= $incidencia['prioritat'] ?></div>
                    </div>
                    <div class="dreta">
                        <p><?= $incidencia['tipus_incidencia']?></p>
                        <h3><?= $incidencia['titol']?></h3>
                        <p><?= $incidencia['data_creacio']?></p>
                        <p><?= $incidencia['nom_usuari_supervisor']?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no_incidencies">No tens incidencies</p>
        <?php endif; ?>
    </div>
    </div>
</div>
<script>
    function sendPostRequest(filterType, filterValue) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../public/index.php?action=filtres_usuari';

        const inputType = document.createElement('input');
        inputType.type = 'hidden';
        inputType.name = filterType;
        inputType.value = filterValue;

        form.appendChild(inputType);
        document.body.appendChild(form);
        form.submit();
    }
</script>