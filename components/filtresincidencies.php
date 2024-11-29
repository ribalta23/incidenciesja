<div class="filtres_container">
    <h3>Filtrar incidencies</h3>
    <form action="../public/index.php?action=filtres" method="post">
        <div class="form_element">
            <label for="usuari">Busca per usuari</label>
            <select name="usuari" id="">
                <option value="">Tots els usuaris</option>
                <?php $usuaris = $controller->obtenir_usuaris(); ?>
                <?php if ($usuaris && $usuaris->num_rows > 0) : ?>
                    <?php while ($usuari = $usuaris->fetch_assoc()) : ?>
                        <option value="<?= $usuari['id_usuari'] ?>"><?= $usuari['nom'] ?></option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form_element">
            <label for="tipus">Busca per tipus</label>
            <select name="tipus" id="">
                <option value="">Tots els tipus</option>
                <?php $tipus = $controller->obtenir_tipus_incidencia(); ?>
                <?php if ($tipus && $tipus->num_rows > 0) : ?>
                    <?php while ($tipu = $tipus->fetch_assoc()) : ?>
                        <option value="<?= $tipu['id_tipus_incidencia'] ?>"><?= $tipu['nom'] ?></option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form_element">
            <label for="prioritat">Busca per prioritat</label>
            <select name="prioritat" id="">
                <option value="">Totes les prioritats</option>
                <option value="baixa">Baixa</option>
                <option value="mitjana">Mitjana</option>
                <option value="alta">Alta</option>
            </select>
        </div>
        <div class="form_element">
            <label for="estat">Busca per estat</label>
            <select name="estat" id="">
                <option value="">Tots els estats</option>
                <option value="pendent">Pendent</option>
                <option value="enproces">En Proces</option>
                <option value="resolta">Resolta</option>
            </select>
        </div>
        <div class="form_element">
            <label for="estat">Localitzacio</label>
            <select name="tipus_localitzacio" id="tipus_localitzacio" onchange="mostrarElementos()">
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
            <label for="data">Busca per dia</label>
            <input type="date" name="data" id="">
        </div>
        <div class="form_element">
            <button type="submit">Buscar</button>
        </div>
    </form>
</div>
