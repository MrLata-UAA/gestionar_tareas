<?php
session_start();
include("db.php");
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_incio = $_POST['inicio'];
    $fecha_limite = $_POST['limite'];
    $usuario_id = $_SESSION['usuario_id'];
    $estado = $_POST['estado']; 
    $fecha_created = date('Y-m-d', strtotime($fecha_incio));
    $fecha_updated = date('Y-m-d', strtotime($fecha_limite));

   echo "Estado: '$estado'<br/>";

    // Asumiendo $conn es tu conexión MySQLi
    $sql= "INSERT INTO tareas (usuario_id, titulo, descripcion, estado, fecha_inicio, fecha_limite, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $usuario_id, $titulo, $descripcion, $estado, $fecha_inicio, $fecha_limite, $fecha_created, $fecha_updated);
    if ($stmt->execute()) {
        echo "Tarea insertada correctamente";
        header("Location: tareas.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="mb-3">Nueva Tarea</h3>
                <form method="POST" class="form-control">
                    <h4 class="mb-3">Registrando una nueva tarea</h4>
                    <div class="mb-3">
                        <label for="titulo">Título: </label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion">Descripci&oacute;n:</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>

                    <!-- Fechas en la misma línea -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Fecha inicio:</label>
                            <input type="date" name="inicio" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Fecha límite:</label>
                            <input type="date" name="limite" class="form-control">
                        </div>
                    </div>
                    <!-- Fechas en la filas distintas línea -->
                    <!-- <div class="mb-3">
                        <label for="inicio">Fecha inicio:
                            <input type="date" name="inicio" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="limite">Fecha límite:
                            <input type="date" name="limite" class="form-control">
                    </div> -->
                    <!-- Estado de la tarea 
                            'pendiente','en progreso','completada'
                    -->
                    <div class="mb-3">
                        <label for="estado">Estado:</label>
                        <select name="estado" id="estado" class="form-select ">
                            <option value="pendiente" selected><span class="bs-warning-bg-subtle">Pendiente</span></option>
                            <option value="en progreso" class="bs-info-bg-subtle">En Progreso</option>
                            <option value="completada" class="bs-success-bg-subtle">Completada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-success" value="Guardar">
                        <input type="reset" class="btn btn-warning" value="Cancelar">
                        <!-- <a href="tareas.php" class="btn btn-warning">Cancelar</a> -->

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end mt-3 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="tareas.php" class="btn btn-link">🔙 Volver a tareas</a>
    </div>
</div>

<script>
  const select = document.getElementById('estado');

  function actualizarColor() {
    // Limpiar clases de color
    select.classList.remove('bg-warning', 'bg-info', 'bg-success', 'text-dark', 'text-white');

    // Aplicar según el valor
    switch(select.value) {
      case 'pendiente':
        select.classList.add('bg-warning', 'text-dark');
        break;
      case 'en progreso':
        select.classList.add('bg-info', 'text-white');
        break;
      case 'completada':
        select.classList.add('bg-success', 'text-white');
        break;
    }
  }

  // Inicial
  actualizarColor();

  // Cambiar al seleccionar otra opción
  select.addEventListener('change', actualizarColor);
</script>

<?php include("footer.php"); ?>