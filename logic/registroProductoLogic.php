<?php
// Incluir el archivo de conexión a la base de datos
require_once '../includes/db_connection.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $comisionable = $_POST['comisionable'] ?? 'N'; // Valor por defecto: 'N'
    $estado = $_POST['estado'] ?? 'A'; // Activo por defecto

    // Manejo de la foto
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $carpeta_destino = "../uploads/";
        $nombre_archivo = basename($_FILES['foto']['name']);
        $ruta_archivo = $carpeta_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_archivo)) {
            $foto = $nombre_archivo;
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    // Preparar la consulta para el procedimiento almacenado
    $sql = "BEGIN
                insertar_producto(:nombre, :descripcion, :precio, :comisionable, :foto, :estado);
            END;";

    // Verificar que la conexión existe antes de ejecutar
    if ($conexion) {
        $stmt = oci_parse($conexion, $sql);

        // Asignar los valores a los parámetros del procedimiento
        oci_bind_by_name($stmt, ':nombre', $nombre);
        oci_bind_by_name($stmt, ':descripcion', $descripcion);
        oci_bind_by_name($stmt, ':precio', $precio);
        oci_bind_by_name($stmt, ':comisionable', $comisionable);
        oci_bind_by_name($stmt, ':foto', $foto);
        oci_bind_by_name($stmt, ':estado', $estado);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            echo "Producto registrado con éxito.";
        } else {
            $error = oci_error($stmt);
            echo "Error al registrar el producto: " . $error['message'];
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

