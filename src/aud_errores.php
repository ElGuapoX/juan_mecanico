<?php
include_once 'db.php';

// Configurar un manejador global para capturar errores y excepciones
function registrarError($descripcion) {
    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->connect();

    // Preparar la consulta para registrar el error en la tabla `HISTORIAL_ERRORES`
    $query = "INSERT INTO HISTORIAL_ERRORES (DESCRIPCION) VALUES (?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('s', $descripcion);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
}

// Manejador para errores PHP (warnings, notices, etc.)
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $descripcion = "Error [$errno]: $errstr en $errfile línea $errline";
    registrarError($descripcion);
    // Opcional: Detener la ejecución en errores críticos
    if ($errno === E_ERROR || $errno === E_CORE_ERROR || $errno === E_COMPILE_ERROR) {
        die("Ocurrió un error crítico. Por favor, contacte al administrador.");
    }
});

// Manejador para excepciones no capturadas
set_exception_handler(function ($exception) {
    $descripcion = "Excepción no capturada: " . $exception->getMessage();
    registrarError($descripcion);
    // Opcional: Mostrar un mensaje de error genérico al usuario
    die("Ocurrió un problema inesperado. Por favor, contacte al administrador.");
});

// Manejador para errores fatales (última línea de defensa)
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        $descripcion = "Error fatal: {$error['message']} en {$error['file']} línea {$error['line']}";
        registrarError($descripcion);
    }
});
?>
