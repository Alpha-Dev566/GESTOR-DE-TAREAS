<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $captcha = $_POST['captcha'];

    if (!isset($_SESSION['captcha_text'])) {
        $error = "CAPTCHA no generado.";
    } elseif (strcasecmp($captcha, $_SESSION['captcha_text']) !== 0) {
        $error = "El CAPTCHA es incorrecto.";
    } else {
        $stmt = $conexion->prepare("SELECT id, contrasena FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuarioData = $resultado->fetch_assoc();
            if (password_verify($contrasena, $usuarioData['contrasena'])) {
                $_SESSION['usuario_id'] = $usuarioData['id'];
                $_SESSION['mensaje'] = "Inicio de sesión exitoso.";
                unset($_SESSION['captcha_text']);
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
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
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
        }

        /* Fondo de la página (imagen de fondo) */
        .login-background {
            background-image: url('imagen/img.jpg'); /* Reemplaza con la ruta de tu imagen */
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        .login-box {
            max-width: 400px;
            margin: auto;
            margin-top: 10vh;
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco con algo de transparencia */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        }

        /* Estilo para el logo fuera del formulario */
        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .logo-container img {
            height: 100px;
            width: auto;
        }

        /* Estilo del pie de página */
        .footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            background: linear-gradient(90deg, #ffffff 50%, #ff6600 50%);
            padding: 20px 0;
            position: absolute;
            bottom: 0;
            left: 0;
            color: black;
            font-size: 14px;
        }
        .footer-left {
            flex: 1;
            text-align: center;
            color: black;
        }
        .footer-right {
            flex: 1;
            text-align: center;
            color: white;
            background-color: #ff6600;
            padding: 10px;
        }
        .footer a {
            color: black;
            text-decoration: none;
        }
        .footer .icon {
            margin-right: 8px;
        }
    </style>
</head>
<body class="login-background">

<!-- Logo fuera del login -->
<div class="logo-container">
    <img src="imagen/imagen1.png" alt="Logo">
</div>

<div class="login-box">
    <h2 class="text-center">Iniciar Sesión</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger mt-3"> <?= $error ?> </div>
    <?php endif; ?>

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
            <button type="submit" class="btn btn-primary">Ingresar</button>
            <a href="registro.php" class="btn btn-link text-center">Registrarse</a>
        </div>
    </form>
</div>

<!-- Pie de página con datos -->
<div class="footer">
    <!-- Lado izquierdo (blanco con texto negro) -->
    <div class="footer-left">
        <p><i class="fas fa-envelope icon"></i><a href="mailto:sedemixco@uregionalregion2.edu.gt">sedemixco@uregionalregion2.edu.gt</a></p>
        <p><i class="fas fa-university icon"></i>Universidad Regional Mixco</p>
        <p><i class="fas fa-calendar-day icon"></i>Plan diario de 17:30 a 20:30 Hrs.</p>
        <p><i class="fas fa-calendar-week icon"></i>Plan sábado de 8:00 a 17:00 Hrs.</p>
    </div>
    
    <!-- Lado derecho (anaranjado con texto blanco) -->
    <div class="footer-right">
        <p><i class="fas fa-phone-alt icon"></i>6670-5093</p>
        <p><i class="fas fa-map-marker-alt icon"></i>Colegio Lehnsen 12 Av. 18-79 , Mixco, Guatemala</p>
        <p><i class="fas fa-cogs icon"></i>Modalidad presencial</p>
    </div>
</div>

</body>
</html>
