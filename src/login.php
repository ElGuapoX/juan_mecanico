<?php
session_start();
include_once 'bd/bd.php'; 

$db = new Database();
$con = $db->connect();

// Si la petición incluye un destino (p.ej. ?next=registrocita), lo guardamos en sesión
if (isset($_GET['next']) && !empty($_GET['next'])) {
    $_SESSION['return_to'] = $_GET['next'];
}

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

                // Redirigir según el rol o al destino solicitado (guardado en sesión)
                $next = isset($_SESSION['return_to']) ? $_SESSION['return_to'] : null;
                if ($user['ROL'] === 'administrador') {
                    header("Location: php/admin/admin.php");
                } else {
                    // Si hay un destino solicitado y es 'registrocita', redirigir ahí
                    if ($next === 'registrocita') {
                        // Limpiamos la variable de sesión después de usarla
                        unset($_SESSION['return_to']);
                        header("Location: php/cliente/registrocita.php");
                    } else {
                        header("Location: php/cliente/home.html");
                    }
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
