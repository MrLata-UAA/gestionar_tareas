<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
     $fecha_created = date('Y-m-d', strtotime($fecha_incio));
    $fecha_updated = date('Y-m-d', strtotime($fecha_limite));

    $sql = "INSERT INTO usuarios (nombre, email, contrasenha,created_at, updated_at) VALUES ('$nombre', '$email', '$password',$fecha_created,$fecha_updated)";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST">
    <h2>Registro</h2>
    Nombre: <input type="text" name="nombre" required><br>
    Email: <input type="email" name="email" required><br>
    Contraseña: <input type="password" name="password" required><br>
    <button type="submit">Registrarse</button>
</form>
