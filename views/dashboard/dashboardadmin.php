<?php
include_once '../controllers/ControladorPermisos.php';
$permis = new ControladorPermisos();
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
} else if(!$permis->tePermisAdmin()) {
    echo '<script>window.location.href = "../public/index.php?action=dashboard";</script>';
}
include_once '../controllers/DashboardController.php';
$dashboard = new DashboardController();
include_once '../controllers/IncidenciaController.php';
$controller = new IncidenciaController();

// Obtener el técnico seleccionado si existe
$selectedUsuari = isset($_POST['usuari']) ? $_POST['usuari'] : '';
$nomUsuariSeleccionat = $selectedUsuari ? $controller->obtenir_nom_usuari($selectedUsuari) : 'Tots els usuaris';

$ultimesIncidencies = $dashboard->ultimesIncidenciesTotes();

$incidenciesMes = $dashboard->obtenirEstadistiques('mes', $selectedUsuari);
$incidenciesAny = $dashboard->obtenirEstadistiques('any', $selectedUsuari);
$incidenciesTotals = $dashboard->obtenirEstadistiques('total', $selectedUsuari);

$incidenciesMesArray = $incidenciesMes['totals'];
$incidenciesAnyArray = $incidenciesAny['totals'];
$incidenciesTotalsArray = $incidenciesTotals['totals'];
$incidenciesPerEstatMesArray = $incidenciesMes['perEstat'];
$incidenciesPerEstatAnyArray = $incidenciesAny['perEstat'];
$incidenciesPerEstatTotalsArray = $incidenciesTotals['perEstat'];

$incidenciesMesJson = json_encode($incidenciesMesArray);
$incidenciesAnyJson = json_encode($incidenciesAnyArray);
$incidenciesTotalsJson = json_encode($incidenciesTotalsArray);
$incidenciesPerEstatMesJson = json_encode($incidenciesPerEstatMesArray);
$incidenciesPerEstatAnyJson = json_encode($incidenciesPerEstatAnyArray);
$incidenciesPerEstatTotalsJson = json_encode($incidenciesPerEstatTotalsArray);

$incidenciesTecnicoMes = $dashboard->obtenirEstadistiques('mes', $selectedUsuari);
$incidenciesTecnicoAny = $dashboard->obtenirEstadistiques('any', $selectedUsuari);
$incidenciesTecnicoTotals = $dashboard->obtenirEstadistiques('total', $selectedUsuari);

$incidenciesTecnicoMesArray = $incidenciesTecnicoMes['totals'];
$incidenciesTecnicoAnyArray = $incidenciesTecnicoAny['totals'];
$incidenciesTecnicoTotalsArray = $incidenciesTecnicoTotals['totals'];
$incidenciesTecnicoPerEstatMesArray = $incidenciesTecnicoMes['perEstat'];
$incidenciesTecnicoPerEstatAnyArray = $incidenciesTecnicoAny['perEstat'];
$incidenciesTecnicoPerEstatTotalsArray = $incidenciesTecnicoTotals['perEstat'];

$incidenciesTecnicoMesJson = json_encode($incidenciesTecnicoMesArray);
$incidenciesTecnicoAnyJson = json_encode($incidenciesTecnicoAnyArray);
$incidenciesTecnicoTotalsJson = json_encode($incidenciesTecnicoTotalsArray);
$incidenciesTecnicoPerEstatMesJson = json_encode($incidenciesTecnicoPerEstatMesArray);
$incidenciesTecnicoPerEstatAnyJson = json_encode($incidenciesTecnicoPerEstatAnyArray);
$incidenciesTecnicoPerEstatTotalsJson = json_encode($incidenciesTecnicoPerEstatTotalsArray);
?>

<div class="contenedor">
    <div class="menu_top">
        <div>
            <h2>Dashboard</h2>
        </div>
        <div>
            <a href="../public/index.php?action=dashboard"><button><i class="fa-solid fa-user-gear"></i></button></a>
        </div>
    </div>
    <div class="contenedor_dashboard">
        <h3>General</h3>
        <div class="radio_group_dashboard">
            <input checked type="radio" name="dades_dashboard_superior" value="mes" id="dades_dashboard_superior_1" required>
            <label for="dades_dashboard_superior_1">Més</label>
            <input type="radio" name="dades_dashboard_superior" value="any" id="dades_dashboard_superior_2" required>
            <label for="dades_dashboard_superior_2">Any</label>
            <input type="radio" name="dades_dashboard_superior" value="total" id="dades_dashboard_superior_3" required>
            <label for="dades_dashboard_superior_3">General</label>
        </div>
        
        <div class="grafic-top">
            <canvas id="generalChart"></canvas>
        </div>
        <hr>

        <div class="grafic-bottom">
            <div class="grafic-bottom-item">
                <h3 id="pendents"><?= $incidenciesPerEstatMesArray[0]; ?></h3>
                <h3>Pendents</h3>
            </div>
            <div class="grafic-bottom-item">
                <h3 id="enproces"><?= $incidenciesPerEstatMesArray[1]; ?></h3>
                <h3>En procés</h3>
            </div>
            <div class="grafic-bottom-item">
                <h3 id="tancades"><?= $incidenciesPerEstatMesArray[2]; ?></h3>
                <h3>Tancades</h3>
            </div>
        </div>
    </div>
    <form id="filtresForm" method="post">
        <div class="form_element">
            <label for="usuari">Busca per tècnics</label>
            <select name="usuari" id="usuari">
                <option value="">Tots els tècnics</option>
                <?php $usuaris = $controller->obtenir_usuaris(); ?>
                <?php if ($usuaris && $usuaris->num_rows > 0) : ?>
                    <?php while ($usuari = $usuaris->fetch_assoc()) : ?>
                        <option value="<?= $usuari['id_usuari'] ?>" <?= $selectedUsuari == $usuari['id_usuari'] ? 'selected' : '' ?>><?= $usuari['nom'] ?></option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
    </form>
        <div id="contenedorInferior" class="contenedor_dashboard oculto">
        <h3 id="nomUsuariSeleccionat"><?= $nomUsuariSeleccionat ?></h3>
        <div class="radio_group_dashboard">
            <input checked type="radio" name="dades_dashboard_inferior" value="mes" id="dades_dashboard_inferior_1" required>
            <label for="dades_dashboard_inferior_1">Més</label>
            <input type="radio" name="dades_dashboard_inferior" value="any" id="dades_dashboard_inferior_2" required>
            <label for="dades_dashboard_inferior_2">Any</label>
            <input type="radio" name="dades_dashboard_inferior" value="total" id="dades_dashboard_inferior_3" required>
            <label for="dades_dashboard_inferior_3">General</label>
        </div>
        <div class="grafic-top">
            <canvas id="tecnicsChart"></canvas>
        </div>
        <hr>
        <div class="grafic-bottom">
            <div class="grafic-bottom-item">
                <h3 id="pendentsInferior"><?= $incidenciesTecnicoPerEstatMesArray[0]; ?></h3>
                <h3>Pendents</h3>
            </div>
            <div class="grafic-bottom-item">
                <h3 id="enprocesInferior"><?= $incidenciesTecnicoPerEstatMesArray[1]; ?></h3>
                <h3>En procés</h3>
            </div>
            <div class="grafic-bottom-item">
                <h3 id="tancadesInferior"><?= $incidenciesTecnicoPerEstatMesArray[2]; ?></h3>
                <h3>Tancades</h3>
            </div>
        </div>
    </div>
    

    
    <div class="ultimes_incidencies">
        <h2>Ultimes incidències generals</h2>
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
            <p class="no_incidencies">No tens incidències</p>
        <?php endif; ?>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    
    // Obtener los datos de PHP
    const incidenciesMes = <?= $incidenciesMesJson; ?>;
    const incidenciesAny = <?= $incidenciesAnyJson; ?>;
    const incidenciesTotals = <?= $incidenciesTotalsJson; ?>;
    const incidenciesPerEstatMes = <?= $incidenciesPerEstatMesJson; ?>;
    const incidenciesPerEstatAny = <?= $incidenciesPerEstatAnyJson; ?>;
    const incidenciesPerEstatTotals = <?= $incidenciesPerEstatTotalsJson; ?>;
    const incidenciesTecnicoMes = <?= $incidenciesTecnicoMesJson; ?>;
    const incidenciesTecnicoAny = <?= $incidenciesTecnicoAnyJson; ?>;
    const incidenciesTecnicoTotals = <?= $incidenciesTecnicoTotalsJson; ?>;
    const incidenciesTecnicoPerEstatMes = <?= $incidenciesTecnicoPerEstatMesJson; ?>;
    const incidenciesTecnicoPerEstatAny = <?= $incidenciesTecnicoPerEstatAnyJson; ?>;
    const incidenciesTecnicoPerEstatTotals = <?= $incidenciesTecnicoPerEstatTotalsJson; ?>;
    
    function calcularMaximoGeneral(datos) {
        if (!Array.isArray(datos) || datos.length === 0) return 0;
        const maxValor = Math.max(...datos.filter(Number.isFinite));
        return Math.ceil(maxValor * 1.5); // Incrementar el máximo en un 50%
    }

    function calcularMaximoTecnicos(datos) {
        if (!Array.isArray(datos) || datos.length === 0) return 0;
        const maxValor = Math.max(...datos.filter(Number.isFinite));
        return Math.ceil(maxValor * 1.5); // Incrementar el máximo en un 50%
    }

    //-----------------------Grafic General--------------------------
    const ctx = document.getElementById('generalChart').getContext('2d');
    const prioridadesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['ALTA', 'MITJANA', 'BAIXA'],
            datasets: [{
                label: 'Número de Tickets',
                data: incidenciesMes, 
                backgroundColor: [
                    'red',
                    'yellow',
                    'limegreen'
                ],
                borderColor: [
                    'darkred',
                    'goldenrod',
                    'green'
                ],
                borderWidth: 1 
            }]
        },
        options: {
            indexAxis: 'y', // Cambia a barras horizontales
            plugins: {
                legend: { display: false }, // Oculta la leyenda
                tooltip: { enabled: true }, // Oculta los tooltips
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: 'black',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value, context) {
                        return value;
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true, // El eje X comienza en 0
                    max: calcularMaximoGeneral(incidenciesMes), // Calcula el máximo de los datos
                    grid: {
                        display: false // Oculta el grid del eje X
                    },
                    ticks: {
                        display: false // Oculta los números del eje X
                    }
                },
                y: {
                    beginAtZero: true, // El eje Y comienza en 0
                    grid: {
                        display: false // Oculta el grid del eje Y
                    },
                    ticks: {
                        color: 'black', // Color de las etiquetas del eje Y
                        font: {
                            size: 14, // Tamaño de la fuente de las etiquetas del eje Y
                            weight: 'bold',
                            anchor: 'start',
                            align: '' // Alinea las etiquetas del eje Y a la izquierda
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false // Permite ajustar el tamaño
        },
        plugins: [ChartDataLabels]
    });

    //----------------------Grafic Tecnics-------------------------
    const ctxTecnics = document.getElementById('tecnicsChart').getContext('2d');
    const tecnicsChart = new Chart(ctxTecnics, {
        type: 'bar',
        data: {
            labels: ['ALTA', 'MITJANA', 'BAIXA'],
            datasets: [{
                label: 'Número de Tickets',
                data: incidenciesTecnicoMes, // Datos iniciales
                backgroundColor: [
                    'red',
                    'yellow',
                    'limegreen'
                ],
                borderColor: [
                    'darkred',
                    'goldenrod',
                    'green'
                ],
                borderWidth: 1 // Grosor del borde
            }]
        },
        options: {
            indexAxis: 'y', // Cambia a barras horizontales
            plugins: {
                legend: { display: false }, // Oculta la leyenda
                tooltip: { enabled: false }, // Oculta los tooltips
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: 'black',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value, context) {
                        return value;
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true, // El eje X comienza en 0
                    max: calcularMaximoTecnicos(incidenciesTecnicoMes), // Establecer el máximo del eje X
                    grid: {
                        display: false // Oculta el grid del eje X
                    },
                    ticks: {
                        display: false // Oculta los números del eje X
                    }
                },
                y: {
                    beginAtZero: true, // El eje Y comienza en 0
                    grid: {
                        display: false // Oculta el grid del eje Y
                    },
                    ticks: {
                        color: 'black', // Color de las etiquetas del eje Y
                        font: {
                            size: 14, // Tamaño de la fuente de las etiquetas del eje Y
                            weight: 'bold',
                            align: 'start' // Alinea las etiquetas del eje Y a la izquierda
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false // Permite ajustar el tamaño
        },
        plugins: [ChartDataLabels]
    });

    // Función para actualizar los datos del gráfico y los números de incidencias por estado
    async function actualizarDatos(dades, usuari, origen) {
        console.log("Actualizar Datos", { dades, usuari, origen });
        let nuevosDatos, nuevosDatosPerEstat;

        const response = await fetch(`../controllers/DashboardController.php?dades=${dades}&usuari=${usuari}`);
        const data = await response.json();

        nuevosDatos = data.totals;
        nuevosDatosPerEstat = data.perEstat;

        if (origen === 'superior') {
            prioridadesChart.data.datasets[0].data = nuevosDatos;
            prioridadesChart.options.scales.x.max = calcularMaximoGeneral(nuevosDatos);
            prioridadesChart.update();

            document.getElementById('pendents').textContent = nuevosDatosPerEstat[0];
            document.getElementById('enproces').textContent = nuevosDatosPerEstat[1];
            document.getElementById('tancades').textContent = nuevosDatosPerEstat[2];
        } else if (origen === 'inferior') {
            tecnicsChart.data.datasets[0].data = nuevosDatos;
            tecnicsChart.options.scales.x.max = calcularMaximoTecnicos(nuevosDatos);
            tecnicsChart.update();

            document.getElementById('pendentsInferior').textContent = nuevosDatosPerEstat[0];
            document.getElementById('enprocesInferior').textContent = nuevosDatosPerEstat[1];
            document.getElementById('tancadesInferior').textContent = nuevosDatosPerEstat[2];
        }
    }

    // Event listener para los cambios en el radio group superior
    document.querySelectorAll('input[name="dades_dashboard_superior"]').forEach((elem) => {
        elem.addEventListener('change', function(event) {
            actualizarDatos(event.target.value, '', 'superior'); // No se pasa usuario para el gráfico general
        });
    });

    // Event listener para los cambios en el radio group inferior
    document.querySelectorAll('input[name="dades_dashboard_inferior"]').forEach((elem) => {
        elem.addEventListener('change', function(event) {
            const usuari = document.getElementById('usuari').value;
            actualizarDatos(event.target.value, usuari, 'inferior');
        });
    });

    // Event listener para los cambios en el select de usuario
    document.getElementById('usuari').addEventListener('change', function() {
        const usuari = this.value;
        const dades = document.querySelector('input[name="dades_dashboard_inferior"]:checked').value;
        actualizarDatos(dades, usuari, 'inferior');
    });

    document.getElementById('usuari').addEventListener('change', function() {
        const contenedorInferior = document.getElementById('contenedorInferior');
        const usuari = this.value;

        if (usuari) {
            contenedorInferior.classList.remove('oculto'); // Muestra el contenedor
            actualizarNombreUsuario();
        } else {
            contenedorInferior.classList.add('oculto'); // Oculta el contenedor
        }
    });

    function actualizarNombreUsuario() {
        const usuariSelect = document.getElementById('usuari');
        const usuariNom = usuariSelect.options[usuariSelect.selectedIndex].text;
        document.getElementById('nomUsuariSeleccionat').textContent = usuariNom ? usuariNom : 'Tots els usuaris';
    }
</script>