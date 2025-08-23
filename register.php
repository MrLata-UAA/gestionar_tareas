<?php
session_start();
include("db.php");
include("header.php");
$error = [];
$icons = [
    'success' => '✅',
    'warning' => '⚠️',
    'info'    => 'ℹ️',
    'danger'  => '❌'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];


    $fecha_created = date("Y-m-d H:i:s");
    $fecha_updated = date("Y-m-d H:i:s");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = ['msg' => "Formato de email inválido.", 'tipo' => 'warning'];
    } else {
        $email = strtolower($email);
    }
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error[] = ['msg' =>  "Las contraseñas no coinciden.", 'tipo' => 'warning'];
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $sql = "INSERT INTO usuarios (nombre, email, contrasenha, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $nombre, $email, $password, $fecha_created, $fecha_updated);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error[] = ['msg' => "Error al registrar: " . $stmt->error, 'tipo' => 'danger'];
        }
    } else {
        $error[] = ['msg' => "Error en la preparación de la consulta: " . $conn->error, 'tipo' => 'danger'];
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="mb-3">Nuevo Usuario</h3>
                <form method="POST" class="form-control">
                    <h4 class="mb-3">Registrando un nuevo usuario</h4>
                    <div class="mb-3">
                        <label for="nombre"> Nombre: </label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email"> Email: </label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Contraseña: </label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Confirmar Contraseña: </label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Card de error debajo -->
<?php if (!empty($error)): ?>
    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <?php foreach ($error as $e): ?>
                <div class="alert alert-<?= $e['tipo'] ?> d-flex align-items-center">
                    <?= $icons[$e['tipo']] ?? '❓' ?>
                    <span class="ms-2"><?= $e['msg'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
<?php endif; ?>
<?php include("footer.php"); ?>