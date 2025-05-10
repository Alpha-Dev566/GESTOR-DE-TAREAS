<?php
session_start();
include 'db.php';

$error = "";
$exito = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $captcha = $_POST['captcha'];

    if (!isset($_SESSION['captcha_text'])) {
        $error = "CAPTCHA no generado.";
    } elseif (strcasecmp($captcha, $_SESSION['captcha_text']) !== 0) {
        $error = "El CAPTCHA es incorrecto.";
    } else {
        // Verificar si el usuario ya existe
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $error = "El usuario ya existe.";
        } else {
            // Hash de la contraseña
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)");
            $stmt->bind_param("ss", $usuario, $contrasena_hash);
            $stmt->execute();
            $exito = "Registro exitoso. Puedes iniciar sesión ahora.";
        }
    }
}

// Generar nuevo CAPTCHA para cada carga de página
$captcha_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$captcha_text = substr(str_shuffle($captcha_chars), 0, 6);
$_SESSION['captcha_text'] = $captcha_text;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
        }
        .login-box {
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
<body>
    <!-- Logo fuera del login -->
    <div class="logo-container">
        <img src="imagen/imagen1.png" alt="Logo">
    </div>

    <div class="login-box">
        <h2 class="text-center">Registrarse</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"> <?= $error ?> </div>
        <?php endif; ?>

        <?php if ($exito): ?>
            <div class="alert alert-success mt-3"> <?= $exito ?> </div>
            <div class="d-grid mt-3">
                <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
            </div>
        <?php else: ?>
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" required autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" required autocomplete="current-password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ingrese el CAPTCHA</label><br>
                    <div class="bg-light p-2 mb-2 text-center fw-bold"> <?= $_SESSION['captcha_text'] ?> </div>
                    <input type="text" name="captcha" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                    <a href="login.php" class="btn btn-link text-center">Ya tienes una cuenta</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>



