<?php
session_start();
include("db.php");
include("header.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $contra = $_POST['contrasenha'];
   // $hash = password_hash($contra, PASSWORD_DEFAULT);

    $sql = "SELECT us.* FROM gestionar_tareas.usuarios us WHERE us.email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

          if (password_verify($contra, $usuario['contrasenha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            header("Location: tareas.php");
            exit();
        } else {
             $error = "Contraseña incorrecta";
        }
    } else {
            $error = "Usuario no encontrado";
    }
   
}
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Iniciar Sesión</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="contrasenha" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
                <div class="text-center mt-3">
                    <a href="register.php">Registrarse</a>
                </div>
            </div>
        </div>

        <!-- Card de error debajo -->
        <?php if (!empty($error)): ?>
            <div class="card mt-3 shadow-sm">
              <div class="card-body">
                <?php if ($error == "Usuario no encontrado"): ?>
                  <div class="alert alert-warning mb-0 uppercase">
                    ⚠️ <?= $error ?>
                  </div>
                <?php else: ?>
                  <div class="alert alert-danger mb-0 uppercase">
                    ❌ <?= $error ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            
        <?php endif; ?>
        <?php $error = ""; ?>
        </div>
    </div>
</div>


<?php include("footer.php"); ?>
