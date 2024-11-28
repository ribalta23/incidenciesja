<?php
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
}
if(isset($_GET['idIncidencia'])){
    include_once '../controllers/IncidenciaController.php';
    $controller = new IncidenciaController();
    $incidencia = $controller->obtenir_per_id($_GET['idIncidencia']);
    $tipus = $controller->obtenir_tipus_incidencia();
    $espai = $controller->obtenir_espai($incidencia['espai']);
    $tecnics = $controller->obtenir_tecnics($incidencia['id_tipus_incidencia']);
    
       
    include_once '../components/modal_info_prioritat.php';
} else {
    header('Location: ../public/index.php?action=incidencies');
    exit();
}

?>

<div class="contenedor">
    <div class="menu_top">
        <div>
            <h2>Incidencia #<?= $incidencia['id_incidencia'] ?></h2>
        </div>
    </div>
    <h3 style="margin-top: 10px;">Creada per <?= $incidencia['NomCreacio'] ?></h3>
    
        <input type="hidden" name="id_incidencia" value="<?= $incidencia['id_incidencia'] ?>">
        <div class="form_element">
            <label for="titol">Titol</label>
            <input type="text" name="titol" id="titol" value="<?= $incidencia['titol'] ?>" disabled>
        </div>
        <div class="form_element">
            <label for="id_tipo_incidencia">Tipus d'incidencia</label>
            <select name="id_tipo_incidencia" id="id_tipo_incidencia" disabled>
                <?php
                    while ($row = $tipus->fetch_assoc()) {
                        echo "<option value='" . $row['id_tipus_incidencia'] . "'";
                        if ($row['id_tipus_incidencia'] == $incidencia['id_tipus_incidencia']) echo " selected";
                        echo ">" . $row['nom'] . "</option>";
                    }
                ?>
            </select>
        </div>
        
        <div class="form_element">
            <label for="id_usuari">Asignar tecnic</label>
            <select name="id_usuari" id="id_usuari" disabled>
                <option value="">Selecciona un tecnic</option>
                <?php

                    while ($row = $tecnics->fetch_assoc()) {
                        echo "<option value='" . $row['id_usuari'] . "'";
                        if ($row['id_usuari'] == $incidencia['id_usuari']) echo " selected";
                        echo ">" . $row['nom'] . "</option>";
                    }
                ?>
            </select>
        </div>
        <div class="form_element">
            <label for="prioritat">Prioritat</label>
            <div class="radio_group">
                <input type="radio" name="prioritat" value="baixa" id="prioritat_1" disabled
                    <?php if($incidencia['prioritat'] == 'baixa') echo 'checked'; ?>>
                <label for="prioritat_1">Baixa</label>

                <input type="radio" name="prioritat" value="mitjana" id="prioritat_2" disabled
                    <?php if($incidencia['prioritat'] == 'mitjana') echo 'checked'; ?>>
                <label for="prioritat_2">Moderada</label>

                <input type="radio" name="prioritat" value="alta" id="prioritat_3" disabled
                    <?php if($incidencia['prioritat'] == 'alta') echo 'checked'; ?>>
                <label for="prioritat_3">Alta</label>
            </div>
        </div>

        <div class="form_element">
            <label for="estat">Estat</label>
            <div class="radio_group">
                <input type="radio" name="estat" value="pendent" id="estat_1" disabled
                    <?php if($incidencia['estat'] == 'pendent') echo 'checked'; ?>>
                <label for="estat_1">Pendent</label>

                <input type="radio" name="estat" value="enproces" id="estat_2" disabled
                    <?php if($incidencia['estat'] == 'enproces') echo 'checked'; ?>>
                <label for="estat_2">EnProces</label>

                <input type="radio" name="estat" value="resolta" id="estat_3" disabled
                    <?php if($incidencia['estat'] == 'resolta') echo 'checked'; ?>>
                <label for="estat_3">Resolta</label>
            </div>
        </div>

        <div class="form_element">
            <label for="descripcio">Descripcio</label>
            <textarea name="descripcio" id="descripcio" cols="30" rows="10" readonly><?= $incidencia['descripcio'] ?></textarea>
        </div>
        <div class="form_element">
            <label for="data_creacio">Data de creació</label>
            <input type="text" name="data_creacio" id="data_creacio" value="<?= $incidencia['data_creacio'] ?>" readonly>
        </div>
        <div class="form_element">
            <label for="data_creacio">Localitzacio</label>
            <div class="espai">
                <?php if($incidencia['espai'] = NULL) {?>
                <p><?= $espai['pis'] ?></p>
                <p><?= $espai['aula'] ?></p>
                <?php } else if($incidencia['aula'] = NULL && $incidencia['pis'] = NULL) {?>
                <p><?= $espai['espai'] ?></p>
                <?php } else if ($incidencia['aula'] = NULL && $incidencia['pis'] = NULL && $incidencia['espai']){?>
                <p>No hi ha localitzacio asignada</p>
                <?php } ?>
            </div>
        </div>
        <div class="form_element">
            <label for="uploads">Imatges</label>
            <div class="uploads">
                <?php
                    if($incidencia['upload'] != ''){
                        $uploads = json_decode($incidencia['upload'], true);
                        echo '<div class="carousel">';
                        foreach ($uploads as $upload) {
                            if (!empty($upload)) {
                                echo '<a href="../public/assets/upload/' . $upload . '" target="_blank"><img src="../public/assets/upload/' . $upload . '" alt="imatge"></a>';
                            }
                        }
                        echo '</div>';
                    } else {
                        echo '<p>No hi ha imatges.</p>';
                    }
                ?>
            </div>
        </div>
    
    
  
</div>