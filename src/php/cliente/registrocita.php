<?php
session_start();
include_once '../../bd/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al login y solicitar volver a registro de cita tras login
    header("Location: ../../index.html?next=registrocita");
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
                    echo "<script>alert('Cita registrada exitosamente.'); window.location.href='home.html';</script>";
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
    <title>Juan Mecanico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/registrocita.css">
</head>
<body id="home">
<nav>
    <div class="nav-logo">
        <a href="home.html">
            <img src="../../images/logo.png">
        </a>
    </div>
    <ul class="nav-links">
        <li class="link"><a href="home.html">Inicio</a></li>
        <li id="link1" class="link"><a href="nosotros.html">Nosotros</a></li>
        <li id="link2" class="link"><a href="servicios.html">Servicios</a></li>
        <li id="link3" class="link"><a href="informacion.html">Informacion</a></li>
    </ul>
    <a href="registrocita.php" class="btn">Obten una cita</a>
</nav>
<header>
    

</header>


<main>
    <section class="form-content">
            <h2>Registro de Citas</h2>
            <p>Seleccione la fecha y hora para su cita:</p>
            <form action="" method="post">
                <label for="fecha">Fecha de la cita:</label>
                <input class="form-control" type="date" name="fecha" id="fecha" required>

                <label for="hora">Hora de la cita:</label>
                <input class="form-control" type="time" name="hora" id="hora" required>

                <label for="servicio">Servicio:</label>
                <select class="form-select" name="servicio" id="servicio" required>
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

<footer class="container">
        <span class="blur"></span>
        <span class="blur"></span>
        <div class="column">
            <div class="logo">
                <img src="../../images/logo.png">
            </div>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </p>
            <div class="socials">
                <a href="#"><i class="ri-youtube-line"></i></a>
                <a href="#"><i class="ri-instagram-line"></i></a>
                <a href="#"><i class="ri-twitter-line"></i></a>
            </div>
        </div>
        <div class="column">
            <h4>Company</h4>
            <a href="#">Business</a>
            <a href="#">Partnership</a>
            <a href="#">Network</a>
        </div>
        <div class="column">
            <h4>About Us</h4>
            <a href="#">Blogs</a>
            <a href="#">Channels</a>
            <a href="#">Sponsors</a>
        </div>
        <div class="column">
            <h4>Contact</h4>
            <a href="#">Contact Us</a>
            <a href="#">Privicy Policy</a>
            <a href="#">Terms & Conditions</a>
        </div>
    </footer>



</body>
</html>
