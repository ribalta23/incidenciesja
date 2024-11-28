
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
    <span>Recuperar Contrasenya</span>
    <form class="login_form" method="POST" action="../controllers/UsuariController.php?action=sendRecoveryEmail">
      <div class="input_group">
        <i class="fas fa-envelope"></i>
        <input required name="email" placeholder="Email" type="email" />
      </div>
      <button type="submit">Enviar</button>
      <div class="recover_password">
      <a href="../public/index.php?action=login">Iniciar sessió</a>
    </div>
    </form>
  </div>
</body>
</html>