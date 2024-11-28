<?php
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
}
    include_once '../controllers/IncidenciaController.php';
    $controller = new IncidenciaController();
    $tipus = $controller->obtenir_tipus_incidencia();

    include_once '../components/modal_info_prioritat.php';
    include_once '../controllers/ControladorPermisos.php';
    $permis = new ControladorPermisos();
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=incidencies"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Agregar incidencia</h2>
        </div>
    </div>
    <form action="../controllers/IncidenciaController.php?action=crear" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_usuari_creacio" value="<?= $_SESSION['usuari']['id'] ?>">
        <div class="form_element">
            <label for="titol">Titol de la incidencia</label>
            <input type="text" name="titol" id="titol" placeholder="Titol" required>
        </div>
        <div class="form_element">
            <label for="id_tipo_incidencia">Tipus d'incidencia</label>
            <select name="id_tipo_incidencia" id="id_tipo_incidencia" onchange="select_tecnics()">
                <option value="" selected>Selecciona un tipus d'incidencia</option>
                <?php
                    while ($row = $tipus->fetch_assoc()) {
                        echo "<option value='".$row['id_tipus_incidencia']."'>".$row['nom']."</option>";
                    }
                ?>
            </select>
        </div>
        <?php if($permis->tePermisAdmin()) { ?>
            <div class="form_element">
                <label for="id_usuari">Asignar tecnic</label>
                <select name="id_usuari" required id="">
                    <option value="">Selecciona un tecnic</option>
                </select>
            </div>
        <?php } ?>
        <div class="form_element">
            <div>
                <label for="prioritat">Prioritat</label>
                <span class="info_prioritat" id="info_prioritat"><i class="fa-solid fa-circle-info"></i></span>
            </div>
            <div class="radio_group">
                <input type="radio" name="prioritat" value="baixa" id="prioritat_1" required>
                <label for="prioritat_1">Baixa</label>
                <input type="radio" name="prioritat" value="mitjana" id="prioritat_2" required>
                <label for="prioritat_2">Moderada</label>
                <input type="radio" name="prioritat" value="alta" id="prioritat_3" required>
                <label for="prioritat_3">Alta</label>
            </div>
        </div>
        <div class="form_element">
            <label for="descripcio">Descripcio de la incidencia</label>
            <textarea name="descripcio" id="descripcio" cols="30" rows="10" required></textarea>
        </div>
        <div class="form_element">
            <label for="estat">Localitzacio</label>
            <select name="tipus_localitzacio" id="tipus_localitzacio" onchange="mostrarElementos()" required>
                <option value="" selected>Selecciona un espai</option>
                <option value="aules">Aules</option>
                <option value="altres">Altres</option>
            </select>
        </div>
        <div class="selects_aules" id="selects_aules" style="display:none;">
            <div class="form_element">
                <label for="pis">Pis</label>
                <select name="pis" id="pis" onchange="select_aula()">
                    <option value="" selected>Selecciona un pis</option>
                </select>
            </div>
            <div class="form_element">
                <label for="aula">Aula</label>
                <select name="aula" id="aula">
                    <option value="" selected>Selecciona una aula</option>
                </select>
            </div>
        </div>
        <div id="input_altres" style="display:none;" class="form_element">
            <label for="altres">Altres localitzacions</label>
            <select name="altres" id="altres">
                <option value="" selected>Selecciona una localitzacio</option >
            </select>
        </div>
        <div class="form_element">
            <label for="upload">Afegir imatges/arxius</label>
            <input type="file" name="upload[]" id="imatge" accept="image/*" multiple>
        </div>
        <div class="form_element">
            <button type="submit">Agregar incidencia</button>
        </div>
    </form>
</div>
