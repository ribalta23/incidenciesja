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
    
    if($incidencia['id_usuari'] != NULL){
        $tecnic = $controller->obtenir_tecnic($incidencia['id_usuari']);
    }
    include_once '../components/modal_info_prioritat.php';
} else {
    header('Location: ../public/index.php?action=incidencies');
    exit();
}
if($incidencia['id_usuari'] != NULL){
    $phone = '34' . $tecnic['telefono']; 
    $nom = $tecnic['nom'];

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $incidencia_url = $protocol . $host . "/incidenciesja/public/index.php?action=veureIncidencia&idIncidencia=" . $incidencia['id_incidencia'];

    $incidencia_text = "Hola $nom, tens aquesta incidencia activa: \n$incidencia_url";

    $whatsapp_link = "https://api.whatsapp.com/send?phone=$phone&text=" . urlencode($incidencia_text);
}

include_once '../controllers/ControladorPermisos.php';
$permis = new ControladorPermisos();
$esCreador = $incidencia['id_usuari_creacio'] == $_SESSION['usuari']['id'];
$esTecnic = $incidencia['id_usuari'] == $_SESSION['usuari']['id'];
$esAdmin = $permis->tePermisAdmin();
$desabilitat = !($esCreador || $esTecnic || $esAdmin);
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=incidencies"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Incidencia #<?= $incidencia['id_incidencia'] ?></h2>
        </div>
        <?php if($incidencia['id_usuari'] != NULL && !$permis->tePermisUsuari()) { ?>
            <div>
                <a class="btn_afegir" href="<?= $whatsapp_link ?>" target="_blank">
                    <button><i class="fa-brands fa-whatsapp"></i></button>
                </a>
            </div>
        <?php } ?>
    </div>
    <h3 style="margin-top: 10px;">Creada per <a href="../public/index.php?action=veurePerfil&idUsuari=<?= $incidencia['id_usuari_creacio']?>"><?= $incidencia['NomCreacio'] ?></a></h3>
    <form action="../controllers/IncidenciaController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_incidencia" value="<?= $incidencia['id_incidencia'] ?>">
        <div class="form_element">
            <label for="titol">Titol</label>
            <input type="text" name="titol" id="titol" value="<?= $incidencia['titol'] ?>" <?php if($desabilitat) echo 'disabled'?>>
        </div>
        <div class="form_element">
            <label for="id_tipo_incidencia">Tipus d'incidencia</label>
            <select name="id_tipo_incidencia" id="id_tipo_incidencia" onchange="select_tecnics_veure()" <?php if($desabilitat) echo 'disabled'?>>
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
            <?php if($esAdmin) { ?>
                <select name="id_usuari" required id="">
                    <option value="">Selecciona un tecnic</option>
                </select>
            <?php } else { ?>
                <input type="hidden" name="id_usuari" value="<?= $incidencia['id_usuari'] ?>">
                <input type="text" value="<?= $incidencia['NomAsignat'] ?>" readonly>
            <?php } ?>
        </div>
        <div class="form_element">
            <label for="prioritat">Prioritat</label>
            <div class="radio_group">
                <input type="radio" name="prioritat" value="baixa" id="prioritat_1" required
                    <?php if($incidencia['prioritat'] == 'baixa') echo 'checked'; ?>
                    <?php if($desabilitat) echo 'disabled'; ?>>
                <label for="prioritat_1">Baixa</label>

                <input type="radio" name="prioritat" value="mitjana" id="prioritat_2" required
                    <?php if($incidencia['prioritat'] == 'mitjana') echo 'checked'; ?>
                    <?php if($desabilitat) echo 'disabled'; ?>>
                <label for="prioritat_2">Moderada</label>

                <input type="radio" name="prioritat" value="alta" id="prioritat_3" required
                    <?php if($incidencia['prioritat'] == 'alta') echo 'checked'; ?>
                    <?php if($desabilitat) echo 'disabled'; ?>>
                <label for="prioritat_3">Alta</label>
            </div>
        </div>
        <div class="form_element">
            <label for="estat">Estat</label>
            <div class="radio_group">
                <input type="radio" name="estat" value="pendent" id="estat_1" required
                    <?php if($incidencia['estat'] == 'pendent') echo 'checked'; ?>
                    <?php if($desabilitat) echo 'disabled'; ?>>
                <label for="estat_1">Pendent</label>

                <input type="radio" name="estat" value="enproces" id="estat_2" required
                    <?php if($incidencia['estat'] == 'enproces') echo 'checked'; ?>
                    <?php if($desabilitat) echo 'disabled'; ?>>
                <label for="estat_2">EnProces</label>

                <input type="radio" name="estat" value="resolta" id="estat_3" required
                    <?php if($incidencia['estat'] == 'resolta') echo 'checked'; ?>
                    <?php if($desabilitat) echo 'disabled'; ?>>
                <label for="estat_3">Resolta</label>
            </div>
        </div>

        <div class="form_element">
            <label for="descripcio">Descripcio</label>
            <textarea name="descripcio" id="descripcio" cols="30" rows="10" <?php if($desabilitat) echo 'readonly'?>><?= $incidencia['descripcio'] ?></textarea>
        </div>
        <div class="form_element">
            <label for="data_creacio">Data de creació</label>
            <input type="text" name="data_creacio" id="data_creacio" value="<?= $incidencia['data_creacio'] ?>" readonly>
        </div>
        <?php if($incidencia['darrera_modificacio'] != NULL) {?>
            <div class="form_element">
                <label for="data_creacio">Darrera modificacio</label>
                <input type="text" name="data_creacio" id="data_creacio" value="<?= $incidencia['darrera_modificacio'] ?>" readonly>
            </div>
        <?php } ?>
        <div class="form_element">
            <label for="localitzacio">Localització</label>
            <input type="text" name="localitzacio" id="localitzacio" value="<?= $incidencia['nom_espai'] ?>" readonly>
        </div>
        <div class="form_element">
            <label for="uploads">Imatges</label>
            <div class="uploads">
                <?php if ($incidencia['upload'] != ''): ?>
                    <div class="gallery-slider">
                        <?php
                            $uploads = json_decode($incidencia['upload'], true);
                            foreach ($uploads as $upload) {
                                if (!empty($upload)) {
                                    echo '<a href="../public/assets/upload/' . $upload . '" data-lightbox="gallery" data-title="Imatge">
                                            <img src="../public/assets/upload/' . $upload . '" alt="imatge">
                                        </a>';
                                }
                            }
                        ?>
                    </div>
                <?php else: ?>
                    <p>No hi ha imatges.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="form_element">
            <label for="upload">Afegir imatges/arxius</label>
            <input type="file" name="upload[]" id="imatge" accept="image/*" multiple <?php if($desabilitat) echo 'disabled'; ?>>
        </div>
        <div class="form_element">
            <?php if(!$desabilitat) { ?>
            <button type="submit" name="action" value="actualitzar">Actualitzar</button>
            <button type="submit" name="action" value="eliminar">Eliminar</button>
            <?php } ?>
        </div>
    </form>
</div>

<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'fadeDuration': 300
    });

    $(document).ready(function() {
        select_tecnics_veure();
    });
    function select_tecnics_veure() {
        const idTipusIncidencia = document.getElementById('id_tipo_incidencia').value;
        const id_usuari = <?= json_encode($incidencia['id_usuari']); ?>;
        if (idTipusIncidencia) {
            fetch(`../controllers/IncidenciaController.php?action=obtenir_tecnics&id_tipus_incidencia=${idTipusIncidencia}`)
                .then(response => response.json())
                .then(data => {
                    const tecnicsSelect = document.querySelector('select[name="id_usuari"]');
                    tecnicsSelect.innerHTML = '';
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Selecciona un tècnic';
                    tecnicsSelect.appendChild(defaultOption);
                    data.forEach(tecnic => {
                        const option = document.createElement('option');
                        option.value = tecnic.id_usuari;
                        option.textContent = tecnic.nom + (tecnic.rol === 'administrador' ? ' - Administrador' : '');
                        if (tecnic.id_usuari == id_usuari) {
                            option.selected = true;
                        }

                        tecnicsSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        } else {
            const tecnicsSelect = document.querySelector('select[name="id_usuari"]');
            tecnicsSelect.innerHTML = '<option value="">Selecciona un tècnic</option>';
        }
    }
</script>

