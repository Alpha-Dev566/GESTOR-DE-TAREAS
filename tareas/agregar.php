<?php
session_start();
include 'db.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($titulo)) {
        $error = "El título no puede estar vacío.";
    } else {
        // Insertar la tarea con la fecha y hora actuales
        $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, usuario_id, creada_en) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ssi", $titulo, $descripcion, $usuario_id);
        $stmt->execute();
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
        }
        .agregar-box {
            max-width: 800px;
            margin: auto;
            margin-top: 8vh;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }
        textarea {
            min-height: 150px;
        }
        
        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .logo-container img {
            height: 100px;
            width: auto;
        }
    </style>
</head>
<body>
    <!-- Logo fuera del login -->
    <div class="logo-container">
        <img src="imagen/imagen1.png" alt="Logo">
    </div>

    <div class="agregar-box">
        <h2 class="text-center mb-4">Agregar Nueva Tarea</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"> <?= $error ?> </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control form-control-lg" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control form-control-lg"></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary btn-lg">Volver</a>
                <button type="submit" class="btn btn-primary btn-lg">Guardar Tarea</button>
            </div>
        </form>
    </div>
</body>
</html>


