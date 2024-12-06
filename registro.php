<?php
// Conexión a la base de datos
include_once 'bd/bd.php';
include 'aud_errores.php';

$db = new Database();
$con = $db->connect();

if (isset($_POST['nombre']) && !empty($_POST['nombre']) &&
    isset($_POST['apellido']) && !empty($_POST['apellido']) &&
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['contrasena']) && !empty($_POST['contrasena'])) {

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar contraseña
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;


    // Consulta preparada para insertar datos en la tabla CLIENTE
    $stmt = $con->prepare("INSERT INTO CLIENTE (NOMBRE, APELLIDO, EMAIL, PASSWORD, TELEFONO) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellido, $email, $contrasena, $telefono);

    // Ejecutar la consulta y verificar éxito
    if ($stmt->execute()) {
        echo "<script>
                alert('Usuario registrado exitosamente');
                window.location.href = '../login.html';
              </script>";
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }

    // Cerrar statement y conexión
    $stmt->close();
    $con->close();
} else {
    echo "Debe llenar todos los campos requeridos.";
}
?>
