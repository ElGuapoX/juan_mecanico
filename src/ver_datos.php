<?php
/*conexión api: http://localhost/juan_mecanico/ver_datos?tabla=mecanico
*/

/*para usar la api en postman utilizar el en enlace siguiente, tener el método get puesto
y si requiere ver los datos de otra tabla cambiar el nombre de mecanico a otra tabla existente en la base de datos
por ejemplo: http://localhost/juan_mecanico/ver_datos?tabla=cliente */



// Incluir configuración y conexión a la base de datos
include_once 'config.php';
// usar la conexión centralizada
include_once 'bd/bd.php';

// Verificar si se pasa el nombre de la tabla como parámetro GET
if (isset($_GET['tabla']) && !empty($_GET['tabla'])) {
    $tabla = trim($_GET['tabla']); // Limpiar espacios en blanco

    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->connect();

    // Validar que el nombre de tabla tenga solo caracteres permitidos
    if (!preg_match('/^[A-Za-z0-9_]+$/', $tabla)) {
        echo json_encode([
            "status" => "error",
            "message" => "Nombre de tabla inválido"
        ]);
        exit;
    }

    // Validar si la tabla existe en la base de datos usando consulta preparada
    $stmt_verificar = $conn->prepare("SHOW TABLES LIKE ?");
    $likePattern = $tabla;
    $stmt_verificar->bind_param('s', $likePattern);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar && $result_verificar->num_rows > 0) {
        // Si la tabla existe, obtener los datos
        try {
            // Nota: los identificadores de tabla no se pueden parametrizar.
            // Ya validamos que el nombre contiene solo caracteres seguros y existe.
            $sql = "SELECT * FROM `" . $tabla . "`";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                echo json_encode([
                    "status" => "success",
                    "data" => $data
                ]);
            } else {
                echo json_encode([
                    "status" => "success",
                    "data" => [],
                    "message" => "No hay datos en la tabla '{$tabla}'"
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Error al ejecutar la consulta: " . $e->getMessage()
            ]);
        }
    } else {
        // Tabla no encontrada
        echo json_encode([
            "status" => "error",
            "message" => "La tabla '{$tabla}' no existe en la base de datos"
        ]);
    }

    // Cerrar conexión
    $conn->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Debe proporcionar el nombre de la tabla en el parámetro 'tabla'"
    ]);
}
?>
