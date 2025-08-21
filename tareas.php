<?php
session_start();
include("db.php");
if (!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit(); }
include("header.php");

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT t.id,  t.titulo,  t.descripcion,  t.estado, t.fecha_inicio, t.fecha_limite FROM tareas t WHERE t.usuario_id = $usuario_id ORDER BY t.id, t.created_at ASC";
$result = $conn->query($sql);
?>
 <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Bienvenido <?php echo $_SESSION['nombre']; ?> 👋 </h2>  
 </div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="crear_tarea.php" class="btn btn-success">➕ Crear nueva tarea </a>
</div>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
    <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Estado</th>
         <th>Fecha Inicio</th>
        <th>Fecha Límite</th>
        <th>Acciones</th>
    </tr>
     </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['titulo']; ?></td>
        <td><?php echo $row['descripcion']; ?></td>
        <td>
            <span class="badge bg-<?php 
                echo ($row['estado']=="pendiente") ? "warning" : 
                     (($row['estado']=="en progreso") ? "info" : "success"); ?>">
                <?php echo $row['estado']; ?>
            </span>
        </td>
        <td><?php echo $row['fecha_inicio']; ?></td>
        <td><?php echo $row['fecha_limite']; ?></td>
        <td>
            <a href="editar_tarea.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
            <a href="eliminar_tarea.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger btn-delete">🗑️ Eliminar</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php include("footer.php"); ?>