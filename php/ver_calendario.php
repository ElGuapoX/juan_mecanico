<?php
session_start();
include_once 'db.php';
include '../php/aud_errores.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html"); 
    exit();
}

$db = new Database();
$conn = $db->connect();

// Realizamos un JOIN con la tabla CLIENTE para obtener el nombre y apellido
$query = "
    SELECT C.ID_CLIENTE, C.nombre, C.apellido, CT.FECHA_SOLICITUD, CT.HORA 
    FROM CITA CT
    JOIN CLIENTE C ON CT.ID_CLIENTE = C.ID_CLIENTE
";

$result = $conn->query($query);

$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Combinamos la fecha con la hora
        $fecha_cita = new DateTime($row['FECHA_SOLICITUD'] . ' ' . $row['HORA']);
        
        $appointments[] = [
            "title" => $row['nombre'] . " " . $row['apellido'],  // Título con el nombre del cliente
            "date" => $fecha_cita->format('Y-m-d H:i:s'),  // Fecha y hora de la cita combinadas
            "url" => "detalle_cita.php?id_cliente=" . $row['ID_CLIENTE']  // Enlace para ver los detalles de la cita
        ];
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juan Mecanico - Ver Calendario</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
</head>
<body id="calendar-view">
<header>
    <div class="header-content">
        <a href="../admin.php"><img src="../images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecanico" class="logo"></a>
        <div class="contact-info">Contactanos: 1234-5678  /  5678-1234</div>
        <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
    </div>
</header>

<main id="home" class="fc-col-header-cell">
    <a href="../admin.php" class="back-arrow">Volver</a>
    <h1 style="text-align:center">Calendario de Citas</h1>
    <div id="calendar"></div>
</main>

<footer>
    <nav class="main-nav">
        <ul>
            <li><a href="../admin.php">Inicio</a></li>
            <li><a href="../soporte.html">Soporte</a></li>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <p>Todos los derechos reservados © Universidad Tecnologica de Panama 2024</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',  // Vista mensual
            events: <?php echo json_encode($appointments); ?>,  // Mostrar las citas obtenidas desde la base de datos
            locale: 'es',  // Establecer el idioma a español
            eventColor: 'red',  // Color de los eventos en el calendario
            eventClick: function(info) {
                window.location.href = info.event.extendedProps.url;  // Redirige a la página de detalles de la cita al hacer clic en el evento
            }
        });
        calendar.render();
    });
</script>

</body>
</html>
