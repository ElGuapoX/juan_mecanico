<?php
session_start();
include_once '../../bd/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html"); 
    exit();
}

$user_id = $_SESSION['usuario_id'];

// Conectar a la base de datos
$db = new Database();
$conn = $db->connect();

// Obtener la información de los mecánicos
$sql = "SELECT * FROM MECANICO";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Mecánicos</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<main id="home" class="main-detallecliente">
<body id="home" class="detalle-mecanico">
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
<a href="../admin.php" class="back-arrow">Volver</a>
	<h1>Lista de Mecánicos Registrados</h1>
	<div class="table-container">
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Ciudad</th>
                <th>Email</th>
                <th>Barrio</th>
                <th>Cédula (CIP)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Iterar sobre cada mecánico y mostrarlo en la tabla
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['ID_MECANICO']}</td>
                            <td>{$row['NOMBRE']}</td>
                            <td>{$row['APELLIDO']}</td>
                            <td>{$row['CIUDAD']}</td>
                            <td>{$row['EMAIL']}</td>
                            <td>{$row['BARRIADA']}</td>
                            <td>{$row['CIP']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay mecánicos registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
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

<?php
// Cerrar la conexión
$conn->close();
?>