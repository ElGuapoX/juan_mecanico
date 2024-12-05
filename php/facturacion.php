<?php
session_start();
include_once 'db.php';
include '../php/aud_errores.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html"); 
    exit();
}

$user_id = $_SESSION['usuario_id'];

$db = new Database();
$conn = $db->connect();

// Consulta para obtener todos los clientes registrados
$query_todos_clientes = "SELECT ID_CLIENTE, NOMBRE, APELLIDO, TELEFONO, EMAIL FROM CLIENTE WHERE ROL != 'administrador'";
$result_todos_clientes = $conn->query($query_todos_clientes);
$todos_clientes = [];

if ($result_todos_clientes->num_rows > 0) {
    while ($row = $result_todos_clientes->fetch_assoc()) {
        $todos_clientes[] = $row;
    }
} 

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes - Juan Mecanico</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body id="home" class="detalle-facturacion">
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
            <a href="../php/ver_calendario.php">Consultar Calendario de Citas</a>
            <a href="../php/detalle_cliente.php">Lista de Clientes</a>
            <a href="../php/ver_mecanicos.php">Lista de Mecánicos</a>
            <a href="../registromecanico.html">Registro de Mecánico</a>
            <a href="../php/facturacion.php">Facturación</a>
            <a href="../logout.php">Cerrar Sesión</a>
        </div>
    </div>
</header>
    <main id="home" class="main-detallecliente">
    <body id="home" class="fondo-facturacion">
    <a href="../admin.php" class="back-arrow">Volver</a>
    <h2>Facturación a Clientes</h2>
    <div class="table-container">
        <?php if (count($todos_clientes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todos_clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['ID_CLIENTE']; ?></td>
                            <td><?php echo $cliente['NOMBRE']; ?></td>
                            <td><?php echo $cliente['APELLIDO']; ?></td>
                            <td><?php echo $cliente['TELEFONO']; ?></td>
                            <td><?php echo $cliente['EMAIL']; ?></td>
                            <td>
                                <a href="formulariofactura.php?id_cliente=<?php echo $cliente['ID_CLIENTE']; ?>" class="btn">Facturación</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron clientes registrados.</p>
        <?php endif; ?>
    </div>
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