<?php
// Script helper para crear un usuario de prueba desde CLI:
// php src/setup_create_user.php correo contraseña [rol]
// ADVERTENCIA: ejecutar este script solo en desarrollo y eliminarlo después.

require_once __DIR__ . '/bd/bd.php';

if (php_sapi_name() !== 'cli') {
    echo "Este script debe ejecutarse desde la línea de comandos.\n";
    exit(1);
}

if ($argc < 3) {
    echo "Uso: php src/setup_create_user.php correo contraseña [rol]\n";
    exit(1);
}

$email = $argv[1];
$password = $argv[2];
$role = $argv[3] ?? 'usuario';

$db = new Database();
$conn = $db->connect();

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO CLIENTE (NOMBRE, APELLIDO, EMAIL, PASSWORD, ROL) VALUES (?, ?, ?, ?, ?)");
$nombre = 'Test';
$apellido = 'Usuario';
$stmt->bind_param('sssss', $nombre, $apellido, $email, $hash, $role);

if ($stmt->execute()) {
    echo "Usuario creado exitosamente: $email\n";
} else {
    echo "Error al crear usuario: " . $stmt->error . "\n";
}

$stmt->close();
$conn->close();

?>