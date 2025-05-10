<?php
session_start();
include 'db.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener la tarea a editar
if (isset($_GET['id'])) {
    $tarea_id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Consulta para obtener la tarea
    $stmt = $conexion->prepare("SELECT * FROM tareas WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $tarea_id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $tarea = $resultado->fetch_assoc();
    } else {
        header("Location: index.php"); // Redirige si no encuentra la tarea
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    if (empty($titulo)) {
        $error = "El título no puede estar vacío.";
    } else {
        // Actualizar la tarea
        $stmt = $conexion->prepare("UPDATE tareas SET titulo = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("ssi", $titulo, $descripcion, $tarea_id);
        $stmt->execute();
        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Editar Tarea</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>

    <form method="POST" class="mb-3">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($tarea['titulo']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"><?= htmlspecialchars($tarea['descripcion']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
        <a href="index.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>

