<?php
include 'php/aud_errores.php';
session_start(); // Iniciar la sesión
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

// Redirigir al usuario a la página de inicio de sesión
header("Location: login.html");
exit();
?>
