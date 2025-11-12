<?php
session_start();
include_once '../../bd/bd.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = (int)$_POST['id_cliente'];
    $fecha_emision = $_POST['fecha_emision'];
    $importe = (float)$_POST['importe'];
    $impuesto = (float)$_POST['impuesto'];
    $total = $importe + $impuesto;
    $comentario = trim($_POST['comentario']);
    $servicios = $_POST['servicios'] ?? []; // Servicios seleccionados

    if ($id_cliente > 0 && $fecha_emision && $importe >= 0 && $impuesto >= 0) {
        try {
            // Iniciar transacción
            $conn->begin_transaction();

            // Insertar en la tabla FACTURA
            $query_factura = "INSERT INTO FACTURA (ID_CLIENTE, FECHA_EMISION, IMPORTE, IMPUESTO, TOTAL, COMENTARIO) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_factura = $conn->prepare($query_factura);
            $stmt_factura->bind_param("issdds", $id_cliente, $fecha_emision, $importe, $impuesto, $total, $comentario);

            if (!$stmt_factura->execute()) {
                throw new Exception("Error al registrar la factura: " . $stmt_factura->error);
            }

            $id_factura = $stmt_factura->insert_id;

            // Insertar servicios seleccionados en FACTURA_SERVICIO
            if (!empty($servicios)) {
                $query_servicio = "INSERT INTO FACTURA_SERVICIO (ID_FACTURA, ID_SERVICIO, CANTIDAD) VALUES (?, ?, ?)";
                $stmt_servicio = $conn->prepare($query_servicio);

                foreach ($servicios as $servicio_id) {
                    $cantidad = 1; // Por defecto una cantidad
                    $stmt_servicio->bind_param("iii", $id_factura, $servicio_id, $cantidad);
                    if (!$stmt_servicio->execute()) {
                        throw new Exception("Error al registrar el servicio en la factura: " . $stmt_servicio->error);
                    }
                }
            }

            // Confirmar transacción
            $conn->commit();
            header("Location: facturacion.php?success=true");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = $e->getMessage();
        }
    } else {
        $error = "Por favor, completa todos los campos obligatorios correctamente.";
    }
} else {
    $id_cliente = isset($_GET['id_cliente']) ? (int)$_GET['id_cliente'] : 0;
}

// Obtener lista de servicios
$query_servicios = "SELECT ID_SERVICIO, DESCRIPCION, COSTO FROM SERVICIO";
$result_servicios = $conn->query($query_servicios);
$servicios_disponibles = $result_servicios->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Factura</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="fondo-registroauto">
<header>
    <div class="header-content">
    <a href="admin.php"><img src="../../images/con_fondo-removebg-preview (1).png" alt="Logo Juan Mecanico" class="logo"></a>
        <div class="contact-info">Contactanos: 1234-5678 / 5678-1234</div>
        <div class="hours">Horario de atención: lunes a sábado de 8:00 am a 6:00 pm</div>
    </div>
</header>
<main>
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <section class="form-content">
        <form action="formulariofactura.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">

            <label for="fecha_emision">Fecha de Emisión:</label>
            <input type="date" id="fecha_emision" name="fecha_emision" required>

            <label for="importe">Importe:</label>
            <input type="number" step="0.01" id="importe" name="importe" required>

            <label for="impuesto">Impuesto:</label>
            <input type="number" step="0.01" id="impuesto" name="impuesto" required>

            <label for="comentario">Comentario:</label><br>
            <textarea id="comentario" name="comentario" rows="4" placeholder="Agrega un comentario opcional..."></textarea><br>

            
            <label for="servicios">Seleccionar Servicios:</label><br>
            <select id="servicios" name="servicios[]" required>
                <?php foreach ($servicios_disponibles as $servicio): ?>
                    <option value="<?php echo $servicio['ID_SERVICIO']; ?>">
                        <?php echo htmlspecialchars($servicio['DESCRIPCION']) . " - $" . number_format($servicio['COSTO'], 2); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" name="botonguardar" value="Registrar Factura" class="submit-button">
        </form>
    </section>
</main>
</body>
</html>
