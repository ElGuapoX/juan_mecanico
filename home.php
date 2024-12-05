<?php
session_start();
include 'php/aud_errores.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juan Mecanico</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="home">
<header>
    <div class="header-content">
        <a href="home.php"><img src="images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecanico" class="logo"></a>
        <div class="contact-info">Contactanos: 1234-5678 / 5678-1234</div>
        <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">&#9776; Opciones</button>
        <div class="dropdown-content">
            <a href="home.php">Inicio</a>
            <a href="registrocitas.html">Registro de Citas</a>
            <a href="soporte.html">Soporte</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</header>

<main>

        <div class="boton-facturas">
            <a href="php/facturacliente.php" class="hero-button">Mis Facturas</a>
        </div>
        
</main>

    <section class="seccion-servicio">
        <h2>Servicios</h2>
        <article class="servicios">
            <img src="images/Diagnostico.png" alt="Noticia 1">
            <div>
                <h3>Diagnostico</h3>
                <p>En Juan Mecánico contamos con un equipo de tecnología de punta donde tenemos la posibilidad de diagnosticar tu carro:</p>
                <ul>
                    <li>Diagnóstico de motor</li>
                    <li>Diagnóstico eléctrico</li>
                    <li>Diagnóstico de Sistemas de frenos</li>
                    <li>Diagnóstico de transmisión</li>
                    <li>Diagnostico de Suspensión y Dirección</li>
                </ul>
            </div>
        </article>
        <article class="servicios">
            <img src="images/Mantenimiento y reparaciones.png" alt="Noticia 2">
            <div>
                <h3>Mantenimiento y Reparaciones</h3>
                <p>En Juan Mecánico ofrecemos una amplia variedad de servicios preventivos, mantenimientos y reparaciones correctivas que su auto requiera:</p>
                <ul>
                    <li>Mantenimiento de motor</li>
                    <li>Mantenimiento de transmision</li>
                    <li>Frenos</li>
                    <li>Suspension</li>
                    <li>Diagnostico Computarizado</li>
                </ul>
            </div>
        </article>
        <article class="servicios">
            <img src="images/Chapisteria y Pintura.png" alt="Noticia 2">
            <div>
                <h3>Chapisteria y Pintura</h3>
                <p>Los mejores profesionales de al pintura y chapisteria automotriz a su disposicion</p>
                <ul>
                    <li>Reparacion de chapisteria </li>
                    <li>Reparacion de pintura</li>
                    <li>Cambio de color</li>
                    <li>Pintura y/o reparacion de rines</li>
                </ul>
            </div>
        </article>  
    </section>
</main>

<footer>
    <nav class="main-nav">
        <ul>
            <li><a href="home.php">Inicio</a></li>
            <li><a href="soporte.html">Soporte</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </nav>
    <p>Todos los derechos reservados © Universidad Tecnologica de Panama 2024</p>
</footer>



</body>
</html>