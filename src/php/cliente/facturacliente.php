<?php
session_start();
include_once 'db.php';
include '../php/aud_errores.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit();
}

$db = new Database();
$conn = $db->connect();

$id_cliente = $_SESSION['usuario_id']; // Obtener el ID del cliente de la sesión

// Obtener las facturas del cliente junto con el servicio y el comentario
$query_facturas = "
    SELECT f.ID_FACTURA, f.FECHA_EMISION, f.IMPORTE, f.IMPUESTO, f.TOTAL, f.COMENTARIO, 
           s.DESCRIPCION AS SERVICIO
    FROM FACTURA f
    LEFT JOIN FACTURA_SERVICIO fs ON f.ID_FACTURA = fs.ID_FACTURA
    LEFT JOIN SERVICIO s ON fs.ID_SERVICIO = s.ID_SERVICIO
    WHERE f.ID_CLIENTE = ?
";
$stmt_facturas = $conn->prepare($query_facturas);
$stmt_facturas->bind_param('i', $id_cliente);
$stmt_facturas->execute();
$result_facturas = $stmt_facturas->get_result();
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
    <title>Facturas del Cliente</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body id="home" class="detalle-facturacion">
<header>
    <div class="header-content">
        <a href="../home.php"><img src="../images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecanico" class="logo"></a>
        <div class="contact-info">Contáctanos: 1234-5678 / 5678-1234</div>
        <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">&#9776; Opciones</button>
        <div class="dropdown-content">
            <a href="../home.php">Inicio</a>
            <a href="../registrocitas.html">Registro de Citas</a>
            <a href="../soporte.html">Soporte</a>
            <a href="../logout.php">Cerrar sesión</a>
        </div>
    </div>
</header>
<a href="../home.php" class="back-arrow">Volver</a>
<main id="home" class="main-facturacion">
    <h1>Facturas del Cliente</h1>
    <div class="table-container">
        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>ID Factura</th>
                    <th>Fecha de Emisión</th>
                    <th>Importe</th>
                    <th>Impuesto</th>
                    <th>Total</th>
                    <th>Servicio</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_facturas->num_rows > 0) {
                    while ($factura = $result_facturas->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($factura['ID_FACTURA']) . "</td>
                                <td>" . htmlspecialchars($factura['FECHA_EMISION']) . "</td>
                                <td>" . htmlspecialchars($factura['IMPORTE']) . "</td>
                                <td>" . htmlspecialchars($factura['IMPUESTO']) . "</td>
                                <td>" . htmlspecialchars($factura['TOTAL']) . "</td>
                                <td>" . htmlspecialchars($factura['SERVICIO'] ?: 'Sin servicio') . "</td>
                                <td>" . htmlspecialchars($factura['COMENTARIO'] ?: 'Sin comentario') . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No se encontraron facturas para este cliente.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
</body>
</html>
