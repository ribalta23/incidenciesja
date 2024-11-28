<?php

if (!defined('ACCESS_ALLOWED')) {
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
  <title>IncidenciesJa! - Login</title>
</head>
<body>
  <div class="login_container">
    <img src="../public/assets/brand/logo_simbol_white.png" alt="Logo">
    <span>Login</span>
    <form class="login_form" method="POST" action="../controllers/UsuariController.php?action=login">
      <div class="input_group">
        <i class="fas fa-user"></i>
        <input required name="email" placeholder="Email" type="email" />
      </div>
      <div class="input_group">
        <i class="fas fa-lock"></i>
        <input required placeholder="Password" name="contrasenya" type="password" />
      </div>
      <button type="submit">Entrar</button>
    </form>
    <div class="recover_password">
      <a href="../views/solicitartoken.php">Recuperar Contrasenya</a>
    </div>
  </div>
</body>
</html>