<?php
// Incluir el archivo de conexión a la base de datos
include '../includes/db_connection.php';

// Activar la visualización de errores (para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar los datos del formulario y sanitizarlos
    $cedula = htmlspecialchars($_POST['cedula']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];  // La fecha se tomará tal cual, pero agregamos TO_DATE en el procedimiento almacenado
    $telefono = htmlspecialchars($_POST['telefono']);
    $primer_nombre = htmlspecialchars($_POST['primer_nombre']);
    $primer_apellido = htmlspecialchars($_POST['primer_apellido']);
    $segundo_apellido = htmlspecialchars($_POST['segundo_apellido']);
    $segundo_nombre = !empty($_POST['segundo_nombre']) ? htmlspecialchars($_POST['segundo_nombre']) : null;
    $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL) ? $_POST['correo'] : die("Correo no válido");
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $ciudad = intval($_POST['ciudad']);
    $rol = intval($_POST['rol']);
    $estado = htmlspecialchars($_POST['estado']);

    // Verificar la conexión a la base de datos
    if (!$conexion) {
        die("Error en la conexión a la base de datos.");
    }

    try {
        // Preparar la llamada al procedimiento almacenado, utilizando TO_DATE para convertir la fecha
        $stid = oci_parse($conexion, 'BEGIN REGISTRAR_PERSONA(:ciudad,:cedula, TO_DATE(:fecha_nacimiento, \'YYYY-MM-DD\'), :telefono, :primer_nombre, :primer_apellido, :segundo_apellido, :segundo_nombre, :correo, :contrasena, :rol, :estado); END;');

        // Vincular los parámetros a la consulta
        oci_bind_by_name($stid, ':cedula', $cedula);
        oci_bind_by_name($stid, ':fecha_nacimiento', $fecha_nacimiento); // La fecha será pasada tal cual
        oci_bind_by_name($stid, ':telefono', $telefono);
        oci_bind_by_name($stid, ':primer_nombre', $primer_nombre);
        oci_bind_by_name($stid, ':primer_apellido', $primer_apellido);
        oci_bind_by_name($stid, ':segundo_apellido', $segundo_apellido);
        oci_bind_by_name($stid, ':segundo_nombre', $segundo_nombre);
        oci_bind_by_name($stid, ':correo', $correo);
        oci_bind_by_name($stid, ':contrasena', $contrasena);
        oci_bind_by_name($stid, ':ciudad', $ciudad);
        oci_bind_by_name($stid, ':rol', $rol);
        oci_bind_by_name($stid, ':estado', $estado);

        // Ejecutar el procedimiento almacenado
        if (!oci_execute($stid)) {
            $e = oci_error($stid);
            error_log("Error al ejecutar el procedimiento: " . $e['message']); // Esto guarda el error en el log
            echo "Error al ejecutar el procedimiento: " . $e['message']; // Muestra el error en pantalla
            exit();
        }

        // Confirmar la transacción
        oci_commit($conexion);
       
        header("Location: ../index.php?registro=exitoso");
        exit();

    } catch (Exception $e) {
        oci_rollback($conexion);
        error_log("Se produjo un error: " . $e->getMessage());
        header("Location: ../pages/success.php?registro=error&mensaje=" . urlencode($e->getMessage()));
        exit();
    } finally {
        oci_free_statement($stid);
        oci_close($conexion);
    }
}
?>













