<?php
session_start();
include 'db.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Eliminar tarea
if (isset($_GET['id'])) {
    $tarea_id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica si la tarea pertenece al usuario actual
    $stmt = $conexion->prepare("SELECT * FROM tareas WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $tarea_id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        // Eliminar la tarea
        $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->bind_param("i", $tarea_id);
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
