<?php
session_start();
include_once 'db.php';
include 'aud_errores.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}



if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
    // Verificar si los datos del formulario están presentes
    if (isset($_POST['fecha']) && isset($_POST['hora']) && isset($_POST['servicio'])) {
        // Obtener los valores del formulario y sanitizar los inputs
        $fecha = trim($_POST['fecha']);
        $hora = trim($_POST['hora']);
        $servicio = trim($_POST['servicio']);
        $user_id = $_SESSION['usuario_id']; // ID del usuario logueado

        // Validación básica de los campos
        if (empty($fecha) || empty($hora) || $servicio === "Seleccione") {
            echo "<script>alert('Por favor, completa todos los campos de la cita.'); window.history.back();</script>";
            exit();
        }

        // Conectar a la base de datos
        $db = new Database();
        $con = $db->connect();

        // Preparar la consulta SQL usando sentencias preparadas para evitar inyecciones SQL
        $sql = "INSERT INTO CITA (ID_CLIENTE, FECHA_SOLICITUD, HORA, ID_SERVICIO) 
                VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        // Verificar si la consulta se preparó correctamente
        if ($stmt) {
            // Vincular parámetros: 'i' para integer (ID_CLIENTE), 's' para strings (FECHA_SOLICITUD, HORA, SERVICIO)
            $stmt->bind_param("isss", $user_id, $fecha, $hora, $servicio);
            $result = $stmt->execute();

            // Comprobar si la consulta fue exitosa
            if ($result) {
                // Redirigir al usuario a la página de inicio después de registrar la cita
                echo "<script>alert('Cita registrada exitosamente.'); window.location.href='../home.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error al registrar la cita: " . $stmt->error . "'); window.history.back();</script>";
            }

            $stmt->close(); // Cerrar la sentencia
        } else {
            echo "<script>alert('Error al preparar la consulta.'); window.history.back();</script>";
        }

        $con->close();
    } else {
        echo "<script>alert('Debe llenar todos los campos de la cita.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Debe iniciar sesión para registrar una cita.'); window.history.back();</script>";
}
?>
