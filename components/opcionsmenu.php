<?php if (!$permis->tePermisUsuari()) { ?>
    <a href="../public/index.php?action=dashboardAdmin"><i class="fa-solid fa-table-columns"></i><span>Dashboard</span></a>
<?php } ?>
<div class="desplegable_options">
    <a href="../public/index.php?action=incidencies"><i class="fa-solid fa-triangle-exclamation"></i><span>Incidencies</span></a>
</div>
<a href="../public/index.php?action=xats"><i class="fa-solid fa-message"></i><span>Missatges</span></a>
<a href="../public/index.php?action=notificacions"><i class="fa-regular fa-bell"></i><span>Notificacions</span></a>
<a href="../public/index.php?action=calendari"><i class="fa-regular fa-calendar"></i><span>Calendari</span></a>
<?php if($permis->tePermisAdmin()) { ?>
    <a href="../public/index.php?action=mostrarUsuaris"><i class="fa-solid fa-users"></i><span>Gestio d'usuaris</span></a>
    <a href="../public/index.php?action=configuracio"><i class="fa-solid fa-gear"></i><span>Configuració</span></a>
<?php } ?>
<a href="../controllers/UsuariController.php?action=logout"><i class="fa-solid fa-right-from-bracket"></i><span>Tancar Sessió</span></a>