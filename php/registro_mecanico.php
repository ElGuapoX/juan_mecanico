<?php
// Incluir el archivo de conexión a la base de datos
include_once 'db.php';
include 'aud_errores.php';

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
