<?php
session_start();
include("db.php");
include("header.php");

$error = [];
$tarea = null; // Inicializar
$icons = [
    'success' => '✅',
    'warning' => '⚠️',
    'info'    => 'ℹ️',
    'danger'  => '❌'
];


// Verificar usuario
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión
if ($conn->connect_error) {
    $error[] = ['msg' => "Conexión fallida: " . $conn->connect_error, 'tipo' => 'danger'];
}

// Obtener ID de la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    $error[] = ['msg' => "ID de tarea no válido.", 'tipo' => 'warning'];
} else {
    $stmt = $conn->prepare("SELECT * FROM tareas WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $tarea = $result->fetch_assoc();

    if (!$tarea) {
        $error[] = ['msg' => "Tarea no encontrada o no pertenece al usuario.", 'tipo' => 'warning'];
    }
}

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $tarea) {
    $id = intval($_POST['id']);

    if ($tarea['estado'] == 'completada') {
        $error[] = ['msg' => "No se puede eliminar una tarea completada.", 'tipo' => 'warning'];
    } elseif ($tarea['fecha_limite'] < date("Y-m-d")) {
        $error[] = ['msg' => "No se puede eliminar una tarea con fecha límite pasada.", 'tipo' => 'warning'];
    } else {
        $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
        if ($stmt->execute()) {
            header("Location: tareas.php?msg=eliminada");
            exit();
        } else {
            $error[] = ['msg' => "Error al eliminar la tarea: " . $stmt->error, 'tipo' => 'danger'];
        }
    }
}

if (isset($stmt)) $stmt->close();
?>

<div class="card mb-4 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="display-6 mb-0">✏️ Editor: <?php echo $_SESSION['nombre']; ?></h2>
        <a href="tareas.php" class="btn btn-outline-secondary">🔙 Volver a tareas</a>
    </div>
</div>

<?php if ($tarea): ?>
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h3>Confirmar eliminación de la tarea</h3>
        <p><strong>Título:</strong> <?= htmlspecialchars($tarea['titulo']) ?></p>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($tarea['descripcion']) ?></p>
        <p><strong>Fecha inicio:</strong> <?= $tarea['fecha_inicio'] ?></p>
        <p><strong>Fecha límite:</strong> <?= $tarea['fecha_limite'] ?></p>
        <p><strong>Estado:</strong>
            <span class="badge bg-<?=
                ($tarea['estado'] == "pendiente") ? "warning" : 
                (($tarea['estado'] == "en progreso") ? "info" : "success");
            ?>">
                <?= $tarea['estado']; ?>
            </span>
        </p>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
            <button type="submit" name="confirm" class="btn btn-danger">❌ Eliminar tarea</button>
            <a href="tareas.php" class="btn btn-secondary">Cancelar</a>
        </form>
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

<?php include("footer.php"); ?>
