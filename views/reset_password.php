<?php
    include_once '../controllers/UsuariController.php';
    $controller = new UsuariController();

    if(!$controller->existeisToken($_GET['token'])) {
        echo "<script>alert('Token no vàlid.');</script>";
        header('Location: ../public/index.php');
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
  <title>IncidenciesJa! - Reset Contrasenya</title>
</head>
<body>
  <div class="login_container">
    <img src="../public/assets/brand/logo_simbol_white.png" alt="Logo">
    <span>Reset Contrasenya</span>
    <form class="login_form" method="POST" action="../controllers/UsuariController.php?action=resetPassword">
      <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" />
      <div class="input_group">
        <i class="fas fa-lock"></i>
        <input required placeholder="Nova Contrasenya" name="new_password" type="password" />
      </div>
      <button type="submit">Reset</button>
    </form>
  </div>
</body>
</html>