<?php
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
}
include_once '../controllers/ControllerConfiguracio.php';
$controller = new ConfiguracioController();
$tipus_incidencia = $controller->obtenirTipusIncidencia();
$espais = $controller->obtenirEspais();
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=dashboardAdmin"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Configuració</h2>
        </div>
    </div>
    <div class="contenedor_configuracions">
        <div class="configuracio">
            <div class="configuracio_menu" id="btn_configuracio_tipus">
                <h3>Tipus d'incidencia</h3>
                <i class="fa-solid fa-angle-down"></i>
            </div>
            <div id="configuracio_content_tipus" class="configuracio_content">
                <form action="../controllers/ControllerConfiguracio.php?action=afegirTipusIncidencia" method="post">
                    <div class="form_element">
                        <input type="text" name="nom" placeholder="Nom del Tipus" required>
                    </div>
                    <div class="form_element">
                        <textarea name="descripcio" placeholder="Descripció" required></textarea>
                    </div>
                    <div class="form_element">
                        <button type="submit">Agregar</button>
                    </div>
                </form>
                <div class="tipus_incidencia_cards">
                    <?php while ($row = $tipus_incidencia->fetch_assoc()) { ?>
                        <div class="tipus_card">
                            <h4><?php echo $row['nom']; ?> </h4>
                            <p><?php echo $row['descripcio']; ?> </p>
                            <a href="../controllers/ControllerConfiguracio.php?action=eliminarTipus&id=<?php echo $row['id_tipus_incidencia']; ?>" class="btn_eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="configuracio">
            <div class="configuracio_menu" id="btn_configuracio_espais">
                <h3>Aules / Espais</h3>
                <i class="fa-solid fa-angle-down"></i>
            </div>
            <div id="configuracio_content_espai" class="configuracio_content">
                <div class="form_element">
                    <label for="select_espais">Selecciona lo que vols afegir</label>
                    <select name="" id="select_espais">
                        <option value="">Selecciona aula / espais</option>
                        <option value="aules">Aules</option>
                        <option value="espais">Espais</option>
                    </select>
                </div>
                <div class="form_espais">
                    <div id="aules_select">
                        <form action="../controllers/ControllerConfiguracio.php?action=afegirEspai" method="post">
                            <div class="form_element">
                                <label for="aula">Numero d'aula</label>
                                <input type="number" name="aula" placeholder="1, 2, 3..." required>
                            </div>
                            <div class="form_element">
                                <label for="pis">Numero de pis</label>
                                <select name="pis" required>
                                    <option value="">Selecciona un pis</option>
                                    <option value="-1">Soterrani</option>
                                    <option value="0">Planta Baixa</option>
                                    <option value="1">Primer</option>
                                    <option value="2">Segon</option>
                                    <option value="3">Tercer</option>
                                </select>
                            </div>
                            <div class="form_element">
                                <label for="nom">Nom de l'aula</label>
                                <input type="text" name="nom" placeholder="Aula 1, Aula 2..." required>
                            </div>
                            <div class="form_element">
                                <button type="submit">Agregar Espai</button>
                            </div>
                        </form>
                    </div>
                    <div id="espais_select">
                        <form action="../controllers/ControllerConfiguracio.php?action=afegirEspai" method="post">
                            <div class="form_element">
                                <label for="espai">Nom del espai</label>
                                <input type="text" name="espai" placeholder="Jardi, Biblioteca..." required>
                            </div>
                            <div class="form_element">
                                <button type="submit">Agregar Espai</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="espais_cards">
                    <?php
                    $current_pis = null;
                    $has_espais_sense_pis = false;
                    $espais_sense_pis = []; // Asegúrate de inicializar esta variable

                    while ($row = $espais->fetch_assoc()) {
                        // Asegúrate de validar correctamente que 'pis' puede ser 0
                        if ($row['aula'] && (isset($row['pis']) || $row['pis'] === 0) && $row['espai']) {
                            if ($current_pis !== $row['pis']) {
                                if ($current_pis !== null) echo "</div>";
                                $current_pis = $row['pis'];
                                echo "<div class='pis_group'><h4>Pis $current_pis</h4>";
                            }
                            echo "<div class='espai_card'>";
                            echo "<h4>Aula {$row['aula']}: {$row['espai']}</h4>";
                            echo "<a href='../controllers/ControllerConfiguracio.php?action=eliminarEspai&id={$row['id']}' class='btn_eliminar_espais'>";
                            echo "<i class='fa-solid fa-trash'></i>";
                            echo "</a>";
                            echo "</div>";
                        } else {
                            $has_espais_sense_pis = true;
                            $espais_sense_pis[] = $row;
                        }
                    }
                    if ($current_pis !== null) echo "</div>";
                    if ($has_espais_sense_pis) {
                        echo "<div class='pis_group'><h4>Espais</h4>";
                        foreach ($espais_sense_pis as $espai) {
                            echo "<div class='espai_card'>";
                            echo "<h4>{$espai['espai']}</h4>";
                            echo "<a href='../controllers/ControllerConfiguracio.php?action=eliminarEspai&id={$espai['id']}' class='btn_eliminar_espais'>";
                            echo "<i class='fa-solid fa-trash'></i>";
                            echo "</a>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#configuracio_content_tipus').hide();
        $('#configuracio_content_espai').hide();
        $('#aules_select').hide();
        $('#espais_select').hide();
        $('#btn_configuracio_tipus').click(function() {
            $('#configuracio_content_tipus').slideToggle();
            $('#btn_configuracio_tipus i').toggleClass('fa-angle-down');
            $('#btn_configuracio_tipus i').toggleClass('fa-angle-up');
        });
        $('#btn_configuracio_espais').click(function() {
            $('#configuracio_content_espai').slideToggle();
            $('#btn_configuracio_espais i').toggleClass('fa-angle-down');
            $('#btn_configuracio_espais i').toggleClass('fa-angle-up');
        });
        $('#select_espais').change(function() {
            if ($(this).val() == ''){
                $('#aules_select').hide();
                $('#espais_select').hide();
            } else if ($(this).val() == 'aules') {
                $('#aules_select').show();
                $('#espais_select').hide();
            } else {
                $('#aules_select').hide();
                $('#espais_select').show();
            }
        });
    });
</script>