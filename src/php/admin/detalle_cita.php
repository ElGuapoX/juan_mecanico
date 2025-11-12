<?php
session_start();
include_once '../../bd/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../index.html");
    exit();
}

$db = new Database();
$conn = $db->connect();

$id_cliente = $_GET['id_cliente']; // Obtener el ID del cliente desde la URL

// Consulta segura usando prepared statements
$query_cliente = "
    SELECT 
        C.ID_CLIENTE, 
        C.nombre, 
        C.apellido, 
        CT.FECHA_SOLICITUD, 
        CT.HORA, 
        S.DESCRIPCION AS SERVICIO, 
        S.COSTO
    FROM CITA CT
    JOIN CLIENTE C ON CT.ID_CLIENTE = C.ID_CLIENTE
    LEFT JOIN SERVICIO S ON CT.ID_SERVICIO = S.ID_SERVICIO
    WHERE C.ID_CLIENTE = ?
";


$stmt_cliente = $conn->prepare($query_cliente);
$stmt_cliente->bind_param("i", $id_cliente);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$cliente = $result_cliente->fetch_assoc();

if (!$cliente) {
    echo "<p>No se encontró el cliente o no tiene citas registradas.</p>";
    $conn->close();
    exit();
}

// Consulta para obtener los autos del cliente
$query_autos = "
    SELECT A.MODELO, A.MATRICULA, A.MARCA, A.COLOR
    FROM AUTOMOVIL A
    WHERE A.ID_CLIENTE = ?
";

$stmt_autos = $conn->prepare($query_autos);
$stmt_autos->bind_param("i", $id_cliente);
$stmt_autos->execute();
$result_autos = $stmt_autos->get_result();
$autos = $result_autos->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cita - Juan Mecanico</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <div class="header-content">
    <a href="admin.php"><img src="../../images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecanico" class="logo"></a>
        <div class="contact-info">Contactanos: 1234-5678 / 5678-1234</div>
        <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">&#9776; Opciones</button>
        <div class="dropdown-content">
            <a href="../admin.php">Inicio</a>
            <a href="ver_calendario.php">Consultar Calendario de Citas</a>
            <a href="detalle_cliente.php">Lista de Clientes</a>
            <a href="ver_mecanicos.php">Lista de Mecánicos</a>
            <a href="registro_mecanico.php">Registro de Mecánico</a>
            <a href="../php/facturacion.php">Facturación</a>
            <a href="../../logout.php">Cerrar Sesión</a>
        </div>
    </div>
</header>

<main>
    <h2>Detalles de la Cita</h2>
    
    <p><strong>Cliente:</strong> <?php echo htmlspecialchars($cliente['nombre'] . " " . $cliente['apellido']); ?></p>
    <p><strong>Fecha de Solicitud:</strong> <?php echo htmlspecialchars($cliente['FECHA_SOLICITUD']); ?></p>
    <p><strong>Hora de la Cita:</strong> <?php echo htmlspecialchars($cliente['HORA']); ?></p>
    <p><strong>Servicio:</strong> <?php echo htmlspecialchars($cliente['SERVICIO']); ?></p>
    <p><strong>Costo:</strong> $<?php echo htmlspecialchars($cliente['COSTO']); ?></p>

    <h3>Autos del Cliente</h3>
    <?php if (count($autos) > 0): ?>
        <ul>
            <?php foreach ($autos as $auto): ?>
                <li>
                    <strong>Marca:</strong> <?php echo htmlspecialchars($auto['MARCA']); ?><br>
                    <strong>Modelo:</strong> <?php echo htmlspecialchars($auto['MODELO']); ?><br>
                    <strong>Matrícula:</strong> <?php echo htmlspecialchars($auto['MATRICULA']); ?><br>
                    <strong>Color:</strong> <?php echo htmlspecialchars($auto['COLOR']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay autos registrados para este cliente.</p>
    <?php endif; ?>
</main>

<footer>
    <nav class="main-nav">
        <ul>
            <li><a href="../admin.php">Inicio</a></li>
            <li><a href="../soporte.html">Soporte</a></li>
            <li><a href="../../logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <p>Todos los derechos reservados © Universidad Tecnologica de Panama 2024</p>
</footer>

</body>
</html>
