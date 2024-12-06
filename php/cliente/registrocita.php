<?php
session_start();
include_once '../../bd/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.html");
    exit();
}

// Lógica para manejar el formulario de registro de cita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fecha'], $_POST['hora'], $_POST['servicio'])) {
        // Sanitización de entradas
        $fecha = trim($_POST['fecha']);
        $hora = trim($_POST['hora']);
        $servicio = trim($_POST['servicio']);
        $user_id = $_SESSION['usuario_id'];

        // Validación básica de los campos
        if (empty($fecha) || empty($hora) || $servicio === "Seleccione") {
            echo "<script>alert('Por favor, completa todos los campos de la cita.');</script>";
        } else {
            // Conectar a la base de datos
            $db = new Database();
            $con = $db->connect();

            // Preparar consulta SQL
            $sql = "INSERT INTO CITA (ID_CLIENTE, FECHA_SOLICITUD, HORA, ID_SERVICIO) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($sql);

            if ($stmt) {
                // Vincular parámetros y ejecutar
                $stmt->bind_param("isss", $user_id, $fecha, $hora, $servicio);
                $result = $stmt->execute();

                if ($result) {
                    echo "<script>alert('Cita registrada exitosamente.'); window.location.href='../../views/cliente/home.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error al registrar la cita. Por favor, inténtalo nuevamente.');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Error al preparar la consulta.');</script>";
            }

            $con->close();
        }
    } else {
        echo "<script>alert('Debe llenar todos los campos de la cita.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Citas</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body id="home" class="fondo-agendarcita">
    <header>
        <div class="header-content">
            <a href="../../views/cliente/home.php">
                <img src="../../images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecánico" class="logo">
            </a>
            <div class="contact-info">Contáctanos: 1234-5678 / 5678-1234</div>
            <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
        </div>
    </header>
    <a href="javascript:history.back()" class="back-arrow">Volver</a>

    <main>
        <section class="form-content">
            <h2>Registro de Citas</h2>
            <p>Seleccione la fecha y hora para su cita:</p>
            <form action="" method="post">
                <label for="fecha">Fecha de la cita:</label>
                <input type="date" name="fecha" id="fecha" required><br>

                <label for="hora">Hora de la cita:</label>
                <input type="time" name="hora" id="hora" required><br>

                <label for="servicio">Servicio:</label>
                <select name="servicio" id="servicio" required>
                    <option value="Seleccione">Seleccione una opción</option>
                    <option value="1">Diagnóstico General</option>
                    <option value="2">Mantenimiento</option>
                    <option value="3">Chapistería y Pintura</option>
                    <option value="4">Alineación</option>
                </select><br>

                <input type="submit" value="Registrar Cita" class="submit-button">
            </form>
        </section>
    </main>

    <footer>
        <nav class="main-nav">
            <ul>
                <li><a href="../../views/cliente/home.php">Inicio</a></li>
                <li><a href="../../views/cliente/soporte.html">Soporte</a></li>
                <li><a href="../../php/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
        <p>Todos los derechos reservados © Universidad Tecnológica de Panamá 2024</p>
    </footer>
</body>
</html>
