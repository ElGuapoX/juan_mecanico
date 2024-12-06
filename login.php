<?php
session_start();
include_once 'bd/bd.php'; 

$db = new Database();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar datos de entrada
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['contrasena']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Por favor, completa todos los campos.'); window.history.back();</script>";
        exit;
    }

    // Preparar la consulta
    $sql = "SELECT ID_CLIENTE, PASSWORD, ROL FROM CLIENTE WHERE EMAIL = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verificar contraseña
            if (password_verify($password, $user['PASSWORD'])) {
                // Establecer variables de sesión
                $_SESSION['usuario_id'] = $user['ID_CLIENTE'];
                $_SESSION['usuario_rol'] = $user['ROL'];

                // Actualizar última sesión
                $updateSession = $con->prepare("UPDATE CLIENTE SET ULTIMA_SESION = NOW() WHERE ID_CLIENTE = ?");
                $updateSession->bind_param("i", $user['ID_CLIENTE']);
                $updateSession->execute();

                // Redirigir según el rol
                if ($user['ROL'] === 'administrador') {
                    header("Location: ../admin.php");
                } else {
                    header("Location: ../home.php");
                }
                exit;
            } else {
                echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('El usuario no existe.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error al preparar la consulta.'); window.history.back();</script>";
    }

    $con->close();
}
?>
