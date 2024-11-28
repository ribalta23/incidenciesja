<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="../public/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../public/css/login.css">
  <title>IncidenciesJa! - Solicitar Token</title>
</head>
<body>
  <div class="login_container">
    <img src="../public/assets/brand/logo_simbol_white.png" alt="Logo">
    <span>Solicitar Token</span>
    <form class="login_form" method="POST" action="../controllers/RecuperarController.php?action=solicitarToken">
      <div class="input_group">
        <i class="fas fa-envelope"></i>
        <input required name="email" placeholder="Email" type="email" />
      </div>
      <button type="submit">Enviar Token</button>
    </form>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'email_no_registrado'): ?>
      <p class="error_message">El correo electrónico no está registrado.</p>
    <?php endif; ?>
  </div>
</body>
</html>