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
    $roles = $controller->obtenirRol();
    $tipus = $controller->obtenirTipus();
    $usuari = $controller->obtenirUsuari($_GET['id']);
?>

<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=mostrarUsuaris"><i class="fa-solid fa-angle-left"></i></a>
            <h2 id ="pageTitle">Visualitzar usuari</h2>
        </div>
        <img id="preview" class="img_user_edit" src="../public/assets/profile/<?= $usuari['imatge'] ?>" alt="Previsualización de la imagen">
    </div>
    <form action="../controllers/GestorUsuarisController.php" method="POST" enctype="multipart/form-data" id="editForm">
        <input type="hidden" name="id" value="<?= $usuari['id_usuari'] ?>">
        <input type="hidden" name="imatge_actual" value="<?= $usuari['imatge'] ?>">
        <div class="form_element">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($usuari['nom']) ?>" required disabled>
        </div>
        <div class="form_element">
            <label for="cognoms">Cognoms</label>
            <input type="text" name="cognoms" id="cognoms" value="<?= htmlspecialchars($usuari['cognoms']) ?>" required disabled>
        </div>
        <div class="form_element">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($usuari['email']) ?>" required disabled oninput="verificarCorreo()">
            <span id="email-error" class="error-message" style="display: none;">El correu electronic ja esta registrat</span>
        </div>
        <div class="form_element">
            <label for="password">Nova Contrasenya</label>
            <div class="password-container">
                <input type="password" name="password" id="password" disabled>
                <button type="button" id="togglePassword" class="toggle-password" disabled>
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            <small>Deixa en blanc si no vols canviar la contrasenya</small>
        </div>
        <div class="form_element">
            <label for="telefono">Telèfon</label>
            <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($usuari['telefono']) ?>" required disabledpattern="\d*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
        </div>
        <div class="form_element">
            <label for="rol">Rol</label>
            <select name="rol" id="rol" required onchange="handleRoleChange(event)" disabled>
                <?php if (isset($roles)): ?>
                    <option value="<?= $usuari['rol'] ?>" selected><?= ucfirst($usuari['rol']) ?></option>
                    <?php foreach ($roles as $rol): ?>
                        <?php if ($rol != $usuari['rol']): ?>
                            <option value="<?= $rol ?>"><?= ucfirst($rol) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form_element" id="tipus-container">
            <label for="tipus">Tipus</label>
            <select name="tipus" id="tipus" onchange disabled>
                <?php if (isset($tipus)): ?>
                    <option value="<?= $usuari['id_sector'] ?>" selected><?= ucfirst($usuari['tipus_nom']) ?></option>
                    <?php foreach ($tipus as $tip): ?>
                        <?php if ($tip['id_tipus_incidencia'] != $usuari['id_sector']): ?>
                            <option value="<?= $tip['id_tipus_incidencia'] ?>"><?= ucfirst($tip['nom']) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form_element">
            <label for="imatge" class="upload-label">Afegir imatge</label>
            <input type="file" name="imatge" id="imatge" onchange="previewImage(event)" disabled>
        </div>
        <div class="form_element">
            <button type="button" id="editButton" onclick="enableEditing()">Editar</button>
            <button type="submit" name="action" value="editarUsuaris" id="saveButton" style="display: none;">Guardar Canvis</button>
        </div>
        <div class="form_element" id="deleteButtonContainer" style="display: none;">
            <a href="../controllers/GestorUsuarisController.php?action=eliminarUsuari&id=<?= $usuari['id_usuari'] ?>">
                <button type="button" id="deleteButton">Eliminar</button>
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    const reader = new FileReader();

    reader.onload = function() {
        preview.src = reader.result;
        preview.style.display = 'block';
    };

    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}

function handleRoleChange(event) {
        const role = event.target.value;
        const tipusContainer = document.getElementById('tipus-container');
        const tipusSelect = document.getElementById('tipus');

        if (role.toLowerCase() === 'usuari') {
            tipusContainer.style.display = 'none';
            tipusSelect.removeAttribute('required');
            tipusSelect.removeAttribute('name');
        } else {
            tipusContainer.style.display = 'flex';
            tipusSelect.setAttribute('required', 'required');
            tipusSelect.setAttribute('name', 'tipus');
        }
    }

    function enableEditing() {
        const formElements = document.querySelectorAll('#editForm input, #editForm select, #editForm button');
        formElements.forEach(element => {
            element.disabled = false;
        });
        document.getElementById('editButton').style.display = 'none';
        document.getElementById('saveButton').style.display = 'inline-block';
        document.getElementById('deleteButtonContainer').style.display = 'block';
        document.getElementById('pageTitle').textContent = 'Editar usuari';
    }

function verificarCorreo() {
            const email = document.getElementById('email').value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `../controllers/GestorUsuarisController.php?action=verificarCorreu&email=${encodeURIComponent(email)}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const emailError = document.getElementById('email-error');
                    const submitButton = document.getElementById('saveButton');
                    if (response.existe) {
                        emailError.style.display = 'block';
                        submitButton.disabled = true;
                    } else {
                        emailError.style.display = 'none';
                        submitButton.disabled = false;
                    }
                }
            };
            xhr.send();
        }


document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('rol');
    handleRoleChange({ target: roleSelect });
});
</script>