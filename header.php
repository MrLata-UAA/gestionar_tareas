<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Tareas</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Asegúrate de incluir SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Estilos propios -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- assets\img\icon\favicon.ico -->
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icon/favicon-16x16.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icon/favicon.ico">
  <link rel="manifest" href="assets/img/icon/site.webmanifest">

</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="tareas.php">Gestión de Tareas</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['usuario_id'])) { ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Salir</a></li>
          <?php } else { ?>
            <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Registro</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">