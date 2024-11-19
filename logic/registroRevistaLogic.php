<?php
// Incluir el archivo de conexión a la base de datos
require_once '../includes/db_connection.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $numero_edicion = $_POST['numero_edicion'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $estado = $_POST['estado'] ?? 'A'; // Valor por defecto: 'A' (Activo)

    // Preparar la consulta para el procedimiento almacenado
    $sql = "BEGIN
                insertar_revista(:numero_edicion, :descripcion, :estado);
            END;";

    // Verificar que la conexión existe antes de ejecutar
    if ($conexion) {
        $stmt = oci_parse($conexion, $sql);

        // Asignar los valores a los parámetros del procedimiento
        oci_bind_by_name($stmt, ':numero_edicion', $numero_edicion);
        oci_bind_by_name($stmt, ':descripcion', $descripcion);
        oci_bind_by_name($stmt, ':estado', $estado);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            echo "Revista registrada con éxito.";
        } else {
            $error = oci_error($stmt);
            echo "Error al registrar la revista: " . $error['message'];
        }

        // Liberar los recursos
        oci_free_statement($stmt);
    } else {
        echo "Error: No se pudo conectar a la base de datos.";
    }

    // Cerrar la conexión a la base de datos
    oci_close($conexion);
}
?>

