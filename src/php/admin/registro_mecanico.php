<?php
// Incluir el archivo de conexión a la base de datos
include_once '../../bd/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos requeridos estén completos
    if (
        isset($_POST['nombre']) && !empty($_POST['nombre']) &&
        isset($_POST['apellido']) && !empty($_POST['apellido']) &&
        isset($_POST['cedula']) && !empty($_POST['cedula']) &&
        isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['provincia']) && !empty($_POST['provincia']) &&
        isset($_POST['ciudad']) && !empty($_POST['ciudad']) &&
        isset($_POST['barrio']) && !empty($_POST['barrio'])
    ) {
        // Recopilar los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $email = $_POST['email'];
        $provincia = $_POST['provincia'];
        $ciudad = $_POST['ciudad'];
        $barrio = $_POST['barrio'];

        // Conectar a la base de datos
        $db = new Database();
        $conn = $db->connect();

        try {
            // Preparar la consulta para insertar en la tabla MECANICO
            $sql = "INSERT INTO MECANICO (NOMBRE, APELLIDO, CIUDAD, EMAIL, Provincia, BARRIADA, CIP) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $nombre, $apellido, $ciudad, $email, $provincia, $barrio, $cedula);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "<script>
                alert('Mecánico registrado exitosamente');
                window.location.href = '../php/ver_mecanicos.php';
              </script>";
            } else {
                throw new Exception("Error al registrar el mecánico: " . $stmt->error);
            }
        } catch (Exception $e) {
            echo "<script>
                    alert('{$e->getMessage()}');
                    window.history.back();
                  </script>";
        } finally {
            // Cerrar la conexión y el statement
            $stmt->close();
            $conn->close();
        }
    } else {
        echo "<script>
                alert('Por favor, completa todos los campos requeridos.');
                window.history.back();
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body id="home" class="fondo-registromecanico">
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
    
    <section class="form-content">
        <h2>Registro Mecanico</h2>
        <p>Complete el siguiente formulario para registrar un Mecanico</p>
        <form id="registro" action="php/registro_mecanico.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="provincia">Provincia:</label>
            <select id="provincia" name="provincia" required>
                <option value="Seleccione">Seleccione una opción</option>
                <option value="Bocas del Toro">Bocas del Toro</option>
                <option value="Cocle">Coclé</option>
                <option value="Colon">Colón</option>
                <option value="Chiriqui">Chiriquí</option>
                <option value="Darien">Darién</option>
                <option value="Herrera">Herrera</option>
                <option value="Los Santos">Los Santos</option>
                <option value="Panama">Panamá</option>
                <option value="Veraguas">Veraguas</option>
            </select>

            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad" required>

            <label for="barrio">Barrio:</label>
            <input type="text" id="barrio" name="barrio" required>

            <button type="submit" class="submit-button">Registrarse</button>
        </form>
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
</footer>

</body>
</html>
