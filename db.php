<?php
$host = "localhost";
$user = "root";
$pass = "Admin12345"; // tu contraseña de MySQL
$db   = "gestionar_tareas";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>