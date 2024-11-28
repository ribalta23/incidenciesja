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
?>

<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=mostrarUsuaris"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Afegir usuari</h2>
        </div>
        <img class="img_user" id="preview" src="#" alt="Previsualización de la imagen" style="display: none;">
    </div>
    <form action="../controllers/GestorUsuarisController.php?action=afegirUsuaris" method="POST" enctype="multipart/form-data">
        <div class="form_element">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>
        </div>
        <div class="form_element">
            <label for="cognoms">Cognoms</label>
            <input type="text" name="cognoms" id="cognoms" required>
        </div>
        <div class="form_element">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required oninput="verificarCorreu()">
                <span id="email-error" class="error-message" style="display: none;">El correu electrònic ja esta registrat</span>
            </div>
        <div class="form_element">
            <label for="password">Contrasenya</label>
            <div class="password-container">
                <input type="password" name="password" id="password" required>
                <button type="button" id="togglePassword" class="toggle-password">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="form_element">
            <label for="telefon">Telèfon</label>
            <input type="text" name="telefon" id="telefon" required pattern="\d*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
        </div>
        <div class="form_element">
            <label for="rol">Rol</label>
            <select name="rol" id="rol" required onchange="handleRoleChange(event)">
                <?php if (isset($roles)): ?>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol ?>"><?= ucfirst($rol) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form_element" id="tipus-container">
            <label for="tipus">Sector</label>
            <select name="tipus" id="tipus">
                <option value="">Selecciona el sector</option>
                <?php if (isset($tipus)): ?>
                    <?php foreach ($tipus as $tip): ?>
                        <option value="<?= $tip['id_tipus_incidencia'] ?>"><?= ucfirst($tip['nom']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>    
        <div class="form_element">
            <label for="imatge" class="upload-label">Afegir imatge</label>
            <input type="file" name="imatge" id="imatge" onchange="previewImage(event)">
        </div>
        <div class="form_element">
            <button type="submit" id="submit-button">Afegir Usuari</button>
        </div>
    </form>
</div>

<script>
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

function handleRoleChange(event) {
    const role = event.target.value;
    const tipusContainer = document.getElementById('tipus-container');
    const tipusSelect = document.getElementById('tipus');

    if (role.toLowerCase() === 'usuari') {
        tipusContainer.style.display = 'none';
        tipusSelect.removeAttribute('name'); // Remove name attribute to not send it
    } else {
        tipusContainer.style.display = 'flex';
        tipusSelect.setAttribute('name', 'tipus'); // Add name attribute back
    }
}
function verificarCorreu() {
            const email = document.getElementById('email').value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `../controllers/GestorUsuarisController.php?action=verificarCorreu&email=${encodeURIComponent(email)}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const emailError = document.getElementById('email-error');
                    const submitButton = document.getElementById('submit-button');
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

// Verificar el rol seleccionado al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('rol');
    handleRoleChange({ target: roleSelect });
});
</script>