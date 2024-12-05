<?php
session_start();
include 'php/aud_errores.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

// Actualizar la cookie de última sesión
setcookie('ultima_sesion', date('d-m-Y H:i:s'), time() + (86400 * 30), "/"); // Expira en 30 días
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juan Mecanico</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body id="home">
<header>
    <div class="header-content">
        <img src="images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecanico" class="logo">
        <div class="contact-info">Contactanos: 1234-5678  /  5678-1234</div>
        <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
    </div>

    <div class="dropdown">
        <button class="dropbtn">&#9776; Opciones</button>
        <div class="dropdown-content">
            <a href="admin.php">Inicio</a>
            <a href="php/ver_calendario.php">Consultar Calendario de Citas</a>
            <a href="php/detalle_cliente.php">Lista de Clientes</a>
            <a href="php/ver_mecanicos.php">Lista de Mecánicos</a>
            <a href="registromecanico.html">Registro de Mecánico</a>
            <a href="php/facturacion.php">Facturación</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</header>

<main>
    <section class="hero-section">
        <div class="prueba" style="background-image: url('images/clientemecanico.jpg');">
            <div class="hero-content">
                <h2>Consultar calendario de citas</h2>
                <p>Aquí podrá consultar las citas programadas por los clientes en tiempo real a través de un calendario intuitivo.</p>
                <div class="button-container">
                    <a href="php/ver_calendario.php" class="hero-button">Consultar</a>
                </div>
            </div>
        </div>
        <div class="prueba" style="background-image: url('images/clientefiguraboton2.jpg');">
            <div class="hero-content">
                <h2>Clientes</h2>
                <p>Aquí podrá gestionar de manera eficiente el control de los clientes de la automotriz.</p>
                <div class="button-container">
                    <a href="php/detalle_cliente.php" class="hero-button">Consultar</a>
                </div>
            </div>
        </div>
        <div class="prueba" style="background-image: url('images/mecanicoboton.jpg');">
            <div class="hero-content">
                <h2>Mecánicos</h2>
                <p>Aquí podrá gestionar de manera eficiente a los trabajadores de nuestra automotriz.</p>
                <div class="button-container">
                    <a href="php/ver_mecanicos.php" class="hero-button">Consultar</a>
                </div>
            </div>
        </div>
        <div class="prueba" style="background-image: url('images/facturacion.jpg');">
            <div class="hero-content">
                <h2>Facturas</h2>
                <p>Aquí podrá realizar facturas para los clientes</p>
                <div class="button-container">
                    <a href="php/facturacion.php" class="hero-button">Facturacion</a>
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    <nav class="main-nav">
        <ul>
            <li><a href="admin.php">Inicio</a></li>
            <li><a href="soporte.html">Soporte</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <p>Todos los derechos reservados © Universidad Tecnologica de Panama 2024</p>
    <?php if (isset($_COOKIE['ultima_sesion'])): ?>
        <p>Última sesión: <?php echo htmlspecialchars($_COOKIE['ultima_sesion']); ?></p>
    <?php else: ?>
        <p>Bienvenido, esta es tu primera sesión.</p>
    <?php endif; ?>
</footer>
</body>
</html>
