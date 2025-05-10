<?php
session_start();

// Generar una cadena aleatoria de caracteres (letras y números)
$captcha_text = substr(md5(rand()), 0, 6);

// Guardar el texto en la sesión para la validación posterior
$_SESSION['captcha'] = $captcha_text;

// Establecer el tipo de contenido como imagen
header('Content-Type: image/png');

// Crear una imagen en blanco
$image = imagecreatetruecolor(100, 30);

// Colores
$bg_color = imagecolorallocate($image, 255, 255, 255);  // Blanco
$text_color = imagecolorallocate($image, 0, 0, 0);      // Negro

// Llenar el fondo de la imagen con el color de fondo
imagefill($image, 0, 0, $bg_color);

// Escribir el texto en la imagen
imagestring($image, 5, 10, 5, $captcha_text, $text_color);

// Mostrar la imagen
imagepng($image);

// Liberar memoria
imagedestroy($image);
?>
