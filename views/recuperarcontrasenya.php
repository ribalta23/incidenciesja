<?php


$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    header('Location: /404.php');
    exit;
}

include_once '../config/Database.php';
include_once '../models/Recuperar.php';

$database = new Database();
$db = $database->connect();
$recuperar = new Recuperar($db);

if (!$recuperar->verificarToken($token)) {
    header('Location: /404.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="../public/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../public/css/login.css">
  <title>IncidenciesJa! - Recuperar Contrasenya</title>
</head>
<body>
  <div class="login_container">
    <img src="../public/assets/brand/logo_simbol_white.png" alt="Logo">
    <span>RECUPERAR CONTRASENYA</span>
    <form class="login_form" method="POST" action="../controllers/RecuperarController.php?action=recuperarContrasenya">
      <div class="input_group">
        <i class="fas fa-key"></i>
        <input required name="token" placeholder="Token" type="text" value="<?= htmlspecialchars($token) ?>" readonly />
      </div>
      <div class="input_group">
        <i class="fas fa-lock"></i>
        <input required name="new_password" placeholder="Nueva Contrasenya" type="password" />
      </div>
      <button type="submit">Enviar</button>
    </form>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'token_invalido'): ?>
      <p class="error_message">El token es inválido o ha expirado.</p>
    <?php endif; ?>
  </div>
</body>
</html>