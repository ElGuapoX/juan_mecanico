<?php
/*conexión api: http://localhost/juan_mecanico/ver_datos?tabla=mecanico
*/

/*para usar la api en postman utilizar el en enlace siguiente, tener el método get puesto
y si requiere ver los datos de otra tabla cambiar el nombre de mecanico a otra tabla existente en la base de datos
por ejemplo: http://localhost/juan_mecanico/ver_datos?tabla=cliente */



// Incluir configuración y conexión a la base de datos
include_once 'config.php';
include_once 'php/db.php';

// Verificar si se pasa el nombre de la tabla como parámetro GET
if (isset($_GET['tabla']) && !empty($_GET['tabla'])) {
    $tabla = trim($_GET['tabla']); // Limpiar espacios en blanco

    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->connect();

    // Validar si la tabla existe en la base de datos
    $sql_verificar = "SHOW TABLES LIKE '$tabla'";
    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        // Si la tabla existe, obtener los datos
        try {
            $sql = "SELECT * FROM " . $tabla;
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
