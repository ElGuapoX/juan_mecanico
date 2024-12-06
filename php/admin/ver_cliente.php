<?php
session_start();
include_once '../../bd/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html"); 
    exit();
}

$db = new Database();
$conn = $db->connect();

// Validar el ID del cliente recibido
$id_cliente = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener los datos del cliente
$query_cliente = "SELECT * FROM CLIENTE WHERE ID_CLIENTE = $id_cliente";
$result_cliente = $conn->query($query_cliente);
$cliente = null;

if ($result_cliente->num_rows > 0) {
    $cliente = $result_cliente->fetch_assoc();
} else {
    die("Cliente no encontrado.");
}

// Obtener los automóviles asociados al cliente
$query_autos = "SELECT * FROM AUTOMOVIL WHERE ID_CLIENTE = $id_cliente";
$result_autos = $conn->query($query_autos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Cliente</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<header>
    <div class="header-content">
        <a href="../admin.php"><img src="../images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecanico" class="logo"></a>
        <div class="contact-info">Contactanos: 1234-5678  /  5678-1234</div>
        <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">&#9776; Opciones</button>
        <div class="dropdown-content">
            <a href="../admin.php">Inicio</a>
            <a href="ver_calendario.php">Consultar Calendario de Citas</a>
            <a href="detalle_cliente.php">Lista de Clientes</a>
            <a href="ver_mecanicos.php">Lista de Mecánicos</a>
            <a href="../registromecanico.html">Registro de Mecánico</a>
            <a href="../php/facturacion.php">Facturación</a>
            <a href="../logout.php">Cerrar Sesión</a>
        </div>
    </div>
</header>
<body id="home" class="padding-container">
<h1>Detalles del Cliente</h1>
<main id="home" class="main-detallecliente">
<section class="info-cliente">
    <?php if ($cliente): ?>
        <div class="info-grid">
            <div class="info-card"><strong>ID:</strong> <?php echo htmlspecialchars($cliente['ID_CLIENTE']); ?></div>
            <div class="info-card"><strong>Nombre:</strong> <?php echo htmlspecialchars($cliente['NOMBRE']); ?></div>
            <div class="info-card"><strong>Apellido:</strong> <?php echo htmlspecialchars($cliente['APELLIDO']); ?></div>
            <div class="info-card"><strong>Email:</strong> <?php echo htmlspecialchars($cliente['EMAIL']); ?></div>
            <div class="info-card"><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['TELEFONO']); ?></div>
            <div class="info-card"><strong>Fecha de Creación:</strong> <?php echo htmlspecialchars($cliente['FECHA_CREACION']); ?></div>
            <div class="info-card"><strong>Ultima Sesion:</strong> <?php echo htmlspecialchars($cliente['ULTIMA_SESION']); ?></div>
        </div>
</section>
<h2>Automóviles Registrados</h2>
<?php if ($result_autos->num_rows > 0): ?>
<section class="info-tablaclient">
    <div class="auto-grid">
        <?php while ($auto = $result_autos->fetch_assoc()): ?>
            <div class="auto-card">
                <h3>Auto ID: <?php echo htmlspecialchars($auto['ID_AUTOMOVIL']); ?></h3>
                <p><strong>Modelo:</strong> <?php echo htmlspecialchars($auto['MODELO']); ?></p>
                <p><strong>Matrícula:</strong> <?php echo htmlspecialchars($auto['MATRICULA']); ?></p>
                <p><strong>Marca:</strong> <?php echo htmlspecialchars($auto['MARCA']); ?></p>
                <p><strong>Color:</strong> <?php echo htmlspecialchars($auto['COLOR']) ?: 'N/A'; ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</section>
<?php else: ?>
    <section class="info-tablaclient">
        <p>No se encontraron automóviles registrados para este cliente.</p>
    </section>
<?php endif; ?>
<!-- Nuevo botón para registrar un automóvil -->
<section class="info-buttoncliente">
    <a href="registrar_auto.php?id_cliente=<?php echo $id_cliente; ?>" class="btn">Registrar Nuevo Automóvil</a>
    <?php else: ?>
        <p>Cliente no encontrado.</p>
    <?php endif; ?>
    <a href="detalle_cliente.php" class="btn btn-secondary">Volver</a>
</section>
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
</body>
</html>
