<?php
// Inicializar la sesión, incliuye la conexión a la base de datos y el encabezado con las variables de error y tarea
session_start();

include("db.php");
include("header.php");
$error = [];
$tarea = null;

// Validar transición de estados
$transiciones_validas = [
    'pendiente'   => ['pendiente', 'en progreso'], // solo puede quedarse igual o ir a En Progreso
    'en progreso' => ['en progreso', 'completada'], // solo puede quedarse igual o ir a Completada
    'completada'  => ['completada'] // no puede cambiar
];



// Verificar usuario
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}


// Obtener ID de la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//Verificar el ID Tarea
if ($id <= 0) {
    $error[] = ['msg' => "ID de tarea no válido.", 'tipo' => 'warning'];
} else {
    //Ubicar la tarea para la edición
    $stmt = $conn->prepare("SELECT * FROM tareas WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $tarea = $result->fetch_assoc();

    //Verificar que la tarea exista
    if (!$tarea) {
        $error[] = ['msg' => "Tarea no encontrada o no pertenece al usuario.", 'tipo' => 'warning'];
    }
    // Estado actual de la tarea
    $estado_actual = $tarea['estado'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['inicio'];
    $fecha_limite = $_POST['limite'];
    $usuario_id = $_SESSION['usuario_id'];
    $estado_nuevo = $_POST['estado'];
    $fecha_updated = date("Y-m-d H:i:s");

    if (empty($titulo)) {
        $error[] = ['msg' => "El título es obligatorio.", 'tipo' => 'warning'];
    } elseif (strlen($titulo) > 255) {
        $error[] = ['msg' => "El título no puede tener más de 255 caracteres.", 'tipo' => 'warning'];
    }


    // Validar descripción
    if (!empty($descripcion) && strlen($descripcion) > 1000) {
        $error[] = ['msg' => "La descripción no puede superar los 1000 caracteres.", 'tipo' => 'warning'];
    }

    // Validar fechas
    if (empty($fecha_inicio) || empty($fecha_limite)) {
        $error[] = ['msg' => "Ambas fechas son obligatorias.", 'tipo' => 'warning'];;
    } elseif (strtotime($fecha_inicio) > strtotime($fecha_limite)) {
        $error[] = ['msg' => "La fecha de inicio no puede ser mayor que la fecha límite.", 'tipo' => 'warning'];
    }

    // Validar estado
    if (!in_array($estado_nuevo, ['pendiente', 'en progreso', 'completada'])) {
        $error[] = ['msg' => "Estado de tarea inválido.", 'tipo' => 'warning'];
    }


    if (!in_array($estado_nuevo, $transiciones_validas[$estado_actual])) {
        $error[] = ['msg' => "No se puede cambiar de <strong>$estado_actual</strong> a <strong>$estado_nuevo</strong>.", 'tipo' => 'danger'];
    }

    // Si no hay errores, insertar en la base
    if (empty($error)) {
        // Asumiendo $conn es tu conexión MySQLi
        //Actualizando la tarea
        $sql = "UPDATE tareas 
            SET titulo = ?, 
                descripcion = ?, 
                estado = ?, 
                fecha_inicio = ?, 
                fecha_limite = ?, 
                updated_at = ?
            WHERE id = ? AND usuario_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssii",
            $titulo,
            $descripcion,
            $estado_nuevo,
            $fecha_inicio,
            $fecha_limite,
            $fecha_updated,
            $id,
            $usuario_id
        );
        if ($stmt->execute()) {
            $_SESSION['flash'] = ['msg' => "Tarea actualizada correctamente", 'tipo' => 'success'];
            header("Location: tareas.php");
            exit();
        } else {
            $error[] = ['msg' =>  "Error: " . $stmt->error, 'tipo' => 'danger'];
        }
    }
}
?>
<div class="card mb-4 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="display-6 mb-0">✏️ Editor: <?php echo $_SESSION['nombre']; ?></h2>
        <a href="tareas.php" class="btn btn-outline-secondary">🔙 Volver a tareas</a>
    </div>
</div>

<?php $disabled = ($tarea['estado'] == 'completada') ? 'disabled' : ''; ?>

<?php if ($tarea): ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="mb-3">Editar Tarea</h3>
                    <form method="POST" class="form-control">
                        <h4 class="mb-3">Modificando una tarea existente
                            <?php if ($tarea['estado'] == 'completada'): ?>
                                <span class="ms-2 text-success">🔒</span>
                            <?php endif; ?>
                        </h4>
                        <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
                        <div class="mb-3">
                            <label for="titulo">Título: </label>
                            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($tarea['titulo']) ?>" <?= $disabled ?> required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion">Descripci&oacute;n:</label>
                            <textarea name="descripcion" class="form-control" <?= $disabled ?>><?= htmlspecialchars($tarea['descripcion']) ?></textarea>
                        </div>

                        <!-- Fechas en la misma línea -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Fecha inicio:</label>
                                <input type="date" name="inicio" class="form-control" value="<?= $tarea['fecha_inicio'] ?>" <?= $disabled ?>>
                            </div>
                            <div class="col-md-6">
                                <label>Fecha límite:</label>
                                <input type="date" name="limite" class="form-control" value="<?= $tarea['fecha_limite'] ?>" <?= $disabled ?>>
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
                            <select name="estado" id="estado" class="form-select" <?= $disabled ?>>
                                <?php if ($tarea['estado'] == 'pendiente'): ?>
                                    <option value="pendiente" <?= $tarea['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="en progreso">En Progreso</option>
                                <?php elseif ($tarea['estado'] == 'en progreso'): ?>
                                    <option value="en progreso" <?= $tarea['estado'] == 'en progreso' ? 'selected' : '' ?>>En Progreso</option>
                                    <option value="completada">Completada</option>
                                <?php else: ?>
                                    <option value="completada" selected>Completada</option>
                                <?php endif; ?>
                            </select>

                        </div>
                        <div class="mb-3">
                            <?php if ($tarea['estado'] != 'completada'): ?>
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-success" value="Guardar">
                                    <input type="reset" class="btn btn-warning" value="Cancelar">
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    ✅ Esta tarea ya está <strong>completada</strong> y no puede ser editada.
                                </div>
                            <?php endif; ?>
                            <!-- <a href="tareas.php" class="btn btn-warning">Cancelar</a> -->

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Mostrar errores con iconos -->
<?php if (!empty($error)): ?>
    <div class="mt-3">
        <?php foreach ($error as $e): ?>
            <div class="alert alert-<?= $e['tipo'] ?> d-flex align-items-center">
                <?= $icons[$e['tipo']] ?? '❓' ?>
                <span class="ms-2"><?= $e['msg'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['tipo'] ?> d-flex align-items-center">
        <?= ($_SESSION['flash']['tipo'] == 'success') ? '✅'
            : (($_SESSION['flash']['tipo'] == 'warning') ? '⚠️'
                : (($_SESSION['flash']['tipo'] == 'info') ? 'ℹ️' : '❌')) ?>
        <span class="ms-2"><?= $_SESSION['flash']['msg'] ?></span>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>


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
        switch (select.value) {
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