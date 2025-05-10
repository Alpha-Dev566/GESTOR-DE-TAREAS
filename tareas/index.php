<?php
session_start();
include 'db.php';

// Verifica si el usuario está logueado, si no lo está, redirige a la página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener todas las tareas del usuario
$resultado = $conexion->query("SELECT * FROM tareas WHERE usuario_id = $usuario_id ORDER BY creada_en DESC");

// Cerrar sesión si el usuario hace clic en el botón de cerrar sesión
if (isset($_POST['cerrar_sesion'])) {
    // Destruir todas las variables de sesión
    session_unset();
    // Destruir la sesión
    session_destroy();
    // Redirigir al login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        body {
            background-color: #007bff;
        }
        .agregar-box {
            max-width: 400px;
            margin: auto;
            margin-top: 10vh;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
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
<body class="container py-5">
    <!-- Título centrado -->
    <h2 class="mb-4 text-center">Mis Tareas</h2>
    <!-- Logo fuera del login -->
    <div class="logo-container">
        <img src="imagen/imagen1.png" alt="Logo">
    </div>
    <!-- Contenedor de los botones (centrados) -->
    <div class="d-flex justify-content-center mb-4">
        <!-- Botón de Agregar nueva tarea -->
        <a href="agregar.php" class="btn btn-success mx-2">Agregar Nueva Tarea</a>
        <!-- Botón de Cerrar sesión -->
        <form method="POST">
            <button type="submit" name="cerrar_sesion" class="btn btn-danger mx-2">Cerrar sesión</button>
        </form>
    </div>

    <!-- Mostrar tareas -->
    <?php if ($resultado->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($tarea = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($tarea['titulo']) ?></td>
                        <td><?= htmlspecialchars($tarea['descripcion']) ?></td>
                        <td><?= $tarea['creada_en'] ?></td>
                        <td>
                            <!-- Botón de editar -->
                            <a href="editar.php?id=<?= $tarea['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <!-- Botón de eliminar -->
                            <a href="eliminar.php?id=<?= $tarea['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes tareas.</p>
    <?php endif; ?>
</body>
</html>


